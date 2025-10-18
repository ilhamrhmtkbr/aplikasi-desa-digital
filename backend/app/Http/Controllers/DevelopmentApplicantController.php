<?php

namespace App\Http\Controllers;

use App\Helpers\RequestSearchHelper;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreDevelopmentApplicantRequest;
use App\Http\Requests\UpdateDevelopmentApplicantRequest;
use App\Http\Resources\DevelopmentApplicantResource;
use App\Http\Resources\PaginateResource;
use App\Interface\DevelopmentApplicantRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DevelopmentApplicantController extends Controller implements HasMiddleware
{
    private DevelopmentApplicantRepositoryInterface $developmentApplicantRepositoryInterface;

    public function __construct(DevelopmentApplicantRepositoryInterface $developmentApplicantRepositoryInterface)
    {
        $this->developmentApplicantRepositoryInterface = $developmentApplicantRepositoryInterface;
    }

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using(['development-applicant-list|development-applicant-create|development-applicant-edit|development-applicant-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['development-applicant-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['development-applicant-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['development-applicant-delete']), only: ['destroy'])
        ];
    }

    public function getPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            RequestSearchHelper::isValid($request);

            $data = $this->developmentApplicantRepositoryInterface
                ->getAllPaginated(
                    $request->search,
                    $request->row_per_page
                );
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data', PaginateResource::make($data, DevelopmentApplicantResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->developmentApplicantRepositoryInterface
            ->getAll(
                $request->search,
                $request->limit,
                true
            );
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data', DevelopmentApplicantResource::collection($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function store(StoreDevelopmentApplicantRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->developmentApplicantRepositoryInterface->create($request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Menambahkan Data', new DevelopmentApplicantResource($data), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->developmentApplicantRepositoryInterface->getById($id);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data Detail', new DevelopmentApplicantResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function update(UpdateDevelopmentApplicantRequest $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->developmentApplicantRepositoryInterface->update($id, $request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Mengubah Data', new DevelopmentApplicantResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->developmentApplicantRepositoryInterface->delete($id);
            return ResponseHelper::jsonResponse(true, 'Berhasil Menghapus Data', new DevelopmentApplicantResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }
}
