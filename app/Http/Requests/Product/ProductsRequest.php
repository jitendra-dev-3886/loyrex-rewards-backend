<?php

namespace App\Http\Requests\Product;

use App\Rules\VaporFileCheckExist;
use App\Rules\VaporFileSize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ProductsRequest extends FormRequest
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

        $commonRule = [
            'name' => 'required | max:191',
            'category_id' => 'required|integer|exists:categories,id,deleted_at,NULL',
            'sub_category_id' => 'nullable|integer|exists:categories,id,deleted_at,NULL',
            'catalogue_id' => 'required|exists:catalogues,id,deleted_at,NULL|array',
            'catalogue_id.*' => 'required|integer',
            'brand_id' => 'required|integer|exists:brands,id,deleted_at,NULL',
            'available_status' => ['required', Rule::in([0, 1])],
            'description' => 'required|max:500',
            'point' => "required|integer|min:1|max:99999"
        ];

        if ($uri == 'api/v1/products') {
            $commonRule['featured_image_key'] = ['required', new VaporFileCheckExist, new VaporFileSize()];
            $commonRule['featured_image_extension'] = ['required', Rule::in(config('constants.default_file_extensions'))];
            $commonRule['upload_images'] = 'required|array|max:4';
            $commonRule['upload_images.*.key'] = ['required', new VaporFileCheckExist, new VaporFileSize()];
            $commonRule['upload_images.*.extension'] = ['required', Rule::in(config('constants.default_file_extensions'))];
        }

        return $commonRule;
    }
}
