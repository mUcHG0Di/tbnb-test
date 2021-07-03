<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'max:64'],
            'description' => ['max:128'],
            'price' => ['required', 'numeric', 'min:1'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        // If it's an update, image is not required
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);
        $newImageRule = ($isUpdate) ? 'nullable' : 'required';
        array_push($rules['image'], $newImageRule);

        return $rules;
    }
}
