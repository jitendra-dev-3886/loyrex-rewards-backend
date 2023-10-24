<?php

namespace App\Models\Product;

use App\Traits\CreatedbyUpdatedby;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use SoftDeletes, CreatedbyUpdatedby, UploadTrait;
    /**
     * @var array
     */

    public $table = 'product_images';
    protected $fillable = ['product_id', 'filename'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productImages()
    {
        return $this->belongsTo('App\product');
    }

    /**
     * @param $value
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getFilenameAttribute($value)
    {
        if ($this->is_file_exists($value) || (config('s3.filesystem_driver') == 's3' && !is_null($value)))
            return \Illuminate\Support\Facades\Storage::url($value);
        else
            return asset(config('constants.image.product_default_img'));
    }

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
        'id' => 'string',
        'product_id' => 'string',
        'filename' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];
}
