<?php

namespace App\Exports\Category;

use App\Models\Category\Category;
use App\Models\User\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromCollection, WithHeadings
{
    protected $request;// defined private $request variable

    public function __construct($request)// constructor method
    {
        $this->request = $request;// assign $request $this variable
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        if($this->request->sort == "" && $this->request->order_by == "") {
            return User::commonFunctionMethod(Category::select('id', 'name',
                DB::raw('(SELECT name FROM categories as c WHERE c.id = categories.parent_id) AS category_name'),
                DB::raw('(
                CASE WHEN categories.parent_id = 0 THEN
                    (SELECT IF(COUNT(products.id) > 0, COUNT(products.id), "0") FROM products WHERE products.category_id = categories.id AND products.deleted_at is null)
                ELSE
                    (SELECT IF(COUNT(products.id) > 0, COUNT(products.id), "0") FROM products WHERE products.sub_category_id = categories.id AND products.deleted_at is null)
                END) AS no_of_items'))->latest()->orderBy('id', 'desc'), $this->request, true, null, null, true);
        } else {
            return User::commonFunctionMethod(Category::select('id', 'name',
                DB::raw('(SELECT name from categories as c WHERE id = categories.parent_id) AS category_name'),
                DB::raw('(
                CASE WHEN categories.parent_id = 0 THEN
                    (SELECT IF(COUNT(products.id) > 0, COUNT(products.id), "0") FROM products WHERE products.category_id = categories.id AND products.deleted_at is null)
                ELSE
                    (SELECT IF(COUNT(products.id) > 0, COUNT(products.id), "0") FROM products WHERE products.sub_category_id = categories.id AND products.deleted_at is null)
                END) AS no_of_items')), $this->request, true, null, null, true);
        }
    }

    public function headings():array
    {
        return ['Category ID', 'Category name', 'Parent category', 'No. of items'];
    }
}
