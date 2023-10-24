<?php

namespace App\Http\Requests\Admin;

//use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UsersRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $uri = $request->path();
        $urlArr = explode("/", $uri);
        $id = end($urlArr);
        $user_type = config('constants.user.user_type_code.admin');
        $commonRule = [
            'first_name' => 'required | regex:/^[a-zA-Z_ ]*$/ | max:191',
            'last_name' => 'required | regex:/^[a-zA-Z_ ]*$/ | max:191',
            'contact_number' => 'required | regex:/^[6-9]\d{9}$/ | digits:10',
            'role_id' => 'required|integer|exists:roles,id,deleted_at,NULL',
        ];

        if ($uri == 'api/v1/admin-register') {
            $commonRule['email'] = 'required|max:191|unique:users,email,NULL,id,deleted_at,NULL,user_type,' . $user_type;
            $commonRule['password'] = 'required|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#@&:%]).*$/|min:8|max:255';
        } else {
            $commonRule['email'] = 'required|max:255|unique:users,email,' . $id . ',id,deleted_at,NULL,user_type,' . $user_type;
        }

        return $commonRule;
    }

    public function messages()
    {
        return [
            'password.regex' => "Password should contain at least one numeric character, one lowercase letter, one uppercase letter and one special character."
        ];
    }
}
