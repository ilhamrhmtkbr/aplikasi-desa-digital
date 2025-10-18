<?php

namespace Tests\RepositoriesHelper;

use Illuminate\Support\Facades\DB;

class DevelopmentRepositoryHelper
{
    const Development_ID = 'CU5T0M1D-11aa-22bb-33cc-44dd55ee66ff';

    public static function insertDevelopment(): void {
        DB::connection('mysql')
            ->table('developments')
            ->insert([
                'id'                => self::Development_ID,
                'name'              => 'PT Marpaung Sejahtera',
                'thumbnail'         => 'thumbnail',
                'description'       => 'Proyek pembangunan gedung kantor cabang baru di Jakarta Selatan.',
                'person_in_charge'  => 'Tina Nasyiah',
                'start_date'        => '2025-05-10',
                'end_date'          => '2025-11-20',
                'amount'            => 750000.00,
                'status'            => 'ongoing',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
    }
}
