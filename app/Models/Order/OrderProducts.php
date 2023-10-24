<?php

namespace App\Models\Order;

use App\Models\Product\Product;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class OrderProducts extends Model
{
    use Notifiable, Scopes, HasApiTokens, SoftDeletes, CreatedbyUpdatedby, UploadTrait;

    protected $table = 'order_products';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products() {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orders() {
        return $this->belongsTo(Order::class);
    }

    /**
     * @param $value
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getFeaturedImageAttribute($value) {
        if ($this->is_file_exists($value) || (config('s3.filesystem_driver') == 's3' && !is_null($value)))
            return $value;
        else
            return asset(config('constants.image.default_img'));
    }
}
