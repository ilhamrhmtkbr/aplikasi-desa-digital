<?php

namespace Tests\SeedersHelper;

use Database\Factories\FamilyMemberFactory;
use Database\Factories\HeadOfFamilyFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HeadOfFamily;
use App\Models\User;
use Tests\RepositoriesHelper\AuthRepositoryHelper;

class HeadOfFamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', AuthRepositoryHelper::HEAD_OF_FAMILY_EMAIL)->first();
        $headOfFamily = new HeadOfFamily();
        $headOfFamily->user_id = $user->id;
        $headOfFamily->profile_picture = 'null';
        $headOfFamily->identify_number = 123123123;
        $headOfFamily->gender = 'male';
        $headOfFamily->phone_number = '081272369357';
        $headOfFamily->occupation = 'programmer';
        $headOfFamily->marital_status = 'single';
        $headOfFamily->date_of_birth = '2000-03-25';
        $headOfFamily->save();

        FamilyMemberFactory::new()->count(5)
            ->create(['head_of_family_id' => $headOfFamily->id, 'user_id' => UserFactory::new()->create()->id]);
    }
}
