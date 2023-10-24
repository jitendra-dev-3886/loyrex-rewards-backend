<?php

namespace App\Models\Voucher;

use Illuminate\Database\Eloquent\Model;

class ProductVoucher extends Model
{
    protected $table = 'product_voucher';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'voucher_id', 'product_id'
    ];

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
        'voucher_id'=>'string',
        'product_id'=>'string',
    ];
}
