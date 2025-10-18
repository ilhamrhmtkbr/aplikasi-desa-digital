<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialAssistanceRecipientsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount'               => 'required|numeric|min:0',
            'reason'               => 'required|string|max:1000',
            'bank'                 => 'required|in:bri,bni,bca,mandiri',
            'account_number'       => 'required|string|max:50',
            'proof'                => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'status'               => 'required|in:pending,approved,rejected',
        ];
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
            'proof'                => 'Bukti',
            'status'               => 'Status',
        ];
    }
}
