<?php

namespace App\Http\Requests;

use App\Models\KelompokJabatan;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKelompokJabatanRequest extends FormRequest
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
        /** @var KelompokJabatan|null $kelompokJabatan */
        $kelompokJabatan = $this->route('kelompokJabatan');

        return [
            'nama_kelompok' => [
                'required',
                'string',
                'max:150',
                Rule::unique('kelompok_jabatans', 'nama_kelompok')->ignore($kelompokJabatan?->getKey()),
            ],
        ];
    }
}
