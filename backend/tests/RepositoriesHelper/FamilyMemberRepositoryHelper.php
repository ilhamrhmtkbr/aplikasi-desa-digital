<?php

namespace Tests\RepositoriesHelper;

use Illuminate\Support\Facades\DB;

class FamilyMemberRepositoryHelper
{
    const FamilyMember_ID = 'CU5T0M1D-11aa-22bb-33cc-44dd55ee66ff';

    public static function insertFamilyMember(): void {
        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();
        DB::connection('mysql')
            ->table('family_members')
            ->insert([
                'id'                => self::FamilyMember_ID,
                'user_id'           => HeadOfFamilyRepositoryHelper::User_ID,
                'head_of_family_id' => HeadOfFamilyRepositoryHelper::HeadOfFamily_ID,
                'profile_picture'   => 'profile_picture',
                'identify_number'   => 123123123,
                'gender'            => 'male',
                'date_of_birth'     => '2000-03-25',
                'phone_number'      => '081218723006',
                'occupation'        => 'programmer',
                'marital_status'    => 'single',
                'relation'          => 'child'
            ]);
    }
}
