<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\AuthTestCase;
use Illuminate\Support\Facades\Log;
use Tests\RepositoriesHelper\FamilyMemberRepositoryHelper;
use Tests\RepositoriesHelper\HeadOfFamilyRepositoryHelper;
use Tests\SeedersHelper\HeadOfFamilySeeder;

class FamilyMemberTest extends AuthTestCase
{
    public function testFamilyMemberAsAdminGetPaginatedSuccess(): void {
        $this->seed([HeadOfFamilySeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/family-member/all/paginated?row_per_page=5');

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

    public function testFamilyMemberAsAdminGetAllSuccess(): void {
        $this->seed([HeadOfFamilySeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/family-member');

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

    public function testFamilyMemberAsAdminShowSuccess(): void {
        FamilyMemberRepositoryHelper::insertFamilyMember();
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/family-member/'.FamilyMemberRepositoryHelper::FamilyMember_ID);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Mendapatkan Data Detail',
            ])
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
                    'relation',
                ]]);
    }

    public function testFamilyMemberAsAdminShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/family-member/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

    public function testFamilyMemberAsAdminCreateSuccess(): void {
        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();
        Storage::fake('public');
        $data = [
            'head_of_family_id' => HeadOfFamilyRepositoryHelper::HeadOfFamily_ID,
            'profile_picture'   => UploadedFile::fake()->image('thumbnail.jpg', 640, 480),
            'identify_number'   => '123123123',
            'gender'            => 'male',
            'date_of_birth'     => '2000-03-25',
            'phone_number'      => '081218723006',
            'occupation'        => 'programmer',
            'marital_status'    => 'single',
            'relation'          => 'child',
            'name'              => 'person3',
            'email'             => 'person3@gmail.com',
            'password'          => 'password'
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/family-member', $data);

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
                    'date_of_birth',
                    'gender',
                    'phone_number',
                    'occupation',
                    'marital_status',
                    'relation',
                ],
            ]);
    }

    public function testFamilyMemberAsAdminCreateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/family-member', $data);

        $response->assertStatus(302);
    }

    public function testFamilyMemberAsAdminUpdateSuccess(): void {
        FamilyMemberRepositoryHelper::insertFamilyMember();
        $data = [
            'identify_number'   => '123123123',
            'gender'            => 'male',
            'date_of_birth'     => '2000-03-25',
            'phone_number'      => '081218723006',
            'occupation'        => 'programmer',
            'marital_status'    => 'single',
            'relation'          => 'child',
            'name'              => 'person3',
            'email'             => 'person3@gmail.com',
            'password'          => 'password'
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/family-member/'.FamilyMemberRepositoryHelper::FamilyMember_ID, $data);

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
                    'date_of_birth',
                    'gender',
                    'phone_number',
                    'occupation',
                    'marital_status',
                    'relation',
                ],
            ]);
    }

    public function testFamilyMemberAsAdminUpdateFailedValidation(): void {
        FamilyMemberRepositoryHelper::insertFamilyMember();

        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/family-member/'.FamilyMemberRepositoryHelper::FamilyMember_ID, $data);

        $response->assertStatus(302);
    }

    public function testFamilyMemberAsAdminDeleteSuccess(): void {
        FamilyMemberRepositoryHelper::insertFamilyMember();

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/family-member/'.FamilyMemberRepositoryHelper::FamilyMember_ID);

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
                    'date_of_birth',
                    'gender',
                    'phone_number',
                    'occupation',
                    'marital_status',
                    'relation',
                ],
            ]);
    }

    public function testFamilyMemberAsAdminDeleteFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/family-member/id');

        $response->assertStatus(500);
    }

    public function testFamilyMemberAsHeadOfFamilyGetPaginatedSuccess(): void {
        $this->seed([HeadOfFamilySeeder::class]);

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/family-member/all/paginated?row_per_page=5');

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

    public function testFamilyMemberAsHeadOfFamilyGetAllSuccess(): void {
        $this->seed([HeadOfFamilySeeder::class]);

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/family-member');

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

    public function testFamilyMemberAsHeadOfFamilyShowSuccess(): void {
        FamilyMemberRepositoryHelper::insertFamilyMember();

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/family-member/'.FamilyMemberRepositoryHelper::FamilyMember_ID);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Mendapatkan Data Detail',
            ])
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
                    'relation',
                ]]);
    }

    public function testFamilyMemberAsHeadOfFamilyShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/family-member/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

    public function testFamilyMemberAsHeadOfFamilyCreateSuccess(): void {
        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();

        Storage::fake('public');
        $data = [
            'head_of_family_id' => HeadOfFamilyRepositoryHelper::HeadOfFamily_ID,
            'profile_picture'   => UploadedFile::fake()->image('thumbnail.jpg', 640, 480),
            'identify_number'   => '123123123',
            'gender'            => 'male',
            'date_of_birth'     => '2000-03-25',
            'phone_number'      => '081218723006',
            'occupation'        => 'programmer',
            'marital_status'    => 'single',
            'relation'          => 'child',
            'name'              => 'person3',
            'email'             => 'person3@gmail.com',
            'password'          => 'password'
        ];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->post('api/family-member', $data);

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
                    'date_of_birth',
                    'gender',
                    'phone_number',
                    'occupation',
                    'marital_status',
                    'relation',
                ],
            ]);
    }

    public function testFamilyMemberAsHeadOfFamilyCreateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->post('api/family-member', $data);

        $response->assertStatus(302);
    }

    public function testFamilyMemberAsHeadOfFamilyUpdateSuccess(): void {
        FamilyMemberRepositoryHelper::insertFamilyMember();
        $data = [
            'identify_number'   => '123123123',
            'gender'            => 'male',
            'date_of_birth'     => '2000-03-25',
            'phone_number'      => '081218723006',
            'occupation'        => 'programmer',
            'marital_status'    => 'single',
            'relation'          => 'child',
            'name'              => 'person3',
            'email'             => 'person3@gmail.com',
            'password'          => 'password'
        ];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->patch('api/family-member/'.FamilyMemberRepositoryHelper::FamilyMember_ID, $data);

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
                    'date_of_birth',
                    'gender',
                    'phone_number',
                    'occupation',
                    'marital_status',
                    'relation',
                ],
            ]);
    }

    public function testFamilyMemberAsHeadOfFamilyUpdateFailedValidation(): void {
        FamilyMemberRepositoryHelper::insertFamilyMember();

        $data = [];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->patch('api/family-member/'.FamilyMemberRepositoryHelper::FamilyMember_ID, $data);

        $response->assertStatus(302);
    }

    public function testFamilyMemberAsHeadOfFamilyDeleteSuccess(): void {
        FamilyMemberRepositoryHelper::insertFamilyMember();

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->delete('api/family-member/'.FamilyMemberRepositoryHelper::FamilyMember_ID);

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
                    'date_of_birth',
                    'gender',
                    'phone_number',
                    'occupation',
                    'marital_status',
                    'relation',
                ],
            ]);
    }

    public function testFamilyMemberAsHeadOfFamilyDeleteFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->delete('api/family-member/id');

        $response->assertStatus(500);
    }
}
