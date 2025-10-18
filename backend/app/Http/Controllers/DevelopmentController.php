<?php

namespace App\Http\Controllers;

use App\Helpers\RequestSearchHelper;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreDevelopmentRequest;
use App\Http\Requests\UpdateDevelopmentRequest;
use App\Http\Resources\DevelopmentResource;
use App\Http\Resources\PaginateResource;
use App\Interface\DevelopmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DevelopmentController extends Controller implements HasMiddleware
{
    private DevelopmentRepositoryInterface $developmentRepository;

    public function __construct(DevelopmentRepositoryInterface $developmentRepository)
    {
        $this->developmentRepository = $developmentRepository;
    }

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using(['development-list|development-create|development-edit|development-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['development-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['development-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['development-delete']), only: ['destroy'])
        ];
    }

    public function getPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            RequestSearchHelper::isValid($request);

            $data = $this->developmentRepository->getAllPaginated($request->search, $request->status, $request->row_per_page);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data', PaginateResource::make($data, DevelopmentResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->developmentRepository->getAll($request->search, $request->status, $request->limit, true);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data', DevelopmentResource::collection($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function store(StoreDevelopmentRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->developmentRepository->create($request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Menambahkan Data', new DevelopmentResource($data), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->developmentRepository->getById($id);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data Detail', new DevelopmentResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function update(UpdateDevelopmentRequest $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->developmentRepository->update($id, $request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Mengubah Data', new DevelopmentResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->developmentRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'Berhasil Menghapus Data', new DevelopmentResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }
}
