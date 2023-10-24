<?php

namespace App;

use App\Http\Resources\DataTrueResource;
use App\Http\Resources\Admin\UsersResource;
use App\Models\Auth\Role;
use App\Notifications\PinnacleEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Scopes;
use Auth;
use Laravel\Passport\HasApiTokens;
use App\Scopes\Admin\AdminUserTypeScope;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Scopes, HasApiTokens, SoftDeletes;

    protected $table = 'users';
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AdminUserTypeScope);
    }

    public function scopeCommonFunctionMethod($query, $model, $request, $preQuery = null, $tablename = null, $groupBy = null, $export_select = false, $no_paginate = false)
    {
        return $this->getCommonFunctionMethod($model, $request, $preQuery, $tablename, $groupBy, $export_select, $no_paginate);
    }

    public static function getCommonFunctionMethod($model, $request, $preQuery = null, $tablename = null, $groupBy = null, $export_select = false, $no_paginate = false)
    {
        if (is_null($preQuery)) {
            $mainQuery = $model::withSearch($request->get('search'), $export_select);
        } else {
            $mainQuery = $model->withSearch($request->get('search'), $export_select);
        }
        if ($request->filled('filter') != '')
            $mainQuery = $mainQuery->withFilter($request->get('filter'));
        if (!is_null($groupBy))
            $mainQuery = $mainQuery->groupBy($groupBy);
        if ($no_paginate) {
            return $mainQuery->withOrderBy($request->get('sort'), $request->get('order_by'), $tablename, $export_select, $groupBy);
        } else {

            $mainQuery = $mainQuery->withOrderBy($request->get('sort'), $request->get('order_by'), $tablename, $export_select, $groupBy);

            if ($request->filled('per_page'))
                return $mainQuery->withPerPage($request->get('per_page'));
            else
                return $mainQuery->withPerPage($mainQuery->get()->count());
        }
    }

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
        'first_name',
        'contact_number',
        'email',
    ];

    public $foreign_sortable = [
        'role_id',
    ];

    public $foreign_table = [
        'roles',
    ];

    public $foreign_key = [
        'name',
    ];

    public $foreign_method = [
        'role',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'job_title',
        'email',
        'password',
        'contact_number',
        'reward_points',
        'user_type',
        'verification_otp',
        'role_id',
        'status',
        'varification_link_token'
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
        'password', 'varification_link_token', 'deleted_at', 'created_by', 'updated_by'
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
        'job_title' => 'string',
        'email' => 'string',
        'contact_number' => 'string',
        'reward_points' => 'string',
        'user_type' => 'string',
        'verification_otp' => 'string',
        'role_id' => 'string',
        'status' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function scopeRegister($query, $request)
    {
        $data = $request->all();
        $users = Auth::user();
        $data['password'] = bcrypt($data['password']);
        $data['user_type'] = config('constants.user.user_type_code.admin');
        $data['status'] = config('constants.user.status_code.active');
        $user = User::create($data);
        $user->created_by = $users->id;
        $user->updated_by = $users->id;
        $user->save();

        $subject = 'Your new Loyrex account is ready!';
        $email = $user->email;
        $template = 'User.UserRegister';
        $customerMailText = 'Hi ' . $user->first_name . ', Congratulations! Your registration on the Loyrex website has been taken into account. You can now log in to the account and place your order. Click here ' . env('APP_URL') . ' to login.';

        $user->notify(new PinnacleEmailNotification($email, $subject, $customerMailText, $user, $template, $type = '2', null)); // Email to User

        return new UsersResource($user);
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query, $request)
    {
        if (!empty($request->id)) {
            if (($key = array_search(config('constants.system_user_id'), $request->id)) !== false) {
                return User::GetError(config('constants.messages.admin_user_delete_error'));
            }
            User::whereIn('id', $request->id)->delete();
            return new DataTrueResource(true);
        } else {
            return response()->json(['error' => config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }

    /**
     * update User
     * @param $query
     * @param $request
     * @param $user
     * @return UsersResource
     */
    public function scopeUpdateUser($query, $request, $user)
    {
        $data = $request->all();
        $users = Auth::user();
        $data['updated_by'] = $users->id;
        if ($data['password'] != null) {
            $data['password'] = bcrypt($data['password']);
        } else {
            $data['password'] = $users->password;
        }
        $user->update($data);
        return new UsersResource($user);
    }

    /**
     * Common Display Error Message.
     *
     * @param $query
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function GetError($message)
    {
        return response()->json(['error' => $message], config('constants.validation_codes.unprocessable_entity'));
    }
}
