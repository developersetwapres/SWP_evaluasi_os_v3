<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePenugasanRequest extends FormRequest
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
            'atasan' => ['required', 'uuid', 'exists:master_pegawais,uuid'],
            'penerima_layanan1' => ['required', 'uuid', 'exists:master_pegawais,uuid'],
            'penerima_layanan2' => ['required', 'uuid', 'exists:master_pegawais,uuid'],
        ];
    }

    public function messages(): array
    {
        return [
            'atasan.required' => 'Penilai atasan wajib dipilih.',
            'atasan.uuid' => 'Format penilai atasan tidak valid.',
            'atasan.exists' => 'Penilai atasan tidak ditemukan.',

            'penerima_layanan1.required' => 'Penilai penerima layanan wajib dipilih.',
            'penerima_layanan1.uuid' => 'Format penilai penerima layanan tidak valid.',
            'penerima_layanan1.exists' => 'Penilai penerima layanan tidak ditemukan.',

            'penerima_layanan2.required' => 'Penilai penerima layanan wajib dipilih.',
            'penerima_layanan2.uuid' => 'Format penilai penerima layanan tidak valid.',
            'penerima_layanan2.exists' => 'Penilai penerima layanan tidak ditemukan.',

        ];
    }
}
