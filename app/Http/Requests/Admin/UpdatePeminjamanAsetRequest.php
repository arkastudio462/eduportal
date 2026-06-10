<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeminjamanAsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'peminjam_type' => ['required', 'string', 'in:App\Models\Guru,App\Models\Siswa,App\Models\User'],
            'peminjam_id' => ['required', 'integer', 'exists:users,id'],
            'ruang_id' => ['nullable', 'exists:ruang,id'],
            'barang_id' => ['nullable', 'exists:barang,id'],
            'tujuan' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'status' => ['required', 'string', 'in:diajukan,disetujui,dipinjam,dikembalikan,ditolak'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
