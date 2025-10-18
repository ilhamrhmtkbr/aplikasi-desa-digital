<?php

namespace Tests\RepositoriesHelper;

use App\Models\User;

class AuthRepositoryHelper
{
    const ADMIN_NAME = 'admin';
    const ADMIN_EMAIL = 'admin@gmail.com';
    const HEAD_OF_FAMILY_NAME = 'head-of-family';
    const HEAD_OF_FAMILY_EMAIL = 'headoffamily@gmail.com';
    const PASSWORD = 'P4ssword!';

    public static function getHeadOfFamilyId()
    {
        $user = User::where('email', self::HEAD_OF_FAMILY_EMAIL)->first();
        return $user->id;
    }
}
