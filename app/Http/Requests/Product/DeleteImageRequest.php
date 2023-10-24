<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class DeleteImageRequest extends FormRequest
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
        $commonRule = [
            'is_feature' => ['required', Rule::in([0, 1])],
        ];

        if($request->is_feature == '1') {
            $commonRule['product_id'] = 'required|integer|exists:products,id,deleted_at,NULL';
        } else {
            $commonRule['product_id'] = 'required|integer|exists:product_images,product_id,deleted_at,NULL';
            $commonRule['image_id'] = 'required|integer|exists:product_images,id,deleted_at,NULL';
        }

        return $commonRule;
    }
}
