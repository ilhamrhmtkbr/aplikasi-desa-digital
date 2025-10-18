<?php
namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\RepositoriesHelper\AuthRepositoryHelper;
use Tests\SeedersHelper\InitialAuthUserSeeder;

abstract class AuthTestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected User $adminUser, $headOfFamilyUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(InitialAuthUserSeeder::class);

        $this->adminUser = User::where('email', AuthRepositoryHelper::ADMIN_EMAIL)->first();
        $this->headOfFamilyUser = User::where('email', AuthRepositoryHelper::HEAD_OF_FAMILY_EMAIL)->first();
    }
}
