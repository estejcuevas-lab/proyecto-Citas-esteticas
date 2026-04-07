<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBusinessHourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isBusiness() || $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'day_of_week' => [
                'required',
                'integer',
                'between:0,6',
                Rule::unique('business_hours')->where('business_id', $this->route('business')->id),
            ],
            'opens_at' => ['required', 'date_format:H:i'],
            'closes_at' => ['required', 'date_format:H:i', 'after:opens_at'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
