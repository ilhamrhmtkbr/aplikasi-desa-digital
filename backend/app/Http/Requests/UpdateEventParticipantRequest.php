<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventParticipantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quantity'          => 'required|integer|min:1',
            'total_price'       => 'required|numeric|min:0',
            'payment_status'    => 'required|in:pending,paid,failed,cancelled',
        ];
    }

    public function attributes(): array
    {
        return [
            'quantity'          => 'Jumlah Peserta',
            'total_price'       => 'Total Harga',
            'payment_status'    => 'Status Pembayaran',
        ];
    }
}
