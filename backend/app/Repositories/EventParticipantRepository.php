<?php

namespace App\Repositories;

use App\Interface\EventParticipantRepositoryInterface;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;

class EventParticipantRepository implements EventParticipantRepositoryInterface
{

    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = EventParticipant::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        });

        if (auth()->user()->hasRole('head-of-family')) {
            $query->where('head_of_family_id', auth()->user()->headOfFamily->id);
        }

        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search, ?int $rowPerPage)
    {
        $query = $this->getAll($search, $rowPerPage, false);

        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = EventParticipant::where('id', $id);
        return $query->first();
    }

    public function create(array $data): EventParticipant
    {
        DB::beginTransaction();

        try {
            $eventParticipant = new EventParticipant();
            $eventParticipant->event_id = $data['event_id'];
            $eventParticipant->head_of_family_id = $data['head_of_family_id'];
            $eventParticipant->quantity = $data['quantity'];
            $eventParticipant->total_price = (int) floor(floatval($data['total_price']));
            $eventParticipant->payment_status = 'pending';
            $eventParticipant->save();

            DB::commit();

            Config::$serverKey = config('midtrans.serverKey');
            Config::$isProduction = config('midtrans.isProduction');
            Config::$isSanitized = config('midtrans.isSanitized');
            Config::$is3ds = config('midtrans.is3ds');

            $params = [
                'transaction_details' => [
                    'order_id' => $eventParticipant->id,
                    'gross_amount' => $eventParticipant->total_price,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $eventParticipant->snap_token = $snapToken;
            $eventParticipant->save();

            return $eventParticipant;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $eventParticipant = EventParticipant::find($id);
            $eventParticipant->quantity = $data['quantity'];
            $eventParticipant->total_price = $data['total_price'];
            $eventParticipant->payment_status = $data['payment_status'];
            $eventParticipant->save();

            DB::commit();

            return $eventParticipant;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $eventParticipant = EventParticipant::find($id);
            $eventParticipant->delete();
            DB::commit();
            return $eventParticipant;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }
}
