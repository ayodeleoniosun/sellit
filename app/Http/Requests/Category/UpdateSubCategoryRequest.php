<?php

namespace App\Http\Requests\Category;

use App\Models\SubCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubCategoryRequest extends FormRequest
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
        $subCategory = SubCategory::where('slug', $this->slug)->firstOrFail();

        return [
            'category_id' => 'required|string|exists:categories,id',
            'name' => [
                'required',
                'string',
                Rule::unique('sub_categories', 'name')->ignore($subCategory->id)
            ]
        ];
    }
}
