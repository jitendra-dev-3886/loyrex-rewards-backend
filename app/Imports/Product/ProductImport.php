<?php

namespace App\Imports\Product;

use App\Models\Product\Product;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductImport implements ToCollection, WithStartRow, WithHeadingRow
{
    use Scopes, CreatedbyUpdatedby;

    private $errors = [];
    private $rows = 0;

    public function startRow(): int
    {
        return 2; // fetch record from second row at import bulk order time.
    }

    public function getErrors()
    {
        return $this->errors; // return all errors
    }

    public function rules(): array
    {
        return [
            'product_name' => 'required | max:191',
            'points' => 'required|integer|min:1|max:99999',
            'description' => 'required|max:500',
            'category_id' => 'required|integer|exists:categories,id,deleted_at,NULL',
            'sub_category_id' => 'nullable|integer|exists:categories,id,deleted_at,NULL',
            'brand_id' => 'required|integer|exists:brands,id,deleted_at,NULL',
            'availability' => ['required', Rule::in([0, 1])],
            'catalogue_id' => 'required|exists:catalogues,id,deleted_at,NULL'
        ];
    }

    public function validationMessages()
    {
        return [
            'product_name.required' => trans('The name field is required.'),
            'product_name.max' => trans('The name should be max 191.'),

            'points.required' => trans('The point field is required.'),
            'points.integer' => trans('The point field should be integer.'),
            'points.min' => trans('The point must be at least 1.'),
            'points.max' => trans('The points must not be greater than 99999.'),

            'description.required' => trans('The description field is required.'),
            'description.max' => trans('The description field should be max 191.'),

            'category_id.required' => trans('The category id field is required.'),
            'category_id.integer' => trans('The category id field should be integer.'),
            'category_id.exists' => trans('The category id field should exists in categories.'),

            'sub_category_id.integer' => trans('The sub category id field should be integer.'),
            'sub_category_id.exists' => trans('The sub category id field should exists in categories.'),

            'brand_id.required' => trans('The brand id field is required.'),
            'brand_id.integer' => trans('The brand id field should be integer.'),
            'brand_id.exists' => trans('The brand id field should exists in brands.'),

            'availability.required' => trans('The available status field is required'),
            'availability.in' => trans('The available status should be either 0 or 1'),

            'catalogue_id.required' => trans('The Catalogue id field is required.'),
            'catalogue_id.exists' => trans('The catalogue id field should exists in catalogues.'),
        ];
    }

    public function validateBulk($collection)
    {
        $i = 1;

        $keys = ['product_name', 'points', 'description', 'category_id', 'sub_category_id', 'brand_id', 'catalogue_id', 'availability'];

        foreach ($collection as $col) {
            $i++;

            if (count(array_intersect_key(array_flip($keys), $col->toArray())) !== count($keys)) {
                $this->errors[] = 'Invalid file format, Please download sample file.';
                break;
            }

            $validator = Validator::make($col->toArray(), $this->rules(), $this->validationMessages());
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $messages) {
                    foreach ($messages as $error) {
                        $this->errors[] = $error . ' on row ' . $i;
                    }
                }
            }
        }
        return $this->getErrors();
    }

    public function collection(Collection $collection)
    {
        $error = $this->validateBulk($collection);
        if ($error) {
            return;
        } else {
            foreach ($collection as $col) {
                $product =  Product::create([
                    'name' => $col['product_name'],
                    'point' => (string)$col['points'],
                    'description' => $col['description'],
                    'category_id' => (string)$col['category_id'],
                    'sub_category_id' => (string)$col['sub_category_id'],
                    'brand_id' => (string)$col['brand_id'],
                    'available_status' => (string)$col['availability'],
                ]);

                $catalogue_ids = array_filter(explode(",", $col['catalogue_id']));
                if (!empty($catalogue_ids)) {
                    $product->catalogues()->attach($catalogue_ids);
                }

                $this->rows++;
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
