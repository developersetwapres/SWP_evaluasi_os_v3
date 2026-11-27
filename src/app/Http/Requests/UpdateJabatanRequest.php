<?php

namespace App\Http\Requests;

use App\Models\Jabatan;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJabatanRequest extends FormRequest
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
        /** @var Jabatan|null $jabatan */
        $jabatan = $this->route('jabatan');

        return [
            'kelompok_jabatan_id' => ['required', 'integer', 'exists:kelompok_jabatans,id'],
            'nama_jabatan' => [
                'required',
                'string',
                'max:150',
                Rule::unique('jabatans', 'nama_jabatan')->ignore($jabatan?->getKey()),
            ],
            'kode_jabatan' => [
                'required',
                'string',
                'max:150',
                Rule::unique('jabatans', 'kode_jabatan')->ignore($jabatan?->getKey()),
            ],
        ];
    }
}
