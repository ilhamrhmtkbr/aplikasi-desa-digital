<?php

namespace App\Repositories;

use App\Interface\SocialAssistanceRecipientsRepositoryInterface;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Support\Facades\DB;

class SocialAssistanceRecipientsRepository implements SocialAssistanceRecipientsRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = SocialAssistanceRecipient::with(['socialAssistance'])->where(function ($query) use ($search) {
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
        return SocialAssistanceRecipient::with([
            'socialAssistance.socialAssistanceRecipients',
            'headOfFamily'
        ])->find($id);
    }

    public function create(array $data): SocialAssistanceRecipient
    {
        DB::beginTransaction();

        try {
            $socialAssistanceRecipient = new SocialAssistanceRecipient();
            $socialAssistanceRecipient->social_assistance_id = $data['social_assistance_id'];
            $socialAssistanceRecipient->head_of_family_id = $data['head_of_family_id'];
            $socialAssistanceRecipient->amount = $data['amount'];
            $socialAssistanceRecipient->reason = $data['reason'];
            $socialAssistanceRecipient->bank = $data['bank'];
            $socialAssistanceRecipient->account_number = $data['account_number'];
            if ($data['proof']) {
                $socialAssistanceRecipient->proof = $data['proof']->store('assets/social-assistance-recipients', 'public');
            }
            if (isset($data['status'])) {
                $socialAssistanceRecipient->status = $data['status'];
            }
            $socialAssistanceRecipient->save();

            DB::commit();

            return $socialAssistanceRecipient;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $socialAssistanceRecipient = SocialAssistanceRecipient::find($id);
            $socialAssistanceRecipient->amount = $data['amount'];
            $socialAssistanceRecipient->reason = $data['reason'];
            $socialAssistanceRecipient->bank = $data['bank'];
            $socialAssistanceRecipient->account_number = $data['account_number'];
            if (isset($data['proof'])) {
                $socialAssistanceRecipient->proof = $data['proof']->store('assets/social-assistance-recipients', 'public');;
            }
            if (isset($data['status'])) {
                $socialAssistanceRecipient->status = $data['status'];
            }
            $socialAssistanceRecipient->save();

            DB::commit();

            return $socialAssistanceRecipient;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $socialAssistanceRecipient = SocialAssistanceRecipient::find($id);
            $socialAssistanceRecipient->delete();
            DB::commit();
            return $socialAssistanceRecipient;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }
}
