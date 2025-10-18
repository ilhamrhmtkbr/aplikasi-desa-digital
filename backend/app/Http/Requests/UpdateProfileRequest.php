<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'thumbnail'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name'              => 'required|string|max:255',
            'about'             => 'required|string|max:2000',
            'headman'           => 'required|string|max:255',
            'people'            => 'required|integer|min:0',
            'agricultural_area' => 'required|numeric|min:0',
            'total_area'        => 'required|numeric|min:0',
            'images'            => 'nullable|array',
            'images.*'          => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ];
    }

    public function attributes(): array
    {
        return [
            'thumbnail'         => 'Thumbnail',
            'name'              => 'Nama Desa',
            'about'             => 'Tentang',
            'headman'           => 'Kepala Desa',
            'people'            => 'Jumlah Penduduk',
            'agricultural_area' => 'Luas Pertanian',
            'total_area'        => 'Luas Wilayah',
        ];
    }
}
