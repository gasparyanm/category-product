<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999'],
            'categories' => ['required', 'array', 'min:2', 'max:10'],
            'categories.*' => ['required', 'exists:categories,id', 'distinct'],
            'published' => ['in:0,1']
        ];
    }
}
