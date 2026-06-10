<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $siswa = $this->route('siswa');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($siswa->user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            'nisn' => ['required', 'string', Rule::unique('siswa', 'nisn')->ignore($siswa->id)],
            'nis' => ['nullable', 'string'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'status' => ['required', 'string', 'in:aktif,nonaktif,lulus,mutasi,alumni'],
        ];
    }
}
