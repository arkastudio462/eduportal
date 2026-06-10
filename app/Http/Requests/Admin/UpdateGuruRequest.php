<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $guru = $this->route('guru');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($guru->user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            'nuptk' => ['required', 'string', Rule::unique('guru', 'nuptk')->ignore($guru->id)],
            'nip' => ['nullable', 'string'],
            'mata_pelajaran' => ['required', 'string', 'max:255'],
        ];
    }
}
