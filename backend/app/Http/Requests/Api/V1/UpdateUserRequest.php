<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $this->user,
            'password' => 'nullable|string|min:6',
            'role' => 'sometimes|string'
        ];
    }
}
