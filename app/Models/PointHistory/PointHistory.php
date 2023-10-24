<?php

namespace App\Models\PointHistory;

use App\Models\User\User;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class PointHistory extends Model
{
    use Notifiable, Scopes, HasApiTokens, SoftDeletes;

    protected $table = 'point_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'action_type',
        'points',
        'description',
        'order_id',
        'created_by',
        'updated_by'
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
        'user_id' => 'string',
        'action_type' => 'string',
        'points' => 'string',
        'description' => 'string',
        'order_id' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];
}
