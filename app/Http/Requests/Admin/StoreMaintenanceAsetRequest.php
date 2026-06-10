<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceAsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'barang_id' => ['nullable', 'exists:barang,id'],
            'ruang_id' => ['nullable', 'exists:ruang,id'],
            'jenis' => ['required', 'string', 'in:perbaikan,perawatan'],
            'deskripsi' => ['required', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'biaya' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:direncanakan,sedang_dikerjakan,selesai'],
            'pelaksana' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
