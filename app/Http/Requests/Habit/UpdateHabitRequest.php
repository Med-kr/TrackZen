<?php

namespace App\Http\Requests\Habit;

use App\Http\Requests\ApiRequest;

class UpdateHabitRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'frequency' => ['sometimes', 'required', 'in:daily,weekly,monthly'],
            'target_days' => ['sometimes', 'required', 'integer', 'min:1'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active' => ['sometimes', 'required', 'boolean'],
        ];
    }
}

