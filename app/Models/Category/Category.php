<?php

namespace App\Models\Category;

use App\Http\Resources\DataTrueResource;
use App\Models\Product\Product;
use App\Models\User\CategoryUser;
use App\Models\User\User;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Category extends Model
{
    use Notifiable, Scopes, HasApiTokens, SoftDeletes, CreatedbyUpdatedby;

    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'parent_id', 'step', 'created_by', 'updated_by'
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
        'deleted_at', 'created_by', 'updated_by', 'step'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
        'id' => 'string',
        'parent_id' => 'string',
        'name' => 'string',
        'step' => 'string'
    ];

    public $sortable = [
        'id', 'name', 'parent_id', 'step'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categories()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subCategories()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->select(['id', 'name', 'parent_id']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subcategory_products()
    {
        return $this->hasMany(Product::class, 'sub_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent_categories()
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    /**
     * This method is used to delete role
     *
     * @param $query
     * @param $request
     * @param $category
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAPI($query, $request, $category)
    {
        $inUse = Category::commonCodeForDeleteModelRestrictions([Product::class],'category_id',$category->id);
        if(!empty($inUse)) {
            $Category = Category::where('id', $category->id)->first();
            return User::GetError($Category->name . " can't be deleted as the [" . implode(",", $inUse) . "] are associated with it. Please remove the product mapping with [" . implode(",", $inUse) . "] in order to delete the category.");
        }
        CategoryUser::where('category_id', $category->id)->delete();
        $category->subCategories()->delete();
        $category->delete();
        return new DataTrueResource($category);
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query,$request){
        if(!empty($request->id)) {
            $categoryname = "";
            $inUses = [];
            foreach ($request->id as $category_id){
                $inUse = Category::commonCodeForDeleteModelRestrictions([Product::class],'category_id', $category_id);
                $Category = Category::where('id', $category_id)->first();
                if(!empty($inUse))
                    $categoryname .= $Category->name.", ";
                $inUses[] = implode(",", $inUse);
            }
            $categorytrime = trim($categoryname,", ");
            $arr = array_diff(array_unique($inUses), array(""));
            if($categorytrime != "") {
                return User::GetError($categorytrime . " can't be deleted as the [" . implode(",", $arr) . "] are associated with it. Please remove the product mapping with [" . implode(",", $arr) . "] in order to delete the categories.");
            } else {
                CategoryUser::whereIn('category_id', $request->id)->delete();
                Category::whereIn('parent_id', $request->id)->delete();
                Category::whereIn('id', $request->id)->delete();
                return new DataTrueResource(true);
            }
        } else {
            return response()->json(['error' =>config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }
}
