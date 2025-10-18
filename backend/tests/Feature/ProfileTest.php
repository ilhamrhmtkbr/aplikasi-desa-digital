<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\AuthTestCase;
use Illuminate\Support\Facades\Log;
use Tests\RepositoriesHelper\ProfileRepositoryHelper;

class ProfileTest extends AuthTestCase
{
    public function testProfileAsAdminGetAllSuccess(): void
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get('api/profile');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

    public function testProfileAsAdminCreateSuccess(): void
    {
        Storage::fake('public');
        $data = [
            'thumbnail' => UploadedFile::fake()->image('thumbnail1.jpg', 640, 480),
            'name' => 'person',
            'about' => 'lorem lorem lorem',
            'headman' => 'urang',
            'people' => 33,
            'agricultural_area' => 33.13,
            'total_area' => 33.13,
            'images' => [
                UploadedFile::fake()->image('thumbnail2.jpg', 640, 480),
                UploadedFile::fake()->image('thumbnail3.jpg', 640, 480)
            ],
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->post('api/profile', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'thumbnail',
                    'name',
                    'about',
                    'headman',
                    'people',
                    'agricultural_area',
                    'total_area',
                    'profile_images' => [
                        ['image']
                    ],
                ],
            ]);
    }

    public function testProfileAsAdminCreateFailedValidation(): void
    {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->post('api/profile', $data);

        $response->assertStatus(302);

    }

    public function testProfileAsAdminUpdateSuccess(): void
    {
        ProfileRepositoryHelper::insertProfile();
        $data = [
            'name' => 'person',
            'about' => 'lorem lorem lorem',
            'headman' => 'urang',
            'people' => 33,
            'agricultural_area' => 33.13,
            'total_area' => 33.13,
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->patch('api/profile', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'thumbnail',
                    'name',
                    'about',
                    'headman',
                    'people',
                    'agricultural_area',
                    'total_area',
                ],
            ]);
    }

    public function testProfileAsAdminUpdateFailedValidation(): void
    {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->patch('api/profile', $data);

        $response->assertStatus(302);
    }

    public function testProfileAsHeadOfFamilyGetAllSuccess(): void
    {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->get('api/profile');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }
}
