<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SensorIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'sometimes|date',
            'date_start' => 'sometimes|date',
            'date_end' => 'sometimes|date',
        ];
    }
}
