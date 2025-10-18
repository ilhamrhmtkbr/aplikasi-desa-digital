<?php

namespace App\Repositories;

use App\Interface\DevelopmentRepositoryInterface;
use App\Models\Development;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DevelopmentRepository implements DevelopmentRepositoryInterface
{

    public function getAll(?string $search, ?string $status, ?int $limit, bool $execute)
    {
        $query = Development::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        })->with(['developmentApplicants']);

        if ($status === 'my-applications') {
            $user = auth()->user();
            $members = [$user->id];

            if ($user->headOfFamily) {
                $familyMembers = FamilyMember::where('head_of_family_id', $user->headOfFamily->id)
                    ->pluck('user_id')
                    ->toArray();
                $members = array_unique(array_merge($members, $familyMembers));
            }

            Log::info('Filtering by members: ', $members);

            $query->whereHas('developmentApplicants', function ($q) use ($members) {
                $q->whereIn('user_id', $members);
            });

            // Debug query
            Log::info('SQL Query: ' . $query->toSql());
            Log::info('Bindings: ', $query->getBindings());
        }

        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search,?string $status, ?int $rowPerPage)
    {
        $query = $this->getAll($search, $status, $rowPerPage, false);

        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = Development::where('id', $id)->with(['developmentApplicants']);
        return $query->first();
    }

    public function create(array $data): Development
    {
        DB::beginTransaction();

        try {
            $development = new Development();
            $development->thumbnail = $data['thumbnail']->store('assets/developments', 'public');
            $development->name = $data['name'];
            $development->description = $data['description'];
            $development->person_in_charge = $data['person_in_charge'];
            $development->start_date = $data['start_date'];
            $development->end_date = $data['end_date'];
            $development->amount = $data['amount'];
            $development->status = $data['status'];
            $development->save();

            DB::commit();

            return $development;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $development = Development::find($id);
            if (isset($data['thumbnail'])) {
                $development->thumbnail = $data['thumbnail']->store('assets/developments', 'public');
            }
            $development->name = $data['name'];
            $development->description = $data['description'];
            $development->person_in_charge = $data['person_in_charge'];
            $development->start_date = $data['start_date'];
            $development->end_date = $data['end_date'];
            $development->amount = $data['amount'];
            $development->status = $data['status'];
            $development->save();

            DB::commit();

            return $development;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $development = Development::find($id);
            $development->delete();
            DB::commit();
            return $development;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }
}
