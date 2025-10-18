<?php

namespace Tests\Feature;

use Tests\AuthTestCase;
use Illuminate\Support\Facades\Log;
use Tests\RepositoriesHelper\AuthRepositoryHelper;
use Tests\RepositoriesHelper\DevelopmentApplicantRepositoryHelper;
use Tests\RepositoriesHelper\DevelopmentRepositoryHelper;
use Tests\SeedersHelper\DevelopmentApplicantSeeder;
use Tests\SeedersHelper\DevelopmentSeeder;

class DevelopmentApplicantTest extends AuthTestCase
{
    public function testDevelopmentApplicantAsAdminGetPaginatedSuccess(): void {
        $this->seed([DevelopmentSeeder::class, DevelopmentApplicantSeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/development-applicant/all/paginated?row_per_page=5');

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
                            'development' => [
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
                            'user' => [
                                'id',
                                'name',
                                'email',
                            ],
                            'status',
                            'created_at',
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

    public function testDevelopmentApplicantAsAdminGetAllSuccess(): void {
        $this->seed([DevelopmentSeeder::class, DevelopmentApplicantSeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/development-applicant');

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
                        'development' => [
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
                        'user' => [
                            'id',
                            'name',
                            'email',
                        ],
                        'status',
                        'created_at',
                    ],
                ],
            ]);
    }

    public function testDevelopmentApplicantAsAdminShowSuccess(): void {
        DevelopmentApplicantRepositoryHelper::insertDevelopmentApplicant();
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/development-applicant/'.DevelopmentApplicantRepositoryHelper::DevelopmentApplicant_ID);

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
                    'development' => [
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
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'status',
                ],
            ]);
    }

    public function testDevelopmentApplicantAsAdminShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/development-applicant/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

    public function testDevelopmentApplicantAsAdminCreateSuccess(): void {
        DevelopmentRepositoryHelper::insertDevelopment();
        $data = [
            'development_id' => DevelopmentRepositoryHelper::Development_ID,
            'user_id' => AuthRepositoryHelper::getHeadOfFamilyId(),
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/development-applicant', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Menambahkan Data',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'development' => [
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
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'status',
                    'created_at',
                ],
            ]);
    }

    public function testDevelopmentApplicantAsAdminCreateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/development-applicant', $data);

        $response->assertStatus(302);
    }

    public function testDevelopmentApplicantAsAdminUpdateSuccess(): void {
        DevelopmentApplicantRepositoryHelper::insertDevelopmentApplicant();

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/development-applicant/'.DevelopmentApplicantRepositoryHelper::DevelopmentApplicant_ID, [
                        'status' => 'pending'
                    ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Mengubah Data',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'development' => [
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
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'status',
                    'created_at',
                ],
            ])
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.development.name', 'PT Marpaung Sejahtera')
            ->assertJsonPath('data.message', null);
    }

    public function testDevelopmentApplicantAsAdminUpdateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/development-applicant/id', $data);

        $response->assertStatus(302);
    }

    public function testDevelopmentApplicantAsAdminDeleteSuccess(): void {
        DevelopmentApplicantRepositoryHelper::insertDevelopmentApplicant();

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->delete('api/development-applicant/'.DevelopmentApplicantRepositoryHelper::DevelopmentApplicant_ID);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Menghapus Data',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'development' => [
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
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'status',
                    'created_at',
                ],
            ]);
    }

    public function testDevelopmentApplicantAsAdminDeleteFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/development-applicant/id');

        $response->assertStatus(500);
    }

    public function testDevelopmentApplicantAsHeadOfFamilyGetPaginatedSuccess(): void {
        $this->seed([DevelopmentSeeder::class, DevelopmentApplicantSeeder::class]);
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/development-applicant/all/paginated?row_per_page=5');

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
                            'development' => [
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
                            'user' => [
                                'id',
                                'name',
                                'email',
                            ],
                            'status',
                            'created_at',
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

    public function testDevelopmentApplicantAsHeadOfFamilyGetAllSuccess(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/development-applicant');

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
                        'development' => [
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
                        'user' => [
                            'id',
                            'name',
                            'email',
                        ],
                        'status',
                        'created_at',
                    ],
                ],
            ]);
    }

    public function testDevelopmentApplicantAsHeadOfFamilyShowSuccess(): void {
        DevelopmentApplicantRepositoryHelper::insertDevelopmentApplicant();
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->get('api/development-applicant/'.DevelopmentApplicantRepositoryHelper::DevelopmentApplicant_ID);

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
                    'development' => [
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
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'status',
                ],
            ]);
    }

    public function testDevelopmentApplicantAsHeadOfFamilyShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/development-applicant/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

    public function testDevelopmentApplicantAsHeadOfFamilyCreateSuccess(): void {
        DevelopmentRepositoryHelper::insertDevelopment();
        $data = [
            'development_id' => DevelopmentRepositoryHelper::Development_ID,
            'user_id' => AuthRepositoryHelper::getHeadOfFamilyId(),
        ];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->post('api/development-applicant', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Menambahkan Data',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'development' => [
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
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'status',
                    'created_at',
                ],
            ]);
    }

    public function testDevelopmentApplicantAsHeadOfFamilyCreateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->post('api/development-applicant', $data);

        $response->assertStatus(302);
    }

    public function testDevelopmentApplicantAsHeadOfFamilyUpdateSuccess(): void {
        DevelopmentApplicantRepositoryHelper::insertDevelopmentApplicant();

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->patch('api/development-applicant/'.DevelopmentApplicantRepositoryHelper::DevelopmentApplicant_ID, [
                'status' => 'pending'
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Mengubah Data',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'development' => [
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
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'status',
                    'created_at',
                ],
            ])
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.development.name', 'PT Marpaung Sejahtera')
            ->assertJsonPath('data.message', null);
    }

    public function testDevelopmentApplicantAsHeadOfFamilyUpdateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->patch('api/development-applicant/id', $data);

        $response->assertStatus(302);
    }

    public function testDevelopmentApplicantAsHeadOfFamilyDeleteSuccess(): void {
        DevelopmentApplicantRepositoryHelper::insertDevelopmentApplicant();

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->delete('api/development-applicant/'.DevelopmentApplicantRepositoryHelper::DevelopmentApplicant_ID);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Menghapus Data',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'development' => [
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
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'status',
                    'created_at',
                ],
            ])
            ->assertJsonPath('data.id', 'CU5T0M1D-11aa-22bb-33cc-44dd55ee66ff')
            ->assertJsonPath('data.development.name', 'PT Marpaung Sejahtera')
            ->assertJsonPath('data.status', 'pending');
    }

    public function testDevelopmentApplicantAsHeadOfFamilyDeleteFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->delete('api/development-applicant/id');

        $response->assertStatus(500);
    }
}
