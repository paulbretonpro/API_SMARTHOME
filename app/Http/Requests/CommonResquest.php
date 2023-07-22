<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class CommonRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
