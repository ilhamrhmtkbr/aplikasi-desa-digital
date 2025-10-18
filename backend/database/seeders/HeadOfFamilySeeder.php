<?php

namespace Database\Seeders;

use Database\Factories\FamilyMemberFactory;
use Database\Factories\HeadOfFamilyFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HeadOfFamily;
use App\Models\User;

class HeadOfFamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'person@gmail.com')->first();
        $headOfFamily = new HeadOfFamily();
        $headOfFamily->user_id = $user->id;
        $headOfFamily->profile_picture = 'atjwg';
        $headOfFamily->identify_number = 123321;
        $headOfFamily->gender = 'male';
        $headOfFamily->phone_number = '0911919';
        $headOfFamily->occupation = 'programmer';
        $headOfFamily->marital_status = 'single';
        $headOfFamily->date_of_birth = '2005-03-01';
        $headOfFamily->save();

        FamilyMemberFactory::new()->count(5)
            ->create(['head_of_family_id' => $headOfFamily->id, 'user_id' => UserFactory::new()->create()->id]);

        UserFactory::new()->count(15)->create()->each(function ($user) {
           $headOfFamily = HeadOfFamilyFactory::new()->create([
               'user_id' => $user->id
           ]);

           FamilyMemberFactory::new()->count(5)
               ->create(['head_of_family_id' => $headOfFamily->id, 'user_id' => UserFactory::new()->create()->id]);
        });
    }
}
