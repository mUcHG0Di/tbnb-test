<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
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
            'products_uuids' => ['required', 'array', 'min:1'],
            'products_uuids.*' => ['required', 'uuid'],
        ];
    }

    public function messages()
    {
        return [
            'products_uuids.required' => 'The products uuids are required',
            'products_uuids.array' => 'The products uuids are required',
            'products_uuids.min' => 'The products uuids are required',
            'products_uuids.*.required' => 'The uuid field is required.',
            'products_uuids.*.uuid' => 'The uuid must be a valid UUID',
        ];
    }
}
