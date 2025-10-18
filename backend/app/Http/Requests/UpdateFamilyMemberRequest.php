<?php

namespace App\Http\Requests;

use App\Models\FamilyMember;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFamilyMemberRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.FamilyMember::find($this->route('family_member'))->user_id,
            'password' => 'required|string|min:8',
            'profile_picture'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'identify_number'   => 'required|string|max:50',
            'gender'            => 'required|in:male,female',
            'date_of_birth'     => 'required|date|before:today',
            'phone_number'      => 'required|string|max:20',
            'occupation'        => 'required|string|max:255',
            'marital_status'    => 'required|in:single,married',
            'relation'          => 'required|in:wife,child,husband'
        ];
    }

    public function attributes(): array
    {
        return [
            'name'            => 'Nama',
            'email'           => 'Email',
            'password'        => 'Kata Sandi',
            'profile_picture'   => 'Foto Profil',
            'identify_number'   => 'Nomor Identitas',
            'gender'            => 'Jenis Kelamin',
            'date_of_birth'     => 'Tanggal Lahir',
            'phone_number'      => 'Nomor Telepon',
            'occupation'        => 'Pekerjaan',
            'marital_status'    => 'Status Perkawinan',
            'relation'          => 'Hubungan'
        ];
    }
}
