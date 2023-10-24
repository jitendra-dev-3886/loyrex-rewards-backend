<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PermissionsRequest extends FormRequest
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
                'name' => 'required|max:191|unique:permissions,name,NULL,id,deleted_at,NULL',
                'label'=> 'required|max:191',
                'guard_name'=> 'required|max:191',
            ];
        } else {
            return [
                'name' => 'required|max:191|unique:permissions,name,' . $id.',id,deleted_at,NULL',
                'label'=> 'required|max:191',
                'guard_name'=> 'required|max:191',
            ];
        }
    }
}
