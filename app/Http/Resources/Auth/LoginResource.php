<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'first_name' => (string)$this->first_name,
            'last_name' => (string)$this->last_name,
            'email' => (string) $this->email,
            'contact_number' => (string)$this->contact_number,
            'role_id' => (string)$this->role_id,
            'user_type' => (string)$this->user_type,
            'user_type_text' => (string) config('constants.user.user_type.' . $this->user_type),
            'status' => (string)$this->status,
            'status_text' => (string) config('constants.user.status.' . $this->status),
            'role' => $this->role,
            'permissions' => $this->permissions,
            'samples_excels' => array([
                'sample_admin_users' => config('constants.sample_dir_path.sample_admin_users'),
                'sample_products' => config('constants.sample_dir_path.sample_products'),
                'sample_user_users' => config('constants.sample_dir_path.sample_user_users'),
                'sample_orders' => config('constants.sample_dir_path.sample_orders'),
                'sample_vouchers' => config('constants.sample_dir_path.sample_vouchers'),
            ]),
            'authorization' => $this->authorization,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at
        ];
    }
}
