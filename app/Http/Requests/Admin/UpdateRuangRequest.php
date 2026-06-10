<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRuangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ruang = $this->route('ruang');

        return [
            'kode' => ['required', 'string', 'max:50', Rule::unique('ruang', 'kode')->ignore($ruang->id)],
            'nama' => ['required', 'string', 'max:255'],
            'lantai' => ['nullable', 'string', 'max:50'],
            'gedung' => ['nullable', 'string', 'max:255'],
            'kapasitas' => ['nullable', 'integer', 'min:1'],
            'jenis' => ['required', 'string', 'in:kelas,lab,aula,ruang_guru,ruang_admin,perpustakaan,lapangan,lainnya'],
            'keterangan' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:tersedia,tidak_tersedia'],
        ];
    }
}
