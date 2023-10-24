<?php

namespace App\Exports\Auth;

use App\Models\Auth\Role;
use App\User;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RolesExport implements FromCollection, WithHeadings
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
            return User::commonFunctionMethod(Role::select('roles.id', 'roles.name')->latest()->orderBy('roles.id', 'desc'), $this->request, true, null, null, true);
        } else {
            return User::commonFunctionMethod(Role::select('roles.id', 'roles.name'), $this->request, true, null, null, true);
        }
    }

    public function headings():array
    {
        return[
            'Role ID',
            'Role Name',
        ];
    }
}
