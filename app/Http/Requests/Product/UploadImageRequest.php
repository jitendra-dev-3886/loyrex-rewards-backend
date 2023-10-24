<?php

namespace App\Http\Requests\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use App\Rules\VaporFileCheckExist;
use App\Rules\VaporFileSize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UploadImageRequest extends FormRequest
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
            'product_id' => 'required|integer|exists:products,id,deleted_at,NULL'
        ];

        $Product = Product::where('id', (int)$request->product_id)->first();

        if($request->is_feature == '1') {
            $max_feature_image = 1;
            if(!is_null($Product)) {

                $featured_image = basename($Product->featured_image);

                if($featured_image != "default_product_image.png") {
                    $max_feature_image = $max_feature_image - 1;
                }
            }

            $commonRule['product_images'] = 'required|array|max:'.$max_feature_image;
        } else {
            $max_product_image = 4;
            if(!is_null($Product)) {
                $filenames = $Product->product_images()->get()->pluck('filename');
                $base_names = [];
                foreach ($filenames as $filename) {
                    if(basename($filename) != "default_product_image.png") {
                        $base_names[] = basename($filename);
                    }
                }

                if(count($base_names) > 0) {
                    $max_product_image = $max_product_image - count($base_names);
                }
            }

            $commonRule['product_images'] = 'required|array|max:'.$max_product_image;
        }


        $commonRule['product_images.*.key'] = ['required', new VaporFileCheckExist, new VaporFileSize()];
        $commonRule['product_images.*.extension'] = ['required', Rule::in(config('constants.default_file_extensions'))];


        return $commonRule;
    }
}
