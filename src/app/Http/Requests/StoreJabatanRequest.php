<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreJabatanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kelompok_jabatan_id' => ['required', 'integer', 'exists:kelompok_jabatans,id'],
            'nama_jabatan' => ['required', 'string', 'max:150', 'unique:jabatans,nama_jabatan'],
            'kode_jabatan' => ['required', 'string', 'max:150', 'unique:jabatans,kode_jabatan'],
        ];
    }
}
