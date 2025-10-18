<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\AuthTestCase;
use Illuminate\Support\Facades\Log;
use Tests\RepositoriesHelper\SocialAssistanceRepositoryHelper;
use Tests\SeedersHelper\SocialAssistanceSeeder;

class SocialAssistanceTest extends AuthTestCase
{
    public function testSocialAssistanceAsAdminGetPaginatedSuccess(): void {
        $this->seed([SocialAssistanceSeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/social-assistance/all/paginated?row_per_page=5');

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

    public function testSocialAssistanceAsAdminGetAllSuccess(): void {
        $this->seed([SocialAssistanceSeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/social-assistance');

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

    public function testSocialAssistanceAsAdminShowSuccess(): void {
        SocialAssistanceRepositoryHelper::insertSocialAssistance();
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/social-assistance/'.SocialAssistanceRepositoryHelper::SocialAssistance_ID);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'category',
                    'amount',
                    'provider',
                    'description',
                    'is_available',
                    'created_at',
                ],
            ]);

    }

    public function testSocialAssistanceAsAdminShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/social-assistance/'.SocialAssistanceRepositoryHelper::SocialAssistance_ID);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

    public function testSocialAssistanceAsAdminCreateSuccess(): void {
        Storage::fake('public');
        $data = [
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg', 480, 480),
            'name' => 'person',
            'category'=> 'staple',
            'amount' => 123.13,
            'provider' => 'PT Sampoerna',
            'description' => 'lorem lorem lorem',
            'is_available' => true
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/social-assistance', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'category',
                    'amount',
                    'provider',
                    'description',
                    'is_available',
                    'created_at',
                ],
            ]);

    }

    public function testSocialAssistanceAsAdminCreateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/social-assistance', $data);

        $response->assertStatus(302);
    }

    public function testSocialAssistanceAsAdminUpdateSuccess(): void {
        SocialAssistanceRepositoryHelper::insertSocialAssistance();
        $data = [
            'name' => 'person',
            'category'=> 'staple',
            'amount' => 123.13,
            'provider' => 'PT Sampoerna',
            'description' => 'lorem lorem lorem',
            'is_available' => true
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/social-assistance/'.SocialAssistanceRepositoryHelper::SocialAssistance_ID, $data);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'category',
                    'amount',
                    'provider',
                    'description',
                    'is_available',
                    'created_at',
                ],
            ]);

    }

    public function testSocialAssistanceAsAdminUpdateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/social-assistance/'.SocialAssistanceRepositoryHelper::SocialAssistance_ID, $data);

        $response->assertStatus(302);
    }

    public function testSocialAssistanceAsAdminDeleteSuccess(): void {
        SocialAssistanceRepositoryHelper::insertSocialAssistance();
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/social-assistance/'.SocialAssistanceRepositoryHelper::SocialAssistance_ID);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'category',
                    'amount',
                    'provider',
                    'description',
                    'is_available',
                    'created_at',
                ],
            ]);

    }

    public function testSocialAssistanceAsAdminDeleteFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/social-assistance/'.SocialAssistanceRepositoryHelper::SocialAssistance_ID);

        $response->assertStatus(500);
    }

    public function testSocialAssistanceAsHeadOfFamilyGetPaginatedSuccess(): void {
        $this->seed([SocialAssistanceSeeder::class]);
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/social-assistance/all/paginated?row_per_page=5');

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

    public function testSocialAssistanceAsHeadOfFamilyGetAllSuccess(): void {
        $this->seed([SocialAssistanceSeeder::class]);
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/social-assistance');

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

    public function testSocialAssistanceAsHeadOfFamilyShowSuccess(): void {
        SocialAssistanceRepositoryHelper::insertSocialAssistance();
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/social-assistance/'.SocialAssistanceRepositoryHelper::SocialAssistance_ID);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'category',
                    'amount',
                    'provider',
                    'description',
                    'is_available',
                    'created_at',
                ],
            ]);

    }

    public function testSocialAssistanceAsHeadOfFamilyShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/social-assistance/'.SocialAssistanceRepositoryHelper::SocialAssistance_ID);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }
}
