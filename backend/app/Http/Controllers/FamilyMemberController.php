<?php

namespace App\Http\Controllers;

use App\Helpers\RequestSearchHelper;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreFamilyMemberRequest;
use App\Http\Requests\UpdateFamilyMemberRequest;
use App\Http\Resources\FamilyMemberResource;
use App\Http\Resources\PaginateResource;
use App\Interface\FamilyMemberRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class FamilyMemberController extends Controller implements HasMiddleware
{
    private FamilyMemberRepositoryInterface $familyMemberRepository;

    public function __construct(FamilyMemberRepositoryInterface $familyMemberRepository)
    {
        $this->familyMemberRepository = $familyMemberRepository;
    }

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using(['family-member-list|family-member-create|family-member-edit|family-member-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['family-member-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['family-member-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['family-member-delete']), only: ['destroy'])
        ];
    }

    public function getPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            RequestSearchHelper::isValid($request);

            $data = $this->familyMemberRepository->getAllPaginated($request->search, $request->row_per_page);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data', PaginateResource::make($data, FamilyMemberResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->familyMemberRepository->getAll($request->search, $request->limit, true);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data', FamilyMemberResource::collection($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function store(StoreFamilyMemberRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->familyMemberRepository->create($request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Menambahkan Data', new FamilyMemberResource($data), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->familyMemberRepository->getById($id);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data Detail', new FamilyMemberResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function update(UpdateFamilyMemberRequest $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->familyMemberRepository->update($id, $request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Mengubah Data', new FamilyMemberResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->familyMemberRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'Berhasil Menghapus Data', new FamilyMemberResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }
}
