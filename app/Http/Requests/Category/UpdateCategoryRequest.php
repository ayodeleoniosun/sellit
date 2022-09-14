<?php

namespace App\Http\Requests\Category;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        $category = Category::where('slug', $this->slug)->firstOrFail();

        return [
            'name' => [
                'required',
                Rule::unique('categories', 'name')->ignore($category->id)
            ],
            'icon' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
