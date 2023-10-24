<?php

namespace App\Models\User;

use App\Http\Resources\User\UsersResource;
use App\Models\Auth\Role;
use App\Models\Category\Category;
use App\Models\PointHistory\PointHistory;
use App\Models\Product\Product;
use App\Models\UserGroup\UserGroup;
use App\Notifications\PinnacleEmailNotification;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use App\Scopes\User\UserTypeScope;


class User extends Model
{
    use Notifiable, Scopes, HasApiTokens, SoftDeletes;

    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserTypeScope);
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
            return $mainQuery->withOrderBy($request->get('sort'), $request->get('order_by'), $tablename, $export_select);
        } else {
            $mainQuery = $mainQuery->withOrderBy($request->get('sort'), $request->get('order_by'), $tablename, $export_select);
            if ($request->filled('per_page'))
                return $mainQuery->withPerPage($request->get('per_page'), $export_select);
            else
                return $mainQuery->withPerPage($mainQuery->count(), $export_select);
        }
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
        'varification_link_token',
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
        'first_name',
        'job_title',
        'email',
        'contact_number',
        'reward_points',
    ];

    public $foreign_sortable = [
        'country_id', 'role_id', 'userGroup_id',
    ];

    public $foreign_table = [
        'countries', 'roles', 'user_groups'
    ];

    public $foreign_key = [
        'name', 'name', 'name',
    ];

    public $foreign_method = [
        'categories', 'role', 'userGroup',
    ];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = [
        'id', 'first_name', 'last_name', 'contact_number', 'email'
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
        'password', 'varification_link_token'
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
        'created_by' => 'string',
        'updated_by' => 'string',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userGroup()
    {
        return $this->belongsToMany(UserGroup::class, "user_group_user", "user_id", "userGroup_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, "category_user", "user_id", "category_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, "product_user", "user_id", "product_id");
    }


    /**
     * Create User 
     *
     * @param  mixed $query
     * @param  mixed $request
     * @return void
     */
    public function scopeCreateUser($query, $request)
    {
        $users = Auth::user();
        $data = $request->all();

        $data['password'] = bcrypt($data['password']);
        $data['user_type'] = config('constants.user.user_type_code.user');
        $data['status'] = config('constants.user.status_code.active');
        $data['created_by'] = $users->id;
        $data['updated_by'] = $users->id;

        $user = User::create($data);

        if ($request['userGroup_id']) {
            //this executes the insert-query
            $user->userGroup()->attach($request['userGroup_id']);
        }

        if ($request['reward_points'] > 0) {
            PointHistory::create([
                'user_id' => $user->id,
                'action_type' => config('constants.user.action_type.credit'),
                'points' => $request['reward_points'],
                'description' => config('constants.user.action_type_text.1'),
                'created_by' => $users->id,
                'updated_by' => $users->id,
            ]);

            $subject = 'Your new Loyrex account is ready!';
            $email = $user->email;
            $template = 'User.UserRegister';
            $customerMailText = 'Hi ' . $user->first_name . ', Congratulations! Your registration on the Loyrex website has been taken into account. You can now log in to the account and place your order. Click here ' . config('constants.front_user_login_url') . ' to login.';

            $user->notify(new PinnacleEmailNotification($email, $subject, $customerMailText, $user, $template, $type = '2', null)); // Email to User
        }

        return new UsersResource($user);
    }

    /**
     * Update User
     *
     * @param  mixed $query
     * @param  mixed $request
     * @param  mixed $user
     * @return void
     */
    public function scopeUpdateUser($query, $request, $user)
    {
        $data = $request->all();
        if ($data['action_type'] == config('constants.user.action_type.debit')) {
            if ($data['reward_points'] > $user['reward_points']) {
                return response()->json(['error' => 'Old reward point [' . $data['reward_points'] . '] should be less than new reward point [' . $user->reward_points . ']']);
            }
        }
        $users = Auth::user();

        if (isset($data['action_type'])) {
            $data['reward_points'] = ($data['action_type'] == 0) ? $user['reward_points'] - $data['reward_points'] : $user['reward_points'] + $data['reward_points'];
        }
        $data['status'] = config('constants.user.status_code.active');
        $data['updated_by'] = $users->id;

        $user->update($data);

        $user->userGroup()->detach();
        //this executes the insert-query
        $user->userGroup()->attach($data['userGroup_id']);

        if (isset($data['action_type'])) {
            PointHistory::create([
                'user_id' => $user->id,
                'action_type' => $data['action_type'],
                'points' => $data['reward_points'],
                'description' => config('constants.user.action_type_text.' . $data['action_type']),
                'created_by' => $users->id,
                'updated_by' => $users->id,
            ]);
        }
        return new UsersResource($user);
    }
}
