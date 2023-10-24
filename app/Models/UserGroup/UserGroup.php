<?php

namespace App\Models\UserGroup;

use App\Http\Resources\DataTrueResource;
use App\Http\Resources\UserGroup\UserGroupsResource;
use App\Models\Catalogue\Catalogue;
use App\Models\User\User;
use App\Models\User\UserGroupUser;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
{

    use SoftDeletes, Scopes;

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = [
        'id', 'name'
    ];
    public $sortable = [
        'id', 'name'
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
        'id' => 'string',
        'name' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',

    ];

    public $foreign_sortable = [
        'catalogue_id',
    ];

    public $foreign_table = [
        'catalogues'
    ];

    public $foreign_key = [
        'name',
    ];

    public $foreign_method = [
        'catalogues',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, "user_group_user","userGroup_id", "user_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function catalogues()
    {
        return $this->belongsToMany(Catalogue::class, "catalogue_user_group",  "userGroup_id","catalogue_id");
    }

    public function scopeCreateUserGroup($query, $request)
    {
        $data = $request->all();
        $userGroup = UserGroup::create($data);

        if ($request['catalogue_id']) {
            //this executes the insert-query
            $userGroup->catalogues()->attach($request['catalogue_id']);
        }

        return new UserGroupsResource($userGroup);
    }

    public function scopeUpdateUserGroup($query, $request, $userGroup)
    {
        $data = $request->all();

        $userGroup->update($data);

        $userGroup->catalogues()->detach();
        //this executes the insert-query
        $userGroup->catalogues()->attach($data['catalogue_id']);

        return new UserGroupsResource($userGroup);
    }

    /**
     * This method is used to delete role
     *
     * @param $query
     * @param $request
     * @param $userGroup
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAPI($query, $request, $userGroup)
    {
        $inUse = UserGroup::commonCodeForDeleteModelRestrictions([UserGroupUser::class], 'userGroup_id', $userGroup->id);
        if (!empty($inUse)) {
            $userGroup = UserGroup::where('id', $userGroup->id)->first();
            return User::GetError($userGroup->name . " can't be deleted as the [" . implode(",", $inUse) . "] are associated with it. Please remove the user mapping with [" . implode(",", $inUse) . "] in order to delete the user group.");
        }
        UserGroupUser::where('userGroup_id', $userGroup->id)->delete();
        $userGroup->delete();
        return new DataTrueResource($userGroup);
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query, $request)
    {
        if (!empty($request->id)) {
            $userGroupName = "";
            $inUses = [];
            foreach ($request->id as $userGroup_id) {
                $inUse = UserGroup::commonCodeForDeleteModelRestrictions([UserGroupUser::class], 'userGroup_id', $userGroup_id);
                $userGroup = UserGroup::where('id', $userGroup_id)->first();
                if (!empty($inUse))
                    $userGroupName .= $userGroup->name . ", ";
                $inUses[] = implode(",", $inUse);
            }
            $userGrouptrime = trim($userGroupName, ", ");
            $arr = array_diff(array_unique($inUses), array(""));
            if ($userGrouptrime != "") {
                return User::GetError($userGrouptrime . " can't be deleted as the [" . implode(",", $arr) . "] are associated with it. Please remove the user mapping with [" . implode(",", $arr) . "] in order to delete the user group.");
            } else {
                UserGroupUser::whereIn('userGroup_id', $request->id)->delete();
                UserGroup::whereIn('id', $request->id)->delete();
                return new DataTrueResource(true);
            }
        } else {
            return response()->json(['error' => config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }
}
