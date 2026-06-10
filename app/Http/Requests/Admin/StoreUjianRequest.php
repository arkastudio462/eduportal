<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUjianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'mapel_id' => ['required', 'exists:mapel,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'durasi' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:draft,akan_datang,sedang_berlangsung,selesai'],
            'kelas_ids' => ['required', 'array'],
            'kelas_ids.*' => ['exists:kelas,id'],
        ];
    }
}
