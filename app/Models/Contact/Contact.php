<?php

namespace App\Models\Contact;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Contact extends Model
{
    use Notifiable, Scopes, HasApiTokens, SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'first_name', 'last_name', 'email', 'subject', 'message'
    ];

    /**
     * @var string[]
     */
    public $sortable = [
        'id', 'first_name', 'last_name', 'email', 'subject', 'message'
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
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
        'id' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'subject' => 'string',
        'email' => 'string',
        'message' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

}
