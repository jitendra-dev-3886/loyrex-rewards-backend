<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductCatalogue extends Model
{
    protected $table = 'product_catalogue';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'catalogue_id'
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
        'product_id' => 'string',
        'catalogue_id' => 'string',
    ];
}
