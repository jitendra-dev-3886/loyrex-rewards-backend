<?php

namespace App\Exports\Product;

use App\Models\Product\Product;
use App\Models\User\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ProductExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $catalogueIdSelectQry = '(SELECT GROUP_CONCAT(DISTINCT product_catalogue.catalogue_id) from product_catalogue WHERE product_catalogue.product_id = products.id)';
        $catalogueIdArr = '(SELECT product_catalogue.catalogue_id from product_catalogue WHERE product_catalogue.product_id = products.id)';

        if($this->request->sort == "" && $this->request->order_by == "") {

            return  User::commonFunctionMethod(Product::select('products.id', 'products.name', DB::raw('FORMAT(products.point, 0)'), 'products.description',
                DB::raw('(SELECT categories.name from categories WHERE categories.id = products.category_id) AS category_name'),
                'products.category_id',
                DB::raw('(SELECT brands.name from brands WHERE brands.id = products.brand_id) AS brand_name'), 'products.brand_id',
                DB::raw('(SELECT categories.name from categories WHERE categories.id = products.sub_category_id) AS sub_category_name'), 'products.sub_category_id',
                DB::raw($catalogueIdSelectQry.' AS catalogue_id'),
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT catalogues.name) FROM catalogues WHERE id IN('.$catalogueIdArr.')) AS catalogue_name'),
                DB::raw('(CASE WHEN products.available_status = "' . config('constants.products.available_status_code.available') . '"
                THEN "' . config('constants.products.available_status.1').'"
                ELSE "'.config('constants.products.available_status.0').'"  END) AS available_status'),
            )->latest()->orderBy('products.id', 'desc'), $this->request, true, null, null, true);
        } else {
            return  User::commonFunctionMethod(Product::select('products.id', 'products.name', DB::raw('FORMAT(products.point, 0)'), 'products.description',
                DB::raw('(SELECT categories.name from categories WHERE categories.id = products.category_id) AS category_name'), 'products.category_id',
                DB::raw('(SELECT brands.name from brands WHERE brands.id = products.brand_id) AS brand_name'), 'products.brand_id',
                DB::raw('(SELECT categories.name from categories WHERE categories.id = products.sub_category_id) AS sub_category_name'), 'products.sub_category_id',
                DB::raw($catalogueIdSelectQry.' AS catalogue_id'),
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT catalogues.name) FROM catalogues WHERE id IN('.$catalogueIdArr.')) AS catalogue_name'),
                DB::raw('(CASE WHEN products.available_status = "' . config('constants.products.available_status_code.available') . '"
                THEN"' . config('constants.products.available_status.1').'" ELSE "'.config('constants.products.available_status.0').'"  END) AS available_status'),
            ), $this->request, true, null, null, true);
        }
    }

    public function headings():array
    {
        return[
            'Product ID',
            'Product Name',
            'Points',
            'Description',
            'Category',
            'Category ID',
            'Brand Name',
            'Brand Id',
            'Sub Category',
            'Sub Category Id',
            'Catalogue Id',
            'Catalogue Name',
            'Availability'
        ];
    }
}
