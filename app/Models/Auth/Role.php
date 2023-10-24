<?php

namespace App\Models\Auth;

use App\Http\Resources\DataTrueResource;
use App\Traits\Scopes;
use App\Traits\CreatedbyUpdatedby;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use Scopes,SoftDeletes,CreatedbyUpdatedby;

    //public $timestamps = false;
    public $sortable=[
        'name','id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name'
    ];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = [
        'id', 'name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at', 'created_by', 'updated_by'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at'=>'string',
        'updated_at'=>'string',
    ];

    /**
     * Get the Users for the Role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the Permissions for the Role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,"permission_role","role_id","permission_id");
    }


    /**
     * This method is used to delete role
     *
     * @param $query
     * @param $request
     * @param $role
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAPI($query, $request, $role)
    {
        $inUse = Role::commonCodeForDeleteModelRestrictions([User::class],'role_id',$role->id);
        if(!empty($inUse)) {
            $Roles = Role::where('id', $role->id)->first();
            return User::GetError($Roles->name . " can't be deleted as the [" . implode(",", $inUse) . "] are associated with it. Please remove the role mapping with [" . implode(",", $inUse) . "] in order to delete the role.");
        }

        $role->delete();
        return new DataTrueResource($role);
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query,$request){
        if(!empty($request->id)) {
            $rolename = "";
            $inUses = [];
            foreach ($request->id as $role_id){
                $inUse = Role::commonCodeForDeleteModelRestrictions([User::class],'role_id',$role_id);
                $Roles = Role::where('id', $role_id)->first();
                if(!empty($inUse))
                    $rolename.= $Roles->name.", ";
                    $inUses[] = implode(",", $inUse);
            }
            $roletrime = trim($rolename,", ");
            $arr = array_diff(array_unique($inUses), array(""));
            if($roletrime != "") {
                return User::GetError($roletrime . " can't be deleted as the [" . implode(",", $arr) . "] are associated with it. Please remove the role mapping with [" . implode(",", $arr) . "] in order to delete the role.");
            } else {
                Role::whereIn('id', $request->id)->delete();
                return new DataTrueResource(true);
            }
        }
        else{
            return response()->json(['error' =>config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }
}
