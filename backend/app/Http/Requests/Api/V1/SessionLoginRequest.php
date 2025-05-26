<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class SessionLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array{email: string, password: string}
     */
    public function rules(): array
    {
        return [
            "email" => 'required|email',
            'password' => 'required|string'
        ];
    }
}
