<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mapel_id' => ['required', 'exists:mapel,id'],
            'tipe' => ['required', 'string', 'in:PG,Essay,Ganda Kompleks'],
            'kesulitan' => ['required', 'string', 'in:Mudah,Sedang,Sulit'],
            'konten' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'opsi' => ['nullable', 'json'],
            'jawaban' => ['required', 'string'],
        ];
    }
}
