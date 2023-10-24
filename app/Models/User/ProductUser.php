<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class ProductUser extends Model
{
    protected $table = 'product_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'user_id'
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
        'user_id' => 'string',
    ];
}
