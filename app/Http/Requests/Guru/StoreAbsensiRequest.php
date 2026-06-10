<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbsensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kelas_id' => ['required', 'exists:kelas,id'],
            'tanggal' => ['required', 'date'],
            'absensi' => ['required', 'array'],
            'absensi.*.status' => ['required', 'string', 'in:hadir,sakit,izin,alpha'],
            'absensi.*.keterangan' => ['nullable', 'string'],
        ];
    }
}
