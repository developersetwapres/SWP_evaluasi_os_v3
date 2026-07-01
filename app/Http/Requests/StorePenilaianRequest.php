<?php

namespace App\Http\Requests;

use App\Models\Indikator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StorePenilaianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $penugasan = $this->route('penugasan');

        return $penugasan
            && $this->user()?->id === $penugasan->penilai_id
            && $penugasan->status !== 'completed';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'evaluator_id' => ['nullable', 'integer'],
            'outsourcing_id' => ['nullable', 'integer'],
            'scores' => ['required', 'array', 'min:1'],
            'scores.*.indicator_id' => ['required', 'integer', 'distinct', 'exists:indikators,id'],
            'scores.*.value' => ['required', 'integer', 'between:1,4'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $penugasan = $this->route('penugasan');

            if (! $penugasan?->outsourcings) {
                return;
            }

            $kelompokJabatanId = $penugasan->outsourcings->jabatan?->kelompok_jabatan_id;

            if (! $kelompokJabatanId) {
                return;
            }

            $expectedIndicatorIds = Indikator::where('kelompok_jabatan_id', $kelompokJabatanId)
                ->orderBy('id')
                ->pluck('id')
                ->map(fn(int $id): int => $id)
                ->all();

            if ($expectedIndicatorIds === []) {
                return;
            }

            $submittedIndicatorIds = collect($this->input('scores', []))
                ->pluck('indicator_id')
                ->map(fn(mixed $id): int => (int) $id)
                ->sort()
                ->values()
                ->all();

            $expectedIndicatorIds = collect($expectedIndicatorIds)
                ->sort()
                ->values()
                ->all();

            if ($submittedIndicatorIds !== $expectedIndicatorIds) {
                $validator->errors()->add('scores', 'Semua indikator wajib dinilai.');
            }
        });
    }
}
