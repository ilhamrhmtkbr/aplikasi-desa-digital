<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name'        => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price'       => 'required|numeric|min:0',
            'date'        => 'required|date|after_or_equal:today',
            'time'        => 'required|date_format:H:i',
            'is_active'   => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'thumbnail'   => 'Thumbnail',
            'name'        => 'Nama Event',
            'description' => 'Deskripsi',
            'price'       => 'Harga',
            'date'        => 'Tanggal',
            'time'        => 'Waktu',
            'is_active'   => 'Status Aktif',
        ];
    }
}
