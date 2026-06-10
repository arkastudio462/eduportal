<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:50', 'unique:barang,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255'],
            'ruang_id' => ['nullable', 'exists:ruang,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'kondisi' => ['required', 'string', 'in:baik,rusak_ringan,rusak_berat'],
            'merek' => ['nullable', 'string', 'max:255'],
            'tahun_peroleh' => ['nullable', 'integer', 'min:1900', 'max:2099'],
            'sumber_dana' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
