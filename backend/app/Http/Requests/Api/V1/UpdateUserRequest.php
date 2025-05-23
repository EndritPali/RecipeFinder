<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     * Get the validation rules that apply to that request
     * 
     * @return array{email: string, password: string, role: string, username: string}
     */
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
