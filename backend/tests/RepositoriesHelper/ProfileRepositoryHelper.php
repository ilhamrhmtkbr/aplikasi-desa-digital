<?php

namespace Tests\RepositoriesHelper;

use Illuminate\Support\Facades\DB;

class ProfileRepositoryHelper
{
    const Profile_ID = 'CU5T0M1D-11aa-22bb-33cc-44dd55ee66ff';

    public static function insertProfile(): void {
        DB::connection('mysql')
            ->table('profiles')
            ->insert([
                'id'                => self::Profile_ID,
                'thumbnail'         => 'thumbnail',
                'name'              => 'person',
                'about'             => 'lorem lorem lorem',
                'headman'           => 'urang',
                'people'            => 33,
                'agricultural_area' => 33.13,
                'total_area'        => 33.13,
            ]);
    }
}
