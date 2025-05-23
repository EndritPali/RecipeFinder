<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreSavedRecipesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return  true;
    }

    public function rules(): array
    {
        return [
            'recipe_id' => 'required|exists:recipes,id',
        ];
    }
}
