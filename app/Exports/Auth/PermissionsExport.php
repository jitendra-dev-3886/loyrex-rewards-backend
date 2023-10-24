<?php

namespace App\Exports\Auth;

use App\Models\Auth\Permission;
use App\User;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PermissionsExport implements FromCollection, WithHeadings
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
            return User::commonFunctionMethod(Permission::select(
                'permissions.id',
                'permissions.name',
                'permissions.label',
                'permissions.guard_name')->latest()->orderBy('permissions.id', 'desc'), $this->request, true, null, null, true);
        } else {
            return User::commonFunctionMethod(Permission::select(
                'permissions.id',
                'permissions.name',
                'permissions.label',
                'permissions.guard_name'), $this->request, true, null, null, true);
        }
    }

    public function headings():array
    {
        return[
            'Permission ID',
            'Name',
            'Label',
            'Guard Name',
        ];
    }
}
