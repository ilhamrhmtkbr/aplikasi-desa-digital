<?php

namespace Tests\RepositoriesHelper;

use Illuminate\Support\Facades\DB;

class EventParticipantRepositoryHelper
{
    const EventParticipant_ID = 'CU5T0M1D-11aa-22bb-33cc-44dd55ee66ff';

    public static function insertEventParticipant(): void {
        EventRepositoryHelper::insertEvent();
        HeadOfFamilyRepositoryHelper::insertHeadOfFamily();

        DB::connection('mysql')
            ->table('event_participants')
            ->insert([
                'id'                => self::EventParticipant_ID,
                'event_id'          => EventRepositoryHelper::Event_ID,
                'head_of_family_id' => HeadOfFamilyRepositoryHelper::HeadOfFamily_ID,
                'quantity'          => 2,
                'total_price'       => EventRepositoryHelper::Event_PRICE * 2,
                'payment_status'    => 'pending',
                'snap_token'        => 'null'
            ]);
    }
}
