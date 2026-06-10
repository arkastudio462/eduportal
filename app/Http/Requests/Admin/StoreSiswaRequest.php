<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'nisn' => ['required', 'string', 'unique:siswa,nisn'],
            'nis' => ['nullable', 'string'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'status' => ['required', 'string', 'in:aktif,nonaktif,lulus,mutasi,alumni'],
        ];
    }
}
