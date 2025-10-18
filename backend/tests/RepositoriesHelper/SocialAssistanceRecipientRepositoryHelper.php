<?php

namespace Tests\RepositoriesHelper;

use Illuminate\Support\Facades\DB;

class SocialAssistanceRecipientRepositoryHelper
{
    const SocialAssistanceRecipients_ID = 'CU5T0M1D-11aa-22bb-33cc-44dd55ee66ff';

    public static function insertSocialAssistance(): void {
        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();
        SocialAssistanceRepositoryHelper::insertSocialAssistance();
        DB::connection('mysql')
        ->table('social_assistance_recipients')
            ->insert([
                'id' => self::SocialAssistanceRecipients_ID,
                'social_assistance_id' => SocialAssistanceRepositoryHelper::SocialAssistance_ID,
                'head_of_family_id' => HeadOfFamilyRepositoryHelper::HeadOfFamily_ID,
                'amount' => 3123.01,
                'reason' => 'lorem lorem lorem',
                'bank' => 'bri',
                'account_number' => 123123,
                'proof' => 'picture',
                'status' => 'pending'
            ]);
    }
}
