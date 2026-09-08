<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOutsourcingRequest extends FormRequest
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
        $outsourcing = $this->route('outsourcing')->user;
        // sesuaikan dengan nama parameter route

        return [
            'name' => ['required', 'string', 'max:250'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($outsourcing->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'jabatan' => ['nullable', 'exists:jabatans,id'],
            'unit_kerja' => ['nullable', 'string', 'max:191', 'exists:biros,kode_biro'],
            'status' => ['required', 'boolean'],
            'image' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama outsourcing wajib diisi.',
            'name.max' => 'Nama maksimal 250 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'password.min' => 'Password minimal 8 karakter.',

            'jabatan.exists' => 'Jabatan tidak valid.',

            'unit_kerja.max' => 'Kode unit kerja terlalu panjang.',

            'status.required' => 'Status wajib diisi.',
            'status.boolean' => 'Status tidak valid.',

            'image.max' => 'Path gambar terlalu panjang.',
        ];
    }
}
