<?php

namespace App\http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     * 
     * @return array{password: string, reset_token: string, user_id: string}
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'reset_token' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
