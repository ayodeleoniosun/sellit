<?php

namespace App\Http\Requests\Ads;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewAdsRequest extends FormRequest
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
            'category_id'    => 'required|string|exists:categories,id',
            'sub_category_id'    => 'required|string|exists:sub_categories,id',
            'name'     => 'required|string',
            'price' => 'required|integer',
            'description'  => 'required|string',
        ];
    }
}
