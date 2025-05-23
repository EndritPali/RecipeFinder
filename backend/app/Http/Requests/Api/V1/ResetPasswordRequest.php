<?php

namespace App\http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'reset_token' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
