<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SensorStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric'
        ];
    }
}
