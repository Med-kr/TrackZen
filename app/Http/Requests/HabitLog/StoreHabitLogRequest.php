<?php

namespace App\Http\Requests\HabitLog;

use App\Http\Requests\ApiRequest;

class StoreHabitLogRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'completed_at' => ['nullable', 'date', 'date_format:Y-m-d', 'before_or_equal:today'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }
}

