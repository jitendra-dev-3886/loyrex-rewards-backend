<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

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
            'token' => 'required|exists:password_resets,token',
            'password' => 'required|confirmed|min:6|max:255',
            'password_confirmation' => 'required|min:6|max:255',

            // confirmed validation need one more key 'password_confirmation' and both have same value;
            // ex. '{key_name}_confirmation'
        ];
    }
}
