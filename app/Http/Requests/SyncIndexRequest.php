<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'since_last' => 'sometimes|boolean',
            'captor' => 'sometimes|boolean',
            'sensor' => 'sometimes|boolean',
            'weather' => 'sometimes|boolean',
        ];
    }
}
