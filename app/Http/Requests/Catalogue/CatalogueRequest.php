<?php

namespace App\Http\Requests\Catalogue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CatalogueRequest extends FormRequest
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
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        $uri = $request->path();
        $urlArr = explode("/", $uri);
        $id = end($urlArr);

        if ($this->method() == 'POST') {
            return [
                'name' => 'required|max:191|unique:catalogues,name,NULL,id,deleted_at,NULL',
            ];
        } else {
            return [
                'name' => 'required|max:191|unique:catalogues,name,'.$id.',id,deleted_at,NULL',
            ];
        }
    }
}
