<?php

namespace App\Exports\Brand;

use App\Models\Brand\Brand;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BrandsExport implements FromCollection, WithHeadings
{
    protected $request;// defined private $request variable

    public function __construct($request)// constructor method
    {
        $this->request = $request;// assign $request $this variable
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if($this->request->sort == "" && $this->request->order_by == "") {
            return User::commonFunctionMethod(Brand::select('brands.id', 'brands.name', 'brands.description',
                DB::raw('(SELECT IF(COUNT(products.id) > 0, COUNT(products.id), "0") FROM products WHERE products.brand_id = brands.id AND products.deleted_at is null) AS no_of_items')
            )->latest()->orderBy('id', 'desc'), $this->request, true, null, null, true);
        } else {
            return User::commonFunctionMethod(Brand::select('brands.id', 'brands.name', 'brands.description',
                DB::raw('(SELECT IF(COUNT(products.id) > 0, COUNT(products.id), "0") FROM products WHERE products.brand_id = brands.id AND products.deleted_at is null) AS no_of_items')
            ), $this->request, true, null, null, true);
        }
    }

    public function headings():array
    {
        return ['Brand ID', 'Brand name', 'Brand description', 'No. of items'];
    }
}
