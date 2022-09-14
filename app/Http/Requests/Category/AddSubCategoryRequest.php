<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class AddSubCategoryRequest extends FormRequest
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
     * Get the validation rules that apply sto the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required|string|exists:categories,id',
            'name' => 'required|string|unique:sub_categories',
        ];
    }
}
