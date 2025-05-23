<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class SessionLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "email" => 'required|email',
            'password' => 'required|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
