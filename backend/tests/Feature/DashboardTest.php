<?php

namespace Tests\Feature;

use Tests\AuthTestCase;
use Illuminate\Support\Facades\Log;

class DashboardTest extends AuthTestCase
{
    public function testDashboardAsAdminGetPaginatedSuccess(): void {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                    ->get('api/dashboard/get-dashboard-data');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Success'
            ]);
    }
}
