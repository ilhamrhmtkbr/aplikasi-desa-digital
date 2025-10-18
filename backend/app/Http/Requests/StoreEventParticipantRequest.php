<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEventParticipantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'event_id'          => 'required|exists:events,id',
            'head_of_family_id' => 'required|exists:head_of_families,id',
            'quantity'          => 'required|integer|min:1',
            'total_price'       => 'required'
        ];
    }

    public function prepareForValidation(): void
    {
        $user = Auth::user();

        if ($user->hasRole('head-of-family')) {
            $this->merge([
                'head_of_family_id' => $user->headOfFamily->id,
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'event_id'          => 'Event',
            'head_of_family_id' => 'Kepala Keluarga',
            'quantity'          => 'Jumlah Peserta',
            'total_price'       => 'Total Harga',
        ];
    }
}
