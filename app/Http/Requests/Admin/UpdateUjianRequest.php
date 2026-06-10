<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUjianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['sometimes', 'string', 'max:255'],
            'mapel_id' => ['sometimes', 'exists:mapel,id'],
            'tanggal_mulai' => ['sometimes', 'date'],
            'tanggal_selesai' => ['sometimes', 'date', 'after_or_equal:tanggal_mulai'],
            'durasi' => ['sometimes', 'integer', 'min:1'],
            'status' => ['sometimes', 'string', 'in:draft,akan_datang,sedang_berlangsung,selesai'],
            'kelas_ids' => ['sometimes', 'array'],
            'kelas_ids.*' => ['exists:kelas,id'],
        ];
    }
}
