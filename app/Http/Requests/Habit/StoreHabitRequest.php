<?php

namespace App\Http\Requests\Habit;

use App\Http\Requests\ApiRequest;

class StoreHabitRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'frequency' => ['required', 'in:daily,weekly,monthly'],
            'target_days' => ['required', 'integer', 'min:1'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}

