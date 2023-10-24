<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RolesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(Request $request)
    {
        $uri = $request->path();
        $urlArr = explode("/",$uri);
        $id=end($urlArr);
        if ($this->method() == 'POST') {
            return [
                'name' => 'required|max:255|unique:roles,name,NULL,id,deleted_at,NULL',
                'guard_name' => 'nullable|max:255',
                'landing_page' => 'nullable|max:255',
            ];
        }
        else{
            return [
                'name' => 'required|max:255|unique:roles,name,' . $id.',id,deleted_at,NULL',
                'guard_name' => 'nullable|max:255',
                'landing_page' => 'nullable|max:255',
            ];
        }
    }
}
