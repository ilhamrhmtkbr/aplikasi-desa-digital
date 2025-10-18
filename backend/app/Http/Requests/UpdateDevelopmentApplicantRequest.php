<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDevelopmentApplicantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'status'
        ];
    }
}
