<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOutsourcingRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:250'],
            'nip' => ['required', 'string', 'max:250', 'unique:users,nip'],
            'jabatan' => ['nullable', 'exists:jabatans,id'],
            'unit_kerja' => ['nullable', 'string', 'max:191'],
            'status' => ['required', 'boolean'],
            'image' => [
                // 'required',
                'nullable',
                'string', // karena diproses moveImageFromTemp
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama outsourcing wajib diisi.',
            'name.max' => 'Nama maksimal 250 karakter.',

            'nip.required' => 'NIP wajib diisi.',
            'nip.max' => 'NIP maksimal 250 karakter',
            'nip.unique' => 'NIP sudah digunakan.',

            'jabatan.exists' => 'Jabatan tidak valid.',

            'unit_kerja.max' => 'Kode unit kerja terlalu panjang.',

            'status.required' => 'Status wajib diisi.',
            'status.boolean' => 'Status tidak valid.',

            'image.max' => 'Path gambar terlalu panjang.',
            // 'image.required' => 'Gambar wajib diunggah.',
        ];
    }
}
