<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\AuthTestCase;
use Illuminate\Support\Facades\Log;
use Tests\RepositoriesHelper\HeadOfFamilyRepositoryHelper;
use Tests\SeedersHelper\HeadOfFamilySeeder;

class HeadOfFamilyTest extends AuthTestCase
{
    public function testHeadOfFamilyAsAdminGetPaginatedSuccess(): void {
        $this->seed([HeadOfFamilySeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/head-of-family/all/paginated?row_per_page=5');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Mendapatkan Data',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                        ],
                    ],
                    'meta' => [
                        'current_page',
                        'from',
                        'last_page',
                        'path',
                        'per_page',
                        'to',
                        'total',
                    ],
                ]]);
    }

    public function testHeadOfFamilyAsAdminGetAllSuccess(): void {
        $this->seed([HeadOfFamilySeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/head-of-family');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Mendapatkan Data',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                    ],
                ]]);
    }

    public function testHeadOfFamilyAsAdminShowSuccess(): void {
        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/head-of-family/'.HeadOfFamilyRepositoryHelper::HeadOfFamily_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'profile_picture',
                    'identify_number',
                    'gender',
                    'date_of_birth',
                    'phone_number',
                    'occupation',
                    'marital_status',
                    'family_members',
                ],
            ]);
    }

    public function testHeadOfFamilyAsAdminShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/head-of-family/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

    public function testHeadOfFamilyAsAdminCreateSuccess(): void {
        Storage::fake('public');
        $data = [
            'name'              => 'required',
            'email'             => 'required@gmail.com',
            'password'          => 'required',
            'profile_picture'   => UploadedFile::fake()->image('thumbnail.jpg', 640, 480),
            'identify_number'   => '123321',
            'gender'            => 'male',
            'phone_number'      => '0911919',
            'occupation'        => 'programmer',
            'marital_status'    => 'single',
            'date_of_birth'     => '2005-03-01',
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/head-of-family', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'profile_picture',
                    'identify_number',
                    'gender',
                    'date_of_birth',
                    'phone_number',
                    'occupation',
                    'marital_status',
                    'family_members',
                ],
            ]);
    }

    public function testHeadOfFamilyAsAdminCreateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/head-of-family', $data);

        $response->assertStatus(302);
    }

    public function testHeadOfFamilyAsAdminUpdateSuccess(): void {
        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();
        $data = [
            'name'              => 'update',
            'email'              => 'update@gmail.com',
            'identify_number'   => '123321',
            'gender'            => 'male',
            'phone_number'      => '0911919',
            'occupation'        => 'programmer',
            'marital_status'    => 'single',
            'date_of_birth'     => '2005-03-01',
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/head-of-family/'.HeadOfFamilyRepositoryHelper::HeadOfFamily_ID, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'profile_picture',
                    'identify_number',
                    'gender',
                    'date_of_birth',
                    'phone_number',
                    'occupation',
                    'marital_status',
                    'family_members',
                ],
            ]);
    }

    public function testHeadOfFamilyAsAdminUpdateFailedValidation(): void {
        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();

        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/head-of-family/'.HeadOfFamilyRepositoryHelper::HeadOfFamily_ID, $data);

        $response->assertStatus(302);
    }

    public function testHeadOfFamilyAsAdminDeleteSuccess(): void {
        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/head-of-family/'.HeadOfFamilyRepositoryHelper::HeadOfFamily_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'profile_picture',
                    'identify_number',
                    'gender',
                    'date_of_birth',
                    'phone_number',
                    'occupation',
                    'marital_status',
                    'family_members',
                ],
            ]);
    }

    public function testHeadOfFamilyAsAdminDeleteFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/head-of-family/id');

        $response->assertStatus(500);
    }
}
