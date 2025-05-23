<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'             => 'required|string|max:100',
            'short_description' => 'required|string|min:5',
            'rating'            => 'required|integer|min:1|max:5',
            'image_url'         => 'required|url',
            'instructions'      => 'required|string',
            'preparation_time'  => 'required|integer|min:1',
            'cooking_time'      => 'required|integer|min:1',
            'servings'          => 'required|integer|min:1',
            'ingredients'       => 'required|string',
            'category'          => 'required|string',
        ];
    }
}
