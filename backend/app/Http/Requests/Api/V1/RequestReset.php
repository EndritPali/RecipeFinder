<?php

namespace App\http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class RequestReset extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id'
        ];
    }
}
