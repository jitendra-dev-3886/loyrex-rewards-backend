<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoriesRequest extends FormRequest
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
        if ($this->method() == 'POST') {
            return [
                'name' => 'required|string|max:191',
                'parent_id' => 'integer|nullable|exists:categories,id',
            ];
        } else {
            return [
                'name' => 'required|string|max:191',
                'parent_id' => 'integer|exists:categories,id|nullable',
            ];
        }
    }
}
