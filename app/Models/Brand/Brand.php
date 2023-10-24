<?php

namespace App\Models\Brand;

use App\Http\Resources\DataTrueResource;
use App\Models\Product\Product;
use App\Models\User\User;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Brand extends Model
{
    use Notifiable, Scopes, HasApiTokens, SoftDeletes, CreatedbyUpdatedby;

    protected $table = 'brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'description', 'created_by', 'updated_by'
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
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
        'id' => 'string',
        'name' => 'string',
        'description' => 'string',
    ];

    public $sortable = [
        'id', 'name', 'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    /**
     * This method is used to delete role
     *
     * @param $query
     * @param $request
     * @param $brand
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAPI($query, $request, $brand)
    {
        $inUse = Brand::commonCodeForDeleteModelRestrictions([Product::class],'brand_id',$brand->id);
        if(!empty($inUse)) {
            $Brand = Brand::where('id', $brand->id)->first();
            return User::GetError($Brand->name . " can't be deleted as the [" . implode(",", $inUse) . "] are associated with it. Please remove the product mapping with [" . implode(",", $inUse) . "] in order to delete the brand.");
        }
        $brand->delete();
        return new DataTrueResource($brand);
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query,$request) {
        if(!empty($request->id)) {
            $brandname = "";
            $inUses = [];
            foreach ($request->id as $brand_id){
                $inUse = Brand::commonCodeForDeleteModelRestrictions([Product::class],'brand_id', $brand_id);
                $Brand = Brand::where('id', $brand_id)->first();
                if(!empty($inUse))
                    $brandname .= $Brand->name.", ";
                $inUses[] = implode(",", $inUse);
            }
            $brandtrime = trim($brandname,", ");
            $arr = array_diff(array_unique($inUses), array(""));
            if($brandtrime != "") {
                return User::GetError($brandtrime . " can't be deleted as the [" . implode(",", $arr) . "] are associated with it. Please remove the product mapping with [" . implode(",", $arr) . "] in order to delete the categories.");
            } else {
                Brand::whereIn('id', $request->id)->delete();
                return new DataTrueResource(true);
            }
        } else {
            return response()->json(['error' =>config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }
}
