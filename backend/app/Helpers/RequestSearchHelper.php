<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestSearchHelper
{
    public static function isValid(Request $request): void
    {
        $valid = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'row_per_page' => 'required'
        ]);

        if ($valid->fails()) {
            abort(422, $valid->errors());
        }
    }
}
