<?php

namespace App\Models\Voucher;

use App\Http\Resources\DataTrueResource;
use App\Http\Resources\Voucher\VouchersResource;
use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\User\User;
use App\Notifications\PinnacleEmailNotification;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\HtmlString;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;

class Voucher extends Model
{

    use Notifiable, Scopes, HasApiTokens, SoftDeletes, CreatedbyUpdatedby;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'category_id',
        'name',
        'description',
        'voucher_type',
        'no_of_vouchers',
        'points',
        'user_id',
        'contact_number',
        'link',
        'voucher_code',
        'reference_voucher_no',
        'status',
        'valid_till'
    ];

    public $sortable = [
        'id',
        'reference_voucher_no',
        'name',
        'points',
        'user_id',
        'valid_till'
    ];

    /**
     * @var array
     */
    public $foreign_sortable = [
        'category_id', 'user_id', 'product_id'
    ];

    /**
     * @var array
     */
    public $foreign_table = [
        'categories', 'users', 'products'
    ];

    /**
     * @var array
     */
    public $foreign_key = [
        'name',
        [
            'first_name',
            'last_name',
        ],
        'name'
    ];

    /**
     * @var array
     */
    public $foreign_method = [
        'category', 'user', 'products'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

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
        'name' => 'string',
        'category_id' => 'string',
        'user_id' => 'string',
        'description' => 'string',
        'points' => 'string',
        'voucher_type' => 'string',
        'no_of_vouchers' => 'string',
        'link' => 'string',
        'voucher_code' => 'string',
        'reference_voucher_no' => 'string',
        'status' => 'string',
        'valid_till' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, "product_voucher", "voucher_id", "product_id");
    }

    /**
     * Insert Voucher
     * @param $query
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeInsertVoucher($query, $request)
    {
        $data = $request->all();
        if (!is_null($request['no_of_vouchers']) && $request['no_of_vouchers'] != '') {
            $reference_voucher_no = Str::random(6);
            for ($i = 1; $i <= $request['no_of_vouchers']; $i++) {
                $data['voucher_code'] = substr(str_shuffle(Str::random(20)), 0, 10);
                $data['reference_voucher_no'] = $reference_voucher_no;
                $data['no_of_vouchers'] = "1";
                $data['valid_till'] = isset($data['valid_till']) ? Carbon::parse($data['valid_till'])->format('Y-m-d') : null;
                $voucher = Voucher::create($data);

                if ($request->get('voucher_type' == '0')) {
                    $link = config('constants.front_user_login_url') . '?voucher_code=' . $data['voucher_code'] . '&voucher_type=' . $request['voucher_type'] . '&product_id=' . $request['product_id'];
                } else {
                    $link = config('constants.front_user_login_url') . '?voucher_code=' . $data['voucher_code'] . '&voucher_type=' . $request['voucher_type'] . '&points=' . $request['points'];
                }
                $voucher->update(['link' => $link]);

                if (!empty($request['product_id'])) {
                    $voucher->products()->attach($request['product_id']);
                }
            }
            return response()->json(['data' => ['no_of_vouchers' => $request['no_of_vouchers'], 'reference_voucher_no' => $data['reference_voucher_no']]]);
        } else {
            $data['reference_voucher_no'] = Str::random(6);
            $data['voucher_code'] = substr(str_shuffle(Str::random(20)), 0, 10);
            $data['no_of_vouchers'] = 1;
            $data['valid_till'] = isset($data['valid_till']) ? Carbon::parse($data['valid_till'])->format('Y-m-d') : null;
            $voucher = Voucher::create($data);

            if ($request->get('voucher_type' == '0')) {
                $link = config('constants.front_user_login_url') . '?voucher_code=' . $data['voucher_code'] . '&voucher_type=' . $request['voucher_type'] . '&product_id=' . $request['product_id'];
            } else {
                $link = config('constants.front_user_login_url') . '?voucher_code=' . $data['voucher_code'] . '&voucher_type=' . $request['voucher_type'] . '&points=' . $request['points'];
            }
            $voucher->update(['link' => $link]);

            if (!empty($request['product_id'])) {
                $voucher->products()->attach($request['product_id']);
            }

            /* Send Mail */
            if ($request['user_id'] != "0") {

                $user = User::where('id', $request['user_id'])->first();
                $email = $user->email;
                $subject = 'Your voucher code is here!';
                $template = 'Voucher.SendVoucherDetails';

                if (!is_null($voucher->valid_till)) {
                    if ($voucher->voucher_type == config('constants.vouchers.voucher_type_code.points')) {
                        $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->points . ' points. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it before ' . $voucher->valid_till . '. You can now visit ' . $voucher['link'] . ' to redeem.');
                    } else {
                        $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->products()->pluck('name')[0] . ' product. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it before ' . $voucher->valid_till . '. You can now visit ' . $voucher['link'] . ' to redeem.');
                    }
                } else {
                    if ($voucher->voucher_type == config('constants.vouchers.voucher_type_code.points')) {
                        $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->points . ' points. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it soon. You can now visit ' . $voucher['link'] . ' to redeem.');
                    } else {
                        $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->products()->pluck('name')[0] . ' product. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it soon. You can now visit ' . $voucher['link'] . ' to redeem.');
                    }
                }

                $user->notify(new PinnacleEmailNotification($email, $subject, $customerMailText, $user, $template, $type = '2', null)); // Email to User
            }

            return response()->json(['data' => ['no_of_vouchers' => $data['no_of_vouchers'], 'reference_voucher_no' => $data['reference_voucher_no']]]);
        }
    }

    /**
     * update Voucher
     * @param $query
     * @param $request
     * @param $voucher
     * @return VouchersResource
     */
    public function scopeUpdateVoucher($query, $request, $voucher)
    {
        $data = $request->all();
        if (!is_null($request['no_of_vouchers']) && $request['no_of_vouchers'] != '') {
            $reference_voucher_no = Str::random(6);
            for ($i = 1; $i <= $request['no_of_vouchers']; $i++) {
                $data['voucher_code'] = substr(str_shuffle(Str::random(20)), 0, 10);
                $data['reference_voucher_no'] = $reference_voucher_no;
                $data['no_of_vouchers'] = "1";
                $data['valid_till'] = isset($data['valid_till']) ? Carbon::parse($data['valid_till'])->format('Y-m-d') : null;
                $voucher->update($data);

                if ($request->get('voucher_type' == '0')) {
                    $link = config('constants.front_user_login_url') . '?voucher_code=' . $data['voucher_code'] . '&voucher_type=' . $request['voucher_type'] . '&product_id=' . $request['product_id'];
                } else {
                    $link = config('constants.front_user_login_url') . '?voucher_code=' . $data['voucher_code'] . '&voucher_type=' . $request['voucher_type'] . '&points=' . $request['points'];
                }
                $voucher->update(['link' => $link]);

                if (!empty($request['product_id'])) {
                    $voucher->products()->detach();
                    $voucher->products()->attach($request['product_id']);
                }
            }
            return response()->json(['data' => ['no_of_vouchers' => $request['no_of_vouchers'], 'reference_voucher_no' => $data['reference_voucher_no']]]);
        } else {
            $data['reference_voucher_no'] = Str::random(6);
            $data['voucher_code'] = substr(str_shuffle(Str::random(20)), 0, 10);
            $data['no_of_vouchers'] = 1;
            $data['valid_till'] = isset($data['valid_till']) ? Carbon::parse($data['valid_till'])->format('Y-m-d') : null;
            $voucher->update($data);

            if ($request->get('voucher_type' == '0')) {
                $link = config('constants.front_user_login_url') . '?voucher_code=' . $data['voucher_code'] . '&voucher_type=' . $request['voucher_type'] . '&product_id=' . $request['product_id'];
            } else {
                $link = config('constants.front_user_login_url') . '?voucher_code=' . $data['voucher_code'] . '&voucher_type=' . $request['voucher_type'] . '&points=' . $request['points'];
            }
            $voucher->update(['link' => $link]);

            if (!empty($request['product_id'])) {
                $voucher->products()->detach();
                $voucher->products()->attach($request['product_id']);
            }

            /* Send Mail */
            if ($request['user_id'] != "0") {

                $user = User::where('id', $request['user_id'])->first();
                $email = $user->email;
                $subject = 'Your voucher code is here!';
                $template = 'Voucher.SendVoucherDetails';

                if (!is_null($voucher->valid_till)) {
                    if ($voucher->voucher_type == config('constants.vouchers.voucher_type_code.points')) {
                        $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->points . ' points. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it before ' . $voucher->valid_till . '. You can now visit ' . $voucher['link'] . ' to redeem.');
                    } else {
                        $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->products()->pluck('name')[0] . ' product. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it before ' . $voucher->valid_till . '. You can now visit ' . $voucher['link'] . ' to redeem.');
                    }
                } else {
                    if ($voucher->voucher_type == config('constants.vouchers.voucher_type_code.points')) {
                        $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->points . ' points. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it soon. You can now visit ' . $voucher['link'] . ' to redeem.');
                    } else {
                        $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->products()->pluck('name')[0] . ' product. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it soon. You can now visit ' . $voucher['link'] . ' to redeem.');
                    }
                }
                $user->notify(new PinnacleEmailNotification($email, $subject, $customerMailText, $user, $template, $type = '2', null)); // Email to User
            }

            return response()->json(['data' => ['no_of_vouchers' => $data['no_of_vouchers'], 'reference_voucher_no' => $data['reference_voucher_no']]]);
        }
    }

    /**
     * Update Voucher status
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeUpdateStatus($query, $request)
    {

        $mainVoucher = Voucher::where('id', $request->id)->first();
        if ($mainVoucher) {
            Voucher::where('id', $request->id)->update(array('status' => $request->status));
            return new DataTrueResource(true);
        } else {
            return response()->json(['error' => config('constants.messages.voucher_not_found')], config('constants.validation_codes.unprocessable_entity'));
        }
    }
}
