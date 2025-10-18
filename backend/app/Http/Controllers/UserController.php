<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\UserResource;
use App\Interface\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function getAllPaginated(Request $request)
    {
        $valid = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $users = $this->userRepositoryInterface->getAllPaginated(
                $valid['search'] ?? null,
                $valid['row_per_page'],
                true
            );

            Log::info('Get users paginated', $valid);

            return ResponseHelper::jsonResponse(
                true,
                'Data User Berhasil Diambil',
                PaginateResource::make($users, UserResource::class),
                200
            );
        } catch (\Exception $e) {
            Log::error('Error getAllPaginated: ' . $e->getMessage());

            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = $this->userRepositoryInterface->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'Data User Berhasil Diambil', UserResource::collection($users), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();

        try {
            $user = $this->userRepositoryInterface->create($data);

            return ResponseHelper::jsonResponse(true, 'User Berhasil Ditambahkan', new UserResource($user), 201);
        }catch (\Exception $e){
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userRepositoryInterface->getById($id);
            if (!$user){
                return ResponseHelper::jsonResponse(false, 'User tidak ditemukan', null, 404);
            }
            return ResponseHelper::jsonResponse(true, 'Detail User berhasil diambil', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        try {
            $user = $this->userRepositoryInterface->getById($id);

            if (!$user) {
                return ResponseHelper::jsonResponse(false, 'User Tidak ditemukan', null, 404);
            }

            $updateUser = $this->userRepositoryInterface->update($id, $request->validated());

            return ResponseHelper::jsonResponse(true, 'Berhasil Update User', $updateUser, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = $this->userRepositoryInterface->getById($id);

            if (!$user) {
                return ResponseHelper::jsonResponse(false, 'User Tidak ditemukan', null, 404);
            }

            $deleteUser = $this->userRepositoryInterface->delete($id);

            return ResponseHelper::jsonResponse(true, 'Berhasil Hapus User', $deleteUser, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 422);
        }
    }
}
