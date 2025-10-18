<?php

namespace Tests\Feature;

use Tests\AuthTestCase;
use Illuminate\Support\Facades\Log;
use Tests\RepositoriesHelper\HeadOfFamilyRepositoryHelper;
use Tests\RepositoriesHelper\SocialAssistanceRecipientRepositoryHelper;
use Tests\RepositoriesHelper\SocialAssistanceRepositoryHelper;
use Tests\SeedersHelper\HeadOfFamilySeeder;
use Tests\SeedersHelper\SocialAssistanceRecipientsSeeder;
use Tests\SeedersHelper\SocialAssistanceSeeder;

class SocialAssistanceRecipientTest extends AuthTestCase
{
    public function testSocialAssistanceRecipientAsAdminGetPaginatedSuccess(): void {
        $this->seed([HeadOfFamilySeeder::class, SocialAssistanceSeeder::class, SocialAssistanceRecipientsSeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/social-assistance-recipient/all/paginated?row_per_page=5');

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

    public function testSocialAssistanceRecipientAsAdminGetAllSuccess(): void {
        $this->seed([HeadOfFamilySeeder::class, SocialAssistanceSeeder::class, SocialAssistanceRecipientsSeeder::class]);

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/social-assistance-recipient');

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

    public function testSocialAssistanceRecipientAsAdminShowSuccess(): void {
        SocialAssistanceRecipientRepositoryHelper::insertSocialAssistance();
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/social-assistance-recipient/'.SocialAssistanceRecipientRepositoryHelper::SocialAssistanceRecipients_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'social_assistance' => [
                        'id',
                        'thumbnail',
                        'name',
                        'category',
                        'amount',
                        'provider',
                        'description',
                        'social_assistance_recipients' => [
                            [
                                'id',
                                'head_of_family' => [
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
                                'amount',
                                'reason',
                                'bank',
                                'account_number',
                                'proof',
                                'status',
                                'created_at',
                            ],
                        ],
                        'is_available',
                        'created_at',
                    ],
                    'head_of_family' => [
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
                    'amount',
                    'reason',
                    'bank',
                    'account_number',
                    'proof',
                    'status',
                    'created_at',
                ],
            ]);
    }

    public function testSocialAssistanceRecipientAsAdminShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/social-assistance-recipient/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

    public function testSocialAssistanceRecipientAsAdminCreateSuccess(): void {
        SocialAssistanceRepositoryHelper::insertSocialAssistance();
        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();
        $data = [
            'social_assistance_id' => SocialAssistanceRepositoryHelper::SocialAssistance_ID,
            'head_of_family_id' => HeadOfFamilyRepositoryHelper::HeadOfFamily_ID,
            'amount' => 123123,
            'reason' => 'reason',
            'bank' => 'bri',
            'proof' => '',
            'account_number' => 123123,
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/social-assistance-recipient', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'amount',
                    'reason',
                    'bank',
                    'account_number',
                    'proof',
                    'status',
                ],
            ]);
    }

    public function testSocialAssistanceRecipientAsAdminCreateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/social-assistance-recipient', $data);

        $response->assertStatus(302);
    }

    public function testSocialAssistanceRecipientAsAdminUpdateSuccess(): void {
        SocialAssistanceRecipientRepositoryHelper::insertSocialAssistance();
        $data = [
            'amount' => 123123,
            'reason' => 'reason',
            'bank' => 'bri',
            'account_number' => '123123',
            'proof' => '',
            'status' => 'approved'
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/social-assistance-recipient/'.SocialAssistanceRecipientRepositoryHelper::SocialAssistanceRecipients_ID, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'amount',
                    'reason',
                    'bank',
                    'account_number',
                    'proof',
                    'status',
                ],
            ]);
    }

    public function testSocialAssistanceRecipientAsAdminUpdateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/social-assistance-recipient/'.SocialAssistanceRecipientRepositoryHelper::SocialAssistanceRecipients_ID, $data);

        $response->assertStatus(302);
    }

    public function testSocialAssistanceRecipientAsAdminDeleteSuccess(): void {
        SocialAssistanceRecipientRepositoryHelper::insertSocialAssistance();
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/social-assistance-recipient/'.SocialAssistanceRecipientRepositoryHelper::SocialAssistanceRecipients_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'amount',
                    'reason',
                    'bank',
                    'account_number',
                    'proof',
                    'status',
                ],
            ]);
    }

    public function testSocialAssistanceRecipientAsAdminDeleteFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/social-assistance-recipient/'.SocialAssistanceRecipientRepositoryHelper::SocialAssistanceRecipients_ID);

        $response->assertStatus(500);
    }

    public function testSocialAssistanceRecipientAsHeadOfFamilyGetPaginatedSuccess(): void {
        $this->seed([HeadOfFamilySeeder::class, SocialAssistanceSeeder::class, SocialAssistanceRecipientsSeeder::class]);
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/social-assistance-recipient/all/paginated?row_per_page=5');

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

    public function testSocialAssistanceRecipientAsHeadOfFamilyGetAllSuccess(): void {
        $this->seed([HeadOfFamilySeeder::class, SocialAssistanceSeeder::class, SocialAssistanceRecipientsSeeder::class]);

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/social-assistance-recipient');

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

    public function testSocialAssistanceRecipientAsHeadOfFamilyShowSuccess(): void {
        SocialAssistanceRecipientRepositoryHelper::insertSocialAssistance();
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->get('api/social-assistance-recipient/'.SocialAssistanceRecipientRepositoryHelper::SocialAssistanceRecipients_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'social_assistance' => [
                        'id',
                        'thumbnail',
                        'name',
                        'category',
                        'amount',
                        'provider',
                        'description',
                        'social_assistance_recipients' => [
                            [
                                'id',
                                'head_of_family' => [
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
                                'amount',
                                'reason',
                                'bank',
                                'account_number',
                                'proof',
                                'status',
                                'created_at',
                            ],
                        ],
                        'is_available',
                        'created_at',
                    ],
                    'head_of_family' => [
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
                    'amount',
                    'reason',
                    'bank',
                    'account_number',
                    'proof',
                    'status',
                    'created_at',
                ],
            ]);
    }

    public function testSocialAssistanceRecipientAsHeadOfFamilyShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/social-assistance-recipient/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

    public function testSocialAssistanceRecipientAsHeadOfFamilyCreateSuccess(): void {
        SocialAssistanceRepositoryHelper::insertSocialAssistance();
        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();
        $data = [
            'social_assistance_id' => SocialAssistanceRepositoryHelper::SocialAssistance_ID,
            'head_of_family_id' => HeadOfFamilyRepositoryHelper::HeadOfFamily_ID,
            'amount' => 123123,
            'reason' => 'reason',
            'bank' => 'bri',
            'account_number' => '123123',
        ];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->post('api/social-assistance-recipient', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'amount',
                    'reason',
                    'bank',
                    'account_number',
                    'proof',
                    'status',
                    'created_at',
                ],
            ]);
    }

    public function testSocialAssistanceRecipientAsHeadOfFamilyCreateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->post('api/social-assistance-recipient', $data);

        $response->assertStatus(302);
    }

    public function testSocialAssistanceRecipientAsHeadOfFamilyUpdateSuccess(): void {
        SocialAssistanceRecipientRepositoryHelper::insertSocialAssistance();
        $data = [
            'amount' => 123123,
            'reason' => 'reason',
            'bank' => 'bri',
            'account_number' => '123123',
            'status' => 'approved'
        ];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->patch('api/social-assistance-recipient/'.SocialAssistanceRecipientRepositoryHelper::SocialAssistanceRecipients_ID, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'amount',
                    'reason',
                    'bank',
                    'account_number',
                    'proof',
                    'status',
                    'created_at',
                ],
            ]);
    }

    public function testSocialAssistanceRecipientAsHeadOfFamilyUpdateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->patch('api/social-assistance-recipient/'.SocialAssistanceRecipientRepositoryHelper::SocialAssistanceRecipients_ID, $data);

        $response->assertStatus(302);
    }

    public function testSocialAssistanceRecipientAsHeadOfFamilyDeleteSuccess(): void {
        SocialAssistanceRecipientRepositoryHelper::insertSocialAssistance();
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->delete('api/social-assistance-recipient/'.SocialAssistanceRecipientRepositoryHelper::SocialAssistanceRecipients_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'amount',
                    'reason',
                    'bank',
                    'account_number',
                    'proof',
                    'status',
                ],
            ]);
    }

    public function testSocialAssistanceRecipientAsHeadOfFamilyDeleteFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->delete('api/social-assistance-recipient/'.SocialAssistanceRecipientRepositoryHelper::SocialAssistanceRecipients_ID);

        $response->assertStatus(500);
    }
}
