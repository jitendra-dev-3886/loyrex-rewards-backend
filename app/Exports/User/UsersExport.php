<?php

namespace App\Exports\User;

use App\Models\User\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class UsersExport implements FromCollection, WithHeadings
{
    protected $request; // defined private $request variable

    public function __construct($request) // constructor method
    {
        $this->request = $request; // assign $request $this variable
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if($this->request->sort == "" && $this->request->order_by == "") {
            return  User::commonFunctionMethod(User::select('users.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name)"),
                'users.job_title', 'users.contact_number', 'users.email',
                DB::raw('FORMAT(users.reward_points, 0)'),
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT user_group_user.userGroup_id) from user_group_user WHERE user_group_user.user_id = users.id) AS userGroup_id'))
                ->latest()->orderBy('users.id', 'desc'), $this->request, true, null, null, true);
        } else {
            return  User::commonFunctionMethod(User::select('users.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name)"),
                'users.job_title', 'users.contact_number', 'users.email',
                DB::raw('FORMAT(users.reward_points, 0)'),
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT user_group_user.userGroup_id) from user_group_user WHERE user_group_user.user_id = users.id) AS userGroup_id')),
                $this->request, true, null, null, true);
        }
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Name',
            'Job title',
            'Contact no.',
            'Email',
            'Reward points',
            'UserGroup id',
        ];
    }
}
