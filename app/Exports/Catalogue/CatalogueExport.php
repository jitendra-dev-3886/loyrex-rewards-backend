<?php

namespace App\Exports\Catalogue;

use App\Models\Catalogue\Catalogue;
use App\Models\User\User;
use App\Models\UserGroup\UserGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CatalogueExport implements FromCollection, WithHeadings
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
            return User::commonFunctionMethod(Catalogue::select(
                'id', 'name',DB::raw('(SELECT COUNT(*) FROM product_catalogue WHERE catalogue_id = catalogues.id GROUP BY catalogue_id) AS no_of_items'))
                ->latest()->orderBy('id', 'desc'),$this->request, true, null, null, true);
        }else{
            return User::commonFunctionMethod(Catalogue::select(
                'id', 'name',DB::raw('(SELECT COUNT(*) FROM product_catalogue WHERE catalogue_id = catalogues.id GROUP BY catalogue_id) AS no_of_items')),
                $this->request, true, null, null, true);
        }
    }

    public function headings():array
    {
        return ['Catalogue ID', 'Catalogue Name', 'No of items'];
    }
}
