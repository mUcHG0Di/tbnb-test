<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class BulkStoreUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'products' => ['required', 'array', 'min:1'],
            'products.*.name' => ['required', 'max:64'],
            'products.*.description' => ['max:128'],
            'products.*.price' => ['required', 'numeric', 'min:1'],
            'products.*.quantity' => ['required', 'numeric', 'min:1'],
        ];
    }

    /**
     * Custom messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'products.*.name.required' => 'The name field is required.',
            'products.*.description.max' => 'The description field max chars is :max.',
            'products.*.price.required' => 'The price field is required.',
            'products.*.price.numeric' => 'The price value must be numeric.',
            'products.*.price.min' => 'The price must be at least :min.',
            'products.*.quantity.required' => 'The price field is required.',
            'products.*.quantity.numeric' => 'The price value must be numeric.',
            'products.*.quantity.min' => 'The price must be at least :min.'
        ];
    }
}
