<?php

namespace App\Models\Order;

use App\Models\User\User;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Order extends Model
{
    use Notifiable, Scopes, HasApiTokens, SoftDeletes, CreatedbyUpdatedby;

    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'quantity',
        'total_points',
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'address',
        'state',
        'city',
        'pin_code',
        'order_status',
        'order_status_remark',
        'user_remark',
        'redeemed_points',
        'payment_amount',
        'courier_name',
        'tracking_id',
        'courier_link',
        'email',
        'payment_method',
        'payment_mode',
        'payment_transaction_id',
        'created_by',
        'updated_by'
    ];

    public $combined = [
        [
            'first_name',
            'last_name',
        ]
    ];

    /**
     * @var array
     */
    public $sortable = [
        'id',
        'quantity',
        'first_name',
        'total_points',
    ];



    /**
     * @var array
     */
    //    public $foreign_sortable = [
    //        'user_id'
    //    ];

    /**
     * @var array
     */
    //    public $foreign_table = [
    //        'users'
    //    ];

    /**
     * @var array
     */
    //    public $foreign_key = [
    //        'first_name'
    //    ];

    /**
     * @var array
     */
    //    public $foreign_method = [
    //        'user'
    //    ];

    public $type_sortable = [
        'order_status'
    ];

    public $type_enum = [
        [
            'constants.order.status_text.pending',
            'constants.order.status_text.inprocess',
            'constants.order.status_text.shipped',
            'constants.order.status_text.delivered',
            'constants.order.status_text.cancel',
            'constants.order.status_text.embedded_order',
        ]
    ];
    public $type_enum_text = [
        [
            'constants.order.status.0',
            'constants.order.status.1',
            'constants.order.status.2',
            'constants.order.status.3',
            'constants.order.status.4',
            'constants.order.status.99',
        ],
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_by', 'updated_by', 'deleted_at'
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
        'id' => 'string',
        'user_id' => 'string',
        'quantity' => 'string',
        'total_points' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'contact_number' => 'string',
        'address' => 'string',
        'state' => 'string',
        'city' => 'string',
        'pin_code' => 'string',
        'order_status' => 'string',
        'order_status_remark' => 'string',
        'user_remark' => 'string',
        'redeemed_points' => 'string',
        'payment_amount' => 'string',
        'courier_name' => 'string',
        'tracking_id' => 'string',
        'courier_link' => 'string',
        'payment_method' => 'string',
        'payment_mode' => 'string',
        'payment_transaction_id' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function products()
    {
        return $this->hasMany(OrderProducts::class)->select([
            'order_id',
            'product_id',
            'product_name',
            'category_name',
            'featured_image',
            'product_points',
            'quantity',
            'points',
            'total_points'
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order_product()
    {
        return $this->belongsTo(OrderProducts::class);
    }
}
