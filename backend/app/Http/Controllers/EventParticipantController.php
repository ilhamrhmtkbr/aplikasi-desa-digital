<?php

namespace App\Http\Controllers;

use App\Helpers\RequestSearchHelper;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreEventParticipantRequest;
use App\Http\Requests\UpdateEventParticipantRequest;
use App\Http\Resources\EventParticipantResource;
use App\Http\Resources\PaginateResource;
use App\Interface\EventParticipantRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventParticipantController extends Controller implements HasMiddleware
{
    private EventParticipantRepositoryInterface $eventParticipantRepository;

    public function __construct(EventParticipantRepositoryInterface $eventParticipantRepository)
    {
        $this->eventParticipantRepository = $eventParticipantRepository;
    }

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using(['event-participant-list|event-participant-create|event-participant-edit|event-participant-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['event-participant-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['event-participant-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['event-participant-delete']), only: ['destroy'])
        ];
    }

    public function getPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            RequestSearchHelper::isValid($request);

            $data = $this->eventParticipantRepository->getAllPaginated($request->search, $request->row_per_page);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data', PaginateResource::make($data, EventParticipantResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->eventParticipantRepository->getAll($request->search, $request->limit, true);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data', EventParticipantResource::collection($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function store(StoreEventParticipantRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->eventParticipantRepository->create($request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Menambahkan Data', new EventParticipantResource($data), 201);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->eventParticipantRepository->getById($id);
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data Detail', new EventParticipantResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function update(UpdateEventParticipantRequest $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->eventParticipantRepository->update($id, $request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Mengubah Data', new EventParticipantResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->eventParticipantRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'Berhasil Menghapus Data', new EventParticipantResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }
}
