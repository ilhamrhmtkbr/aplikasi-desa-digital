<?php

namespace Tests\RepositoriesHelper;

use Illuminate\Support\Facades\DB;

class EventRepositoryHelper
{
    const Event_ID = 'CU5T0M1D-11aa-22bb-33cc-44dd55ee66ff';
    const Event_PRICE = 250000;

    public static function insertEvent(): void {
        DB::connection('mysql')
            ->table('events')
            ->insert([
                'id'            => self::Event_ID,
                'thumbnail'     => 'picture',
                'name'          => 'Seminar Teknologi AI 2025',
                'description'   => 'Seminar tahunan membahas perkembangan terbaru dalam teknologi kecerdasan buatan dan penerapannya di industri.',
                'price'         => self::Event_PRICE,
                'date'          => '2025-11-20',
                'time'          => '09:00:00',
                'is_active'     => true
            ]);
    }
}
