<?php

namespace Tests\Feature;

use Database\Seeders\EventSeeder;
use Tests\AuthTestCase;
use Illuminate\Support\Facades\Log;
use Tests\RepositoriesHelper\EventParticipantRepositoryHelper;
use Tests\RepositoriesHelper\EventRepositoryHelper;
use Tests\RepositoriesHelper\HeadOfFamilyRepositoryHelper;
use Tests\SeedersHelper\EventParticipantSeeder;
use Tests\SeedersHelper\HeadOfFamilySeeder;

class EventParticipantTest extends AuthTestCase
{
    public function testEventParticipantAsAdminGetPaginatedSuccess(): void
    {
        $this->seed([HeadOfFamilySeeder::class, EventSeeder::class, EventParticipantSeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get('api/event-participant/all/paginated?row_per_page=5');

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

    public function testEventParticipantAsAdminGetAllSuccess(): void
    {
        $this->seed([HeadOfFamilySeeder::class, EventSeeder::class, EventParticipantSeeder::class]);

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get('api/event-participant');

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

    public function testEventParticipantAsAdminShowSuccess(): void
    {
        EventParticipantRepositoryHelper::insertEventParticipant();
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get('api/event-participant/' . EventParticipantRepositoryHelper::EventParticipant_ID);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'event' => [
                        'id',
                        'thumbnail',
                        'name',
                        'description',
                        'price',
                        'date',
                        'time',
                        'is_active',
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
                    'quantity',
                    'total_price',
                    'payment_status',
                    'created_at',
                    'snap_token',
                ],
            ]);
    }

    public function testEventParticipantAsAdminShowFailedBecauseDataNotFound(): void
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->get('api/event-participant/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

//    public function testEventParticipantAsAdminCreateSuccess(): void
//    {
//        EventRepositoryHelper::insertEvent();
//        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();
//        $data = [
//            'event_id'          => EventRepositoryHelper::Event_ID,
//            'head_of_family_id' => HeadOfFamilyRepositoryHelper::HeadOfFamily_ID,
//            'quantity'          => 2,
//            'total_price'       => EventRepositoryHelper::Event_PRICE * 2,
//            'payment_status'    => 'pending',
//            'snap_token'        => 'null'
//        ];
//
//        $response = $this->actingAs($this->adminUser, 'sanctum')
//            ->post('api/event-participant', $data);
//
//        $response->assertStatus(201)
//            ->assertJsonStructure([
//                'success',
//                'message',
//                'data' => [
//                    'id',
//                    'event' => [
//                        'id',
//                        'thumbnail',
//                        'name',
//                        'description',
//                        'price',
//                        'date',
//                        'time',
//                        'is_active',
//                    ],
//                    'head_of_family' => [
//                        'id',
//                        'user' => [
//                            'id',
//                            'name',
//                            'email',
//                        ],
//                        'profile_picture',
//                        'identify_number',
//                        'gender',
//                        'date_of_birth',
//                        'phone_number',
//                        'occupation',
//                        'marital_status',
//                        'family_members',
//                    ],
//                    'quantity',
//                    'total_price',
//                    'payment_status',
//                    'created_at',
//                    'snap_token',
//                ],
//            ]);
//    }

    public function testEventParticipantAsAdminCreateFailedValidation(): void
    {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->post('api/event-participant', $data);

        $response->assertStatus(302);
    }

    public function testEventParticipantAsAdminUpdateSuccess(): void
    {
        EventParticipantRepositoryHelper::insertEventParticipant();
        $data = [
            'quantity'       => 1,
            'total_price'    => 25000,
            'payment_status' => 'pending'
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->patch('api/event-participant/'.EventParticipantRepositoryHelper::EventParticipant_ID, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'event' => [
                        'id',
                        'thumbnail',
                        'name',
                        'description',
                        'price',
                        'date',
                        'time',
                        'is_active',
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
                    'quantity',
                    'total_price',
                    'payment_status',
                    'created_at',
                    'snap_token',
                ],
            ]);
    }

    public function testEventParticipantAsAdminUpdateFailedValidation(): void
    {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->patch('api/event-participant/id', $data);

        $response->assertStatus(302);
    }

    public function testEventParticipantAsAdminDeleteSuccess(): void
    {
        EventParticipantRepositoryHelper::insertEventParticipant();

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->delete('api/event-participant/'.EventParticipantRepositoryHelper::EventParticipant_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'event' => [
                        'id',
                        'thumbnail',
                        'name',
                        'description',
                        'price',
                        'date',
                        'time',
                        'is_active',
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
                    'quantity',
                    'total_price',
                    'payment_status',
                    'created_at',
                    'snap_token',
                ],
            ]);
    }

    public function testEventParticipantAsAdminDeleteFailedBecauseDataNotFound(): void
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->delete('api/event-participant/id');

        $response->assertStatus(500);
    }

    public function testEventParticipantAsHeadOfFamilyGetPaginatedSuccess(): void
    {
        $this->seed([HeadOfFamilySeeder::class, EventSeeder::class, EventParticipantSeeder::class]);

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->get('api/event-participant/all/paginated?row_per_page=5');

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

    public function testEventParticipantAsHeadOfFamilyGetAllSuccess(): void
    {
        $this->seed([HeadOfFamilySeeder::class, EventSeeder::class, EventParticipantSeeder::class]);

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->get('api/event-participant');

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

    public function testEventParticipantAsHeadOfFamilyShowSuccess(): void
    {
        EventParticipantRepositoryHelper::insertEventParticipant();

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->get('api/event-participant/'.EventParticipantRepositoryHelper::EventParticipant_ID);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'event' => [
                        'id',
                        'thumbnail',
                        'name',
                        'description',
                        'price',
                        'date',
                        'time',
                        'is_active',
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
                    'quantity',
                    'total_price',
                    'payment_status',
                    'created_at',
                    'snap_token',
                ],
            ]);
    }

    public function testEventParticipantAsHeadOfFamilyShowFailedBecauseDataNotFound(): void
    {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->get('api/event-participant/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

//    public function testEventParticipantAsHeadOfFamilyCreateSuccess(): void
//    {
//        EventRepositoryHelper::insertEvent();
//        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();
//        $data = [
//            'event_id'          => EventRepositoryHelper::Event_ID,
//            'head_of_family_id' => HeadOfFamilyRepositoryHelper::HeadOfFamily_ID,
//            'quantity'          => 2,
//            'total_price'       => EventRepositoryHelper::Event_PRICE * 2,
//            'payment_status'    => 'pending',
//            'snap_token'        => 'null'
//        ];
//
//        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
//            ->post('api/event-participant', $data);
//
//        $response->assertStatus(201)
//            ->assertJsonStructure([
//                'success',
//                'message',
//                'data' => [
//                    'id',
//                    'event' => [
//                        'id',
//                        'thumbnail',
//                        'name',
//                        'description',
//                        'price',
//                        'date',
//                        'time',
//                        'is_active',
//                    ],
//                    'head_of_family' => [
//                        'id',
//                        'user' => [
//                            'id',
//                            'name',
//                            'email',
//                        ],
//                        'profile_picture',
//                        'identify_number',
//                        'gender',
//                        'date_of_birth',
//                        'phone_number',
//                        'occupation',
//                        'marital_status',
//                        'family_members',
//                    ],
//                    'quantity',
//                    'total_price',
//                    'payment_status',
//                    'created_at',
//                    'snap_token',
//                ],
//            ]);
//    }

    public function testEventParticipantAsHeadOfFamilyCreateFailedValidation(): void
    {
        $data = [];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->post('api/event-participant', $data);

        $response->assertStatus(302);
    }

    public function testEventParticipantAsHeadOfFamilyUpdateSuccess(): void
    {
        EventParticipantRepositoryHelper::insertEventParticipant();
        $data = [
            'quantity'       => 1,
            'total_price'    => 25000,
            'payment_status' => 'pending'
        ];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->patch('api/event-participant/'.EventParticipantRepositoryHelper::EventParticipant_ID, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'event' => [
                        'id',
                        'thumbnail',
                        'name',
                        'description',
                        'price',
                        'date',
                        'time',
                        'is_active',
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
                    'quantity',
                    'total_price',
                    'payment_status',
                    'created_at',
                    'snap_token',
                ],
            ]);
    }

    public function testEventParticipantAsHeadOfFamilyUpdateFailedValidation(): void
    {
        $data = [];

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->patch('api/event-participant/id', $data);

        $response->assertStatus(302);
    }

    public function testEventParticipantAsHeadOfFamilyDeleteSuccess(): void
    {
        EventParticipantRepositoryHelper::insertEventParticipant();

        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->delete('api/event-participant/'.EventParticipantRepositoryHelper::EventParticipant_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'event' => [
                        'id',
                        'thumbnail',
                        'name',
                        'description',
                        'price',
                        'date',
                        'time',
                        'is_active',
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
                    'quantity',
                    'total_price',
                    'payment_status',
                    'created_at',
                    'snap_token',
                ],
            ]);
    }

    public function testEventParticipantAsHeadOfFamilyDeleteFailedBecauseDataNotFound(): void
    {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
            ->delete('api/event-participant/id');

        $response->assertStatus(500);
    }
}
