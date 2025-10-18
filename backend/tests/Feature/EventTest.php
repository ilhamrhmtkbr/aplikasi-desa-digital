<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\AuthTestCase;
use Illuminate\Support\Facades\Log;
use Tests\RepositoriesHelper\EventRepositoryHelper;
use Tests\SeedersHelper\EventSeeder;

class EventTest extends AuthTestCase
{
    public function testEventAsAdminGetPaginatedSuccess(): void {
        $this->seed([EventSeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/event/all/paginated?row_per_page=5');

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

    public function testEventAsAdminGetAllSuccess(): void {
        $this->seed([EventSeeder::class]);
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/event');

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

    public function testEventAsAdminShowSuccess(): void {
        EventRepositoryHelper::insertEvent();
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/event/'.EventRepositoryHelper::Event_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'description',
                    'price',
                    'date',
                    'time',
                    'is_active',
                    'event_participants',
                ],
            ]);
    }

    public function testEventAsAdminShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/event/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }

    public function testEventAsAdminCreateSuccess(): void {
        Storage::fake('public');

        $data = [
            'thumbnail'     => UploadedFile::fake()->image('thumbnail.jpg', 640, 480),
            'name'          => 'Seminar Teknologi AI 2025',
            'description'   => 'Seminar tahunan membahas perkembangan terbaru dalam teknologi kecerdasan buatan dan penerapannya di industri.',
            'price'         => EventRepositoryHelper::Event_PRICE,
            'date'          => '2025-11-20',
            'time'          => '09:00',
            'is_active'     => true
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/event', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'description',
                    'price',
                    'date',
                    'time',
                    'is_active',
                ],
            ]);
    }

    public function testEventAsAdminCreateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->post('api/event', $data);

        $response->assertStatus(302);
    }

    public function testEventAsAdminUpdateSuccess(): void {
        EventRepositoryHelper::insertEvent();
        $data = [
            'name'          => 'Seminar Teknologi AI 2025',
            'description'   => 'Seminar tahunan membahas perkembangan terbaru dalam teknologi kecerdasan buatan dan penerapannya di industri.',
            'price'         => EventRepositoryHelper::Event_PRICE,
            'date'          => '2025-11-20',
            'time'          => '09:00',
            'is_active'     => true
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/event/'.EventRepositoryHelper::Event_ID, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'description',
                    'price',
                    'date',
                    'time',
                    'is_active',
                ],
            ]);
    }

    public function testEventAsAdminUpdateFailedValidation(): void {
        $data = [];

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->patch('api/event/id', $data);

        $response->assertStatus(302);
    }

    public function testEventAsAdminDeleteSuccess(): void {
        EventRepositoryHelper::insertEvent();

        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/event/'.EventRepositoryHelper::Event_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'description',
                    'price',
                    'date',
                    'time',
                    'is_active',
                ],
            ]);
    }

    public function testEventAsAdminDeleteFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->delete('api/event/id');

        $response->assertStatus(500);
    }

    public function testEventAsHeadOfFamilyGetPaginatedSuccess(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/event/all/paginated?row_per_page=5');

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

    public function testEventAsHeadOfFamilyGetAllSuccess(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/event');

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

    public function testEventAsHeadOfFamilyShowSuccess(): void {
        EventRepositoryHelper::insertEvent();
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/event/'.EventRepositoryHelper::Event_ID);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'thumbnail',
                    'name',
                    'description',
                    'price',
                    'date',
                    'time',
                    'is_active',
                    'event_participants',
                ],
            ]);
    }

    public function testEventAsHeadOfFamilyShowFailedBecauseDataNotFound(): void {
        $response = $this->actingAs($this->headOfFamilyUser, 'sanctum')
                    ->get('api/event/id');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kosong'
            ]);
    }
}
