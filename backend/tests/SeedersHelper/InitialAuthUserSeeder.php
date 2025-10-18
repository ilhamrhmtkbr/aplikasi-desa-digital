<?php

namespace Tests\SeedersHelper;

use Illuminate\Database\Seeder;

class InitialAuthUserSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            HeadOfFamilySeeder::class,
        ]);
    }
}
