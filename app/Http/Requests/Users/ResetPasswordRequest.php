<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
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
            'email_address'             => 'required|email|exists:users',
            'token'                     => 'required|string|exists:password_resets',
            'new_password'              => ['required', 'confirmed', Password::min(8)->uncompromised()],
            'new_password_confirmation' => 'required|string',
        ];
    }
}
