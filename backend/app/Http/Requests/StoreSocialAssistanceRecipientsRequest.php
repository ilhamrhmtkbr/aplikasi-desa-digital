<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSocialAssistanceRecipientsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'social_assistance_id' => 'required|exists:social_assistances,id',
            'head_of_family_id'    => 'required|exists:head_of_families,id',
            'amount'               => 'required|numeric|min:0',
            'reason'               => 'required|string|max:1000',
            'bank'                 => 'required|in:bri,bni,bca,mandiri',
            'account_number'       => 'required|max:50',
            'proof'                => 'nullable'
        ];
    }

    public function prepareForValidation(): void
    {
        $user = Auth::user();

        if ($user->hasRole('head-of-family')) {
            $this->merge([
                'head_of_family_id' => $user->headOfFamily->id,
                'proof' => ''
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'social_assistance_id' => 'Bantuan Sosial',
            'head_of_family_id'    => 'Kepala Keluarga',
            'amount'               => 'Jumlah',
            'reason'               => 'Alasan',
            'bank'                 => 'Bank',
            'account_number'       => 'Nomor Rekening',
        ];
    }
}
