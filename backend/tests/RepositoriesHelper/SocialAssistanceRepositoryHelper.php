<?php

namespace Tests\RepositoriesHelper;

use Illuminate\Support\Facades\DB;

class SocialAssistanceRepositoryHelper
{
    const SocialAssistance_ID = 'CU5T0M1D-11aa-22bb-33cc-44dd55ee66ff';

    public static function insertSocialAssistance(): void {

        DB::connection('mysql')
            ->table('social_assistances')
            ->insert([
                'id' => self::SocialAssistance_ID,
                'thumbnail' => 'thumbnail',
                'name' => 'person',
                'category'=> 'staple',
                'amount' => 123.13,
                'provider' => 'PT Sampoerna',
                'description' => 'lorem lorem lorem',
                'is_available' => true
            ]);
    }
}
