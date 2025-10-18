<?php

namespace App\Http\Controllers;

use App\Helpers\RequestSearchHelper;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\PaginateResource;
use App\Interface\EventRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventController extends Controller implements HasMiddleware
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using(['event-list|event-create|event-edit|event-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['event-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['event-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['event-delete']), only: ['destroy'])
        ];
    }

    public function getPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            RequestSearchHelper::isValid($request);

            $data = $this->eventRepository->getAllPaginated($request->search, $request->status, $request->row_per_page);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data', PaginateResource::make($data, EventResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->eventRepository->getAll($request->search, $request->status, $request->limit, true);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data', EventResource::collection($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function store(StoreEventRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->eventRepository->create($request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Menambahkan Data', new EventResource($data), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->eventRepository->getById($id);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data Detail', new EventResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function update(UpdateEventRequest $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->eventRepository->update($id, $request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Mengubah Data', new EventResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->eventRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'Berhasil Menghapus Data', new EventResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }
}
