<?php

namespace App\Http\Requests;

use App\Models\Pilar;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePilarRequest extends FormRequest
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
        /** @var Pilar|null $pilar */
        $pilar = $this->route('pilar');

        return [
            'title' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pilars', 'title')->ignore($pilar?->getKey()),
            ],
            'bobot_skor_id' => ['required', 'integer', 'exists:bobot_skors,id'],
        ];
    }
}
