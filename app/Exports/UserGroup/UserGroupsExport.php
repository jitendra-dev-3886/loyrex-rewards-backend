<?php

namespace App\Exports\UserGroup;

use App\Models\User\User;
use App\Models\UserGroup\UserGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserGroupsExport implements FromCollection, WithHeadings
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
        $catalogueIdArr = '(SELECT catalogue_user_group.catalogue_id from catalogue_user_group WHERE catalogue_user_group.userGroup_id = user_groups.id)';
        if($this->request->sort == "" && $this->request->order_by == "") {
            return User::commonFunctionMethod(UserGroup::select(
                'id', 'name',DB::raw('(SELECT COUNT(*) FROM user_group_user WHERE userGroup_id  = user_groups.id GROUP BY userGroup_id ) AS no_of_user_groups'),
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT catalogues.name) FROM catalogues WHERE id IN('.$catalogueIdArr.')) AS catalogue_name'),
            )
                ->latest()->orderBy('id', 'desc'),$this->request, true, null, null, true);
        }else{
            return User::commonFunctionMethod(UserGroup::select(
                'id', 'name',DB::raw('(SELECT COUNT(*) FROM user_group_user WHERE userGroup_id  = user_groups.id GROUP BY userGroup_id ) AS no_of_user_groups'),
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT catalogues.name) FROM catalogues WHERE id IN('.$catalogueIdArr.')) AS catalogue_name'),
            ),$this->request, true, null, null, true);
        }
    }

    public function headings():array
    {
        return ['Group ID', 'Group Name','No. of Users','Catalogues'];
    }
}
