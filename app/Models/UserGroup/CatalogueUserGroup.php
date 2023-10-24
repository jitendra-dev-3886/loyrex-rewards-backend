<?php

namespace App\Models\UserGroup;

use Illuminate\Database\Eloquent\Model;

class CatalogueUserGroup extends Model
{
    protected $table = 'catalogue_user_group';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userGroup_id', 'catalogue_id'
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
        'userGroup_id' => 'string',
        'catalogue_id' => 'string',
    ];
}
