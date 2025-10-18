<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\AuthTestCase;
use Tests\RepositoriesHelper\DevelopmentRepositoryHelper;
use Tests\SeedersHelper\DevelopmentSeeder;

class DevelopmentTest extends AuthTestCase
{
    public function testDevelopmentAsAdminGetPaginatedSuccess(): void {
        $this->seed([DevelopmentSeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/development/all/paginated?row_per_page=5');

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
                            'id',
                            'thumbnail',
                            'name',
                            'description',
                            'person_in_charge',
                            'start_date',
                            'end_date',
                            'amount',
                            'status',
                            'development_applicants',
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
                ],
            ]);
    }

    public function testDevelopmentAsAdminGetAllSuccess(): void {
        $this->seed([DevelopmentSeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/development');

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
                        'id',
                        'thumbnail',
                        'name',
                        'description',
                        'person_in_charge',
                        'start_date',
                        'end_date',
                        'amount',
                        'status',
                        'development_applicants',
                    ],
                ],
            ]);
    }

    public function testDevelopmentAsAdminShowSuccess(): void {
        DevelopmentRepositoryHelper::insertDevelopment();
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/development/'.DevelopmentRepositoryHelper::Development_ID);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Mendapatkan Data Detail',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'description',
                    'person_in_charge',
                    'start_date',
                    'end_date',
                    'amount',
                    'status',
                    'development_applicants',
                ],
            ]);
    }

    public function testDevelopmentAsAdminShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/development/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

    public function testDevelopmentAsAdminCreateSuccess(): void {
        Storage::fake('public');

        $data = [
            'thumbnail'         => UploadedFile::fake()->image('thumbnail.jpg', 640, 480), // Fake image
            'name'              => 'PT Marpaung Sejahtera',
            'description'       => 'Proyek pembangunan gedung kantor cabang baru di Jakarta Selatan.',
            'person_in_charge'  => 'Tina Nasyiah',
            'start_date'        => '2025-05-10',
            'end_date'          => '2025-11-20',
            'amount'            => 750000,
            'status'            => 'ongoing',
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')->post('api/development', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'description',
                    'person_in_charge',
                    'start_date',
                    'end_date',
                    'amount',
                    'status',
                ],
        ]);
    }

    public function testDevelopmentAsAdminCreateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/development', $data);

        $response->assertStatus(302);
    }

    public function testDevelopmentAsAdminUpdateSuccess(): void {
        DevelopmentRepositoryHelper::insertDevelopment();
        $data = [
            'name'              => 'PT Marpaung Sejahtera',
            'description'       => 'Proyek pembangunan gedung kantor cabang baru di Jakarta Selatan.',
            'person_in_charge'  => 'Tina Nasyiah',
            'start_date'        => '2025-05-10',
            'end_date'          => '2025-11-20',
            'amount'            => 750000,
            'status'            => 'ongoing',
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/development/'.DevelopmentRepositoryHelper::Development_ID, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'name',
                    'description',
                    'person_in_charge',
                    'start_date',
                    'end_date',
                    'amount',
                    'status',
                ],
            ]);
    }

    public function testDevelopmentAsAdminUpdateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/development/id', $data);

        $response->assertStatus(302);
    }

    public function testDevelopmentAsAdminDeleteSuccess(): void {
        DevelopmentRepositoryHelper::insertDevelopment();

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/development/'.DevelopmentRepositoryHelper::Development_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'name',
                    'description',
                    'person_in_charge',
                    'start_date',
                    'end_date',
                    'amount',
                    'status',
                ],
            ]);
    }

    public function testDevelopmentAsAdminDeleteFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/development/id');

        $response->assertStatus(500);
    }

    public function testDevelopmentAsHeadOfFamilyGetPaginatedSuccess(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/development/all/paginated?row_per_page=5');

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
                            'id',
                            'thumbnail',
                            'name',
                            'description',
                            'person_in_charge',
                            'start_date',
                            'end_date',
                            'amount',
                            'status',
                            'development_applicants',
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
                ],
            ]);
    }

    public function testDevelopmentAsHeadOfFamilyGetAllSuccess(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/development');

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
                        'id',
                        'thumbnail',
                        'name',
                        'description',
                        'person_in_charge',
                        'start_date',
                        'end_date',
                        'amount',
                        'status',
                        'development_applicants',
                    ],
                ],
            ]);
    }

    public function testDevelopmentAsHeadOfFamilyShowSuccess(): void {
        DevelopmentRepositoryHelper::insertDevelopment();
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/development/'.DevelopmentRepositoryHelper::Development_ID);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Mendapatkan Data Detail',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'description',
                    'person_in_charge',
                    'start_date',
                    'end_date',
                    'amount',
                    'status',
                    'development_applicants',
                ],
            ]);
    }

    public function testDevelopmentAsHeadOfFamilyShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/development/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }
}
