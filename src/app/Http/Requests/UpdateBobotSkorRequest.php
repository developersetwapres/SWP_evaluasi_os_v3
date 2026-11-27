<?php

namespace App\Http\Requests;

use App\Models\BobotSkor;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBobotSkorRequest extends FormRequest
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
        /** @var BobotSkor|null $bobotSkor */
        $bobotSkor = $this->route('bobotSkor');

        return [
            'siklus_id' => ['nullable', 'integer', 'exists:sikluses,id'],
            'title' => ['required', 'string', 'max:255'],
            'kode_bobot' => [
                'required',
                'string',
                'max:255',
                Rule::unique('bobot_skors', 'kode_bobot')->ignore($bobotSkor?->getKey()),
            ],
            'bobot' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode_bobot.unique' => 'Kode bobot sudah digunakan.',
            'bobot.max' => 'Bobot maksimal 100.',
        ];
    }
}
