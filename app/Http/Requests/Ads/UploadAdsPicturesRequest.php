<?php

namespace App\Http\Requests\Ads;

use Illuminate\Foundation\Http\FormRequest;

class UploadAdsPicturesRequest extends FormRequest
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
            'pictures' => 'required|array|max:5',
            'pictures.*' => 'required|image|mimes:jpg, png, jpeg|max:2048',
        ];
    }

    public function messages() {
        return [
            'pictures.max' => 'Maximum allowed number of images is 5',
            'pictures.*.max' => 'Maximum allowed size for an image is 2MB',
        ];
    }
}
