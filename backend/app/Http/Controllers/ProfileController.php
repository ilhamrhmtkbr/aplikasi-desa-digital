<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Interface\ProfileRepositoryInterface;

class ProfileController extends Controller
{
    private ProfileRepositoryInterface $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->profileRepository->get();
            if (!$data){
                return ResponseHelper::jsonResponse(true, 'Data kosong', null, 200);
            }
            return ResponseHelper::jsonResponse(true, 'Berhasil Mendapatkan Data Detail', new ProfileResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function store(StoreProfileRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->profileRepository->create($request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Menambahkan Data', new ProfileResource($data), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }

    public function update(UpdateProfileRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->profileRepository->update($request->validated());
            return ResponseHelper::jsonResponse(true, 'Berhasil Mengubah Data', new ProfileResource($data), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, $e->getCode());
        }
    }
}
