<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['string', 'min:3', 'max:255'],
            'price' => ['numeric', 'min:0', 'max:999999'],
            'categories' => ['array', 'min:2', 'max:10'],
            'categories.*' => ['exists:categories,id', 'distinct'],
            'published' => ['in:0,1']
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'categories' => $this->categories ?? []
        ]);
    }
}
