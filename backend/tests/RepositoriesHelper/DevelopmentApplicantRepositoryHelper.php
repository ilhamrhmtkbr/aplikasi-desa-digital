<?php

namespace Tests\RepositoriesHelper;

use Illuminate\Support\Facades\DB;

class DevelopmentApplicantRepositoryHelper
{
    const DevelopmentApplicant_ID = 'CU5T0M1D-11aa-22bb-33cc-44dd55ee66ff';

    public static function insertDevelopmentApplicant(): void {
        DevelopmentRepositoryHelper::insertDevelopment();

        DB::connection('mysql')
            ->table('development_applicants')
            ->insert([
                'id' => self::DevelopmentApplicant_ID,
                'development_id' => DevelopmentRepositoryHelper::Development_ID,
                'user_id'        => AuthRepositoryHelper::getHeadOfFamilyId(),
                'status'         => 'pending'
            ]);
    }
}
