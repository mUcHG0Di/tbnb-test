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
}
