<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDevelopmentApplicantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'development_id' => 'required|string|max:255',
            'user_id' => 'required|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'development_id' => 'Id Development',
            'user_id' => 'Id User',
        ];
    }
}
