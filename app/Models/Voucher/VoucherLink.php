<?php
namespace App\Models\Voucher;

use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class VoucherLink extends Model
{
    use Notifiable, Scopes, HasApiTokens, SoftDeletes, CreatedbyUpdatedby;
    /**
     * @var array
     */

    public $table = 'voucher_links';
    protected $fillable = ['voucher_id', 'link', 'voucher_code'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucherLinks()
    {
        return $this->belongsTo('App\Voucher\Voucher');
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
        'id'=>'string',
        'voucher_id'=>'string',
        'link'=>'string',
        'voucher_code'=>'string',
        'created_at'=>'string',
        'updated_at'=>'string',
    ];
}
