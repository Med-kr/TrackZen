<?php

namespace App\Http\Requests\Habit;

use App\Http\Requests\ApiRequest;

class ListHabitsRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'active' => ['nullable', 'in:0,1,true,false'],
        ];
    }
}

