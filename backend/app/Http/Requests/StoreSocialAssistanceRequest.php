<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSocialAssistanceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'thumbnail'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:staple,cash,subsidized fuel,health',
            'amount'      => 'required|numeric|min:0',
            'provider'    => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'is_available'=> 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'thumbnail'   => 'Thumbnail',
            'name'        => 'Nama Bantuan',
            'category'    => 'Kategori',
            'amount'      => 'Jumlah',
            'provider'    => 'Penyedia',
            'description' => 'Deskripsi',
            'is_available'=> 'Ketersediaan',
        ];
    }
}
