<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIngredientRequest extends FormRequest
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
     * @return array{name: string, unit: string}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'unit' => 'required|string|max:20'
        ];
    }
}
