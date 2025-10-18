<?php

namespace App\Repositories;

use App\Interface\ProfileRepositoryInterface;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function get()
    {
        return Profile::first();
    }

    public function create(array $data): Profile
    {
        DB::beginTransaction();

        try {
            // DEBUG: Log data yang masuk
            Log::info('Profile Create Data:', [
                'has_thumbnail' => isset($data['thumbnail']),
                'has_images' => isset($data['images']),
                'images_count' => isset($data['images']) ? count($data['images']) : 0,
            ]);

            $profile = new Profile();

            // Store thumbnail
            if (isset($data['thumbnail'])) {
                $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            }

            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agricultural_area = $data['agricultural_area'];
            $profile->total_area = $data['total_area'];

            $profile->save();

            // Store additional images
            if (isset($data['images']) && is_array($data['images'])) {
                Log::info('Processing images:', ['count' => count($data['images'])]);

                foreach ($data['images'] as $index => $image) {
                    Log::info("Storing image {$index}");

                    $profileImage = $profile->profileImages()->create([
                        'image' => $image->store('assets/profiles', 'public')
                    ]);

                    Log::info("Image {$index} stored:", ['id' => $profileImage->id]);
                }
            }

            DB::commit();

            // Reload profile with images
            $profile->load('profileImages');
            Log::info('Profile created with images:', ['images_count' => $profile->profileImages->count()]);

            return $profile;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Profile creation failed:', ['error' => $e->getMessage()]);
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data)
    {
        DB::beginTransaction();

        try {
            $profile = Profile::first();

            if (isset($data['thumbnail'])) {
                $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            }

            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agricultural_area = $data['agricultural_area'];
            $profile->total_area = $data['total_area'];

            $profile->save();

            if (isset($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $image) {
                    $profile->profileImages()->create([
                        'image' => $image->store('assets/profiles', 'public')
                    ]);
                }
            }

            DB::commit();

            return $profile;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
