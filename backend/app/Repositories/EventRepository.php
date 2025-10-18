<?php

namespace App\Repositories;

use App\Interface\EventRepositoryInterface;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventRepository implements EventRepositoryInterface
{

    public function getAll(?string $search, ?string $status, ?int $limit, bool $execute)
    {
        $query = Event::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        })->with(['eventParticipant']);

        if ($status === 'joined') {
            $query->whereHas('eventParticipant', function ($query) {
                $query->where('head_of_family_id', auth()->user()->headOfFamily->id);
            });
        }


        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search, ?string $status, ?int $rowPerPage)
    {
        $query = $this->getAll($search, $status, $rowPerPage, false);

        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = Event::where('id', $id)->with(['eventParticipant']);
        return $query->first();
    }

    public function create(array $data): Event
    {
        DB::beginTransaction();

        try {
            $event = new Event();
            $event->thumbnail = $data['thumbnail']->store('assets/events', 'public');
            $event->name = $data['name'];
            $event->description = $data['description'];
            $event->price = $data['price'];
            $event->date = $data['date'];
            $event->time = $data['time'];
            $event->is_active = $data['is_active'];
            $event->save();

            DB::commit();

            return $event;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $event = Event::find($id);
            if (isset($data['thumbnail'])) {
                $event->thumbnail = $data['thumbnail']->store('assets/events', 'public');
            }
            $event->name = $data['name'];
            $event->description = $data['description'];
            $event->price = $data['price'];
            $event->date = $data['date'];
            $event->time = $data['time'];
            $event->is_active = $data['is_active'];
            $event->save();

            DB::commit();

            return $event;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $event = Event::find($id);
            $event->delete();
            DB::commit();
            return $event;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }
}
