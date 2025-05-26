<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreSavedRecipesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array{recipe_id: string}
     */
    public function rules(): array
    {
        return [
            'recipe_id' => 'required|exists:recipes,id',
        ];
    }
}
