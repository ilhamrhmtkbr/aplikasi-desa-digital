<?php

namespace Tests\SeedersHelper;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Tests\RepositoriesHelper\AuthRepositoryHelper;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => AuthRepositoryHelper::ADMIN_NAME,
            'email' => AuthRepositoryHelper::ADMIN_EMAIL,
            'password' => bcrypt(AuthRepositoryHelper::PASSWORD)
        ])->assignRole('admin');
        User::create([
            'name' => AuthRepositoryHelper::HEAD_OF_FAMILY_NAME,
            'email' => AuthRepositoryHelper::HEAD_OF_FAMILY_EMAIL,
            'password' => bcrypt(AuthRepositoryHelper::PASSWORD)
        ])->assignRole('head-of-family');
    }
}
