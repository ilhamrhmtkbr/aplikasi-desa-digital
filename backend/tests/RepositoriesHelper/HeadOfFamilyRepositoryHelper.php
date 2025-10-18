<?php

namespace Tests\RepositoriesHelper;

use Illuminate\Support\Facades\DB;

class HeadOfFamilyRepositoryHelper
{
    const User_ID = 'CU5T0M1D-11aa-22bb-33cc-44dd55ee66ff';
    const HeadOfFamily_ID = 'CU5T0M1D-11aa-22bb-33cc-44dd55ee66ff';

    public static function insertUser(): void {
        DB::connection('mysql')
            ->table('users')
            ->insert([
                'id' => self::User_ID,
                'name' => 'person',
                'email' => 'person@gmail.com',
                'password' => bcrypt('password'),
            ]);
    }

    public static function insertHeadOfFamily(): void {
        self::insertUser();
        DB::connection('mysql')
            ->table('head_of_families')
            ->insert([
                'id'                => self::HeadOfFamily_ID,
                'user_id'           => self::User_ID,
                'profile_picture'   => 'atjwg',
                'identify_number'   => 123321,
                'gender'            => 'male',
                'phone_number'      => '0911919',
                'occupation'        => 'programmer',
                'marital_status'    => 'single',
                'date_of_birth'     => '2005-03-01',
            ]);
    }
}
