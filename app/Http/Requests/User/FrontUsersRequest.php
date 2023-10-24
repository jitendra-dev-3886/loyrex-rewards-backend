<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FrontUsersRequest extends FormRequest
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

        $commonRule = [
            'first_name' => 'required|regex:/^[a-zA-Z_ ]*$/|max:191',
            'last_name' => 'required|regex:/^[a-zA-Z_ ]*$/|max:191',
            'job_title' => 'required|max:191',
            'reward_points' => 'required|min:0|digits_between:1,6',
            'userGroup_id' => 'required|exists:user_groups,id,deleted_at,NULL|array',
            'userGroup_id.*' => 'required|integer',
        ];

        $user_type = config('constants.user.user_type_code.user');
        if ($uri == 'api/v1/users-add') {
            $commonRule['email'] = 'required|max:191|email|unique:users,email,NULL,id,deleted_at,NULL,user_type,' . $user_type;
            $commonRule['contact_number'] = 'required|regex:/^[6-9]\d{9}$/|digits:10|unique:users,contact_number,NULL,id,deleted_at,NULL,user_type,' . $user_type;
            $commonRule['password'] = 'required|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#@&:%]).*$/|min:8|max:255';
        } else {
            $commonRule['email'] = 'required|max:191|email|unique:users,email,' . $id . ',id,deleted_at,NULL,user_type,' . $user_type;
            $commonRule['contact_number'] = 'required|regex:/^[6-9]\d{9}$/|digits:10|unique:users,contact_number,' . $id . ',id,deleted_at,NULL,user_type,' . $user_type;
            $commonRule['action_type'] = ['nullable', Rule::in([0, 1])];
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
