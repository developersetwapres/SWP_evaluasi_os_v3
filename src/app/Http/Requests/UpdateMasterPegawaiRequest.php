<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMasterPegawaiRequest extends FormRequest
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
            'jabatan' => ['nullable', 'string', 'max:250'],
            'unit_kerja' => ['nullable', 'string', 'max:250', 'exists:biros,kode_biro'],
            'image' => [
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

            'unit_kerja.max' => 'Kode unit kerja terlalu panjang.',
            'unit_kerja.exists' => 'Unit kerja tidak valid.',

            'image.max' => 'Path gambar terlalu panjang.',
        ];
    }
}
