<?php

namespace App\Exports\Admin;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class UsersExport implements FromCollection, WithHeadings
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
            return  User::commonFunctionMethod(User::select('users.id',
                DB::raw("concat(users.first_name, ' ', users.last_name)"),
                DB::raw('(SELECT name from roles WHERE roles.id = users.role_id) AS role_name'),
                'contact_number', 'email')->latest()->orderBy('users.id', 'desc'), $this->request, true, null, null, true);
        } else {
            return  User::commonFunctionMethod(User::select('users.id',
                DB::raw("concat(users.first_name, ' ', users.last_name)"),
                DB::raw('(SELECT name from roles WHERE roles.id = users.role_id) AS role_name'),
                'users.contact_number', 'users.email'), $this->request, true, null, null, true);
        }
    }

    public function headings():array
    {
        return[
            'Admin ID',
            'Name',
            'Role',
            'Contact no',
            'Email',
        ];
    }
}
