<?php

namespace App\Models\Catalogue;

use App\Http\Resources\DataTrueResource;
use App\Models\Product\ProductCatalogue;
use App\Models\User\User;
use App\Models\UserGroup\CatalogueUserGroup;
use App\Models\UserGroup\UserGroup;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalogue extends Model
{

    use SoftDeletes,Scopes;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, "catalogue_user_group", "catalogue_id", " userGroup_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(ProductCatalogue::class, 'catalogue_id');
    }

    /**
     * This method is used to delete role
     *
     * @param $query
     * @param $request
     * @param $catalogue
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAPI($query, $request, $catalogue)
    {
        $inUse = Catalogue::commonCodeForDeleteModelRestrictions([CatalogueUserGroup::class],'catalogue_id',$catalogue->id);
        if(!empty($inUse)) {
            $catalogue = Catalogue::where('id', $catalogue->id)->first();
            return User::GetError($catalogue->name . " can't be deleted as the [" . implode(",", $inUse) . "] are associated with it. Please remove the user group mapping with [" . implode(",", $inUse) . "] in order to delete the catalogue.");
        }
        CatalogueUserGroup::where('catalogue_id', $catalogue->id)->delete();
        $catalogue->delete();
        return new DataTrueResource($catalogue);
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

            $catalogueName = "";
            $inUses = [];
            foreach ($request->id as $catalogue_id){
                $inUse = Catalogue::commonCodeForDeleteModelRestrictions([CatalogueUserGroup::class],'catalogue_id', $catalogue_id);
                $catalogue = Catalogue::where('id', $catalogue_id)->first();
                if(!empty($inUse))
                    $catalogueName .= $catalogue->name.", ";
                $inUses[] = implode(",", $inUse);
            }
            $cataloguetrime = trim($catalogueName,", ");
            $arr = array_diff(array_unique($inUses), array(""));
            if($cataloguetrime != "") {
                return User::GetError($cataloguetrime . " can't be deleted as the [" . implode(",", $arr) . "] are associated with it. Please remove the user group mapping with [" . implode(",", $arr) . "] in order to delete the catalogue.");
            } else {
                CatalogueUserGroup::whereIn('userGroup_id', $request->id)->delete();
                Catalogue::whereIn('id', $request->id)->delete();
                return new DataTrueResource(true);
            }
        } else {
            return response()->json(['error' =>config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }
}
