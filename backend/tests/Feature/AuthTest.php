<?php

namespace Tests\Feature;

use Tests\RepositoriesHelper\AuthRepositoryHelper;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function testLoginSuccessAsAdmin(): void
    {
        $credentials = [
            'email' => AuthRepositoryHelper::ADMIN_EMAIL,
            'password' => AuthRepositoryHelper::PASSWORD
        ];

        $res = $this->post('api/login', $credentials);

        $res->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login Success'
            ]);
    }

    public function testLoginFailedAsAdminBecauseWrongCredentials(): void
    {
        $credentials = [
            'email' => AuthRepositoryHelper::ADMIN_EMAIL,
            'password' => 'salah'
        ];

        $res = $this->post('api/login', $credentials);

        $res->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
    }

    public function testLoginFailedAsAdminBecauseValidation(): void
    {
        $res = $this->post('api/login');

        $res->assertStatus(302);
    }

    public function testLoginSuccessAsHeadOfFamily(): void
    {
        $credentials = [
            'email' => AuthRepositoryHelper::HEAD_OF_FAMILY_EMAIL,
            'password' => AuthRepositoryHelper::PASSWORD
        ];

        $res = $this->post('api/login', $credentials);

        $res->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login Success'
            ]);
    }

    public function testLoginFailedAsHeadOfFamilyBecauseWrongCredentials(): void
    {
        $credentials = [
            'email' => AuthRepositoryHelper::HEAD_OF_FAMILY_EMAIL,
            'password' => 'salah'
        ];

        $res = $this->post('api/login', $credentials);

        $res->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
    }

    public function testLoginFailedAsHeadOfFamilyBecauseValidation(): void
    {
        $res = $this->post('api/login');

        $res->assertStatus(302);
    }

    public function testGetDataMeSuccessAsAdmin()
    {
        $credentials = [
            'email' => AuthRepositoryHelper::ADMIN_EMAIL,
            'password' => AuthRepositoryHelper::PASSWORD
        ];

        $resLogin = $this->post('api/login', $credentials);

        $token = 'Bearer ' . $resLogin->json('token');

        $res = $this->withHeaders(
            ['Authorization' => $token]
        )->get('api/me');

        $res->assertStatus(200)
            ->assertJsonPath('data.role', 'admin');
    }

    public function testGetDataMeSuccessAsHeadOfFamily()
    {
        $credentials = [
            'email' => AuthRepositoryHelper::HEAD_OF_FAMILY_EMAIL,
            'password' => AuthRepositoryHelper::PASSWORD
        ];

        $resLogin = $this->post('api/login', $credentials);

        $token = 'Bearer ' . $resLogin->json('token');

        $res = $this->withHeaders(
            ['Authorization' => $token]
        )->get('api/me');

        $res->assertStatus(200)
            ->assertJsonPath('data.role', 'head-of-family');
    }
}
