<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Hash;

class ChangePasswordRequest extends FormRequest
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
            'old_password' => ['required', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The old password is incorrect.'));
                }
            }],
            'new_password' => 'required|required_with:confirm_password|same:confirm_password|different:old_password|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#@&:%]).*$/|min:8|max:255',
            'confirm_password' => 'required|min:6|max:255',
        ];
    }

    public function messages()
    {
        return [
            'new_password.regex' => "Password should contain at least one numeric character, one lowercase letter, one uppercase letter and one special character."
        ];
    }
}
