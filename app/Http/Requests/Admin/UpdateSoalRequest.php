<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mapel_id' => ['sometimes', 'exists:mapel,id'],
            'tipe' => ['sometimes', 'string', 'in:PG,Essay,Ganda Kompleks'],
            'kesulitan' => ['sometimes', 'string', 'in:Mudah,Sedang,Sulit'],
            'konten' => ['sometimes', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'opsi' => ['nullable', 'json'],
            'jawaban' => ['sometimes', 'string'],
        ];
    }
}
