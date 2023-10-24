<?php

namespace App\Http\Resources\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            'email' =>(string) $this->email,
            'contact_number' => (string)$this->contact_number,
            'role_id' => (string)$this->role_id,
            'user_type' => (string)$this->user_type,
            'user_type_text' => (string) config('constants.user.user_type.'.$this->user_type),
            'status' => (string)$this->status,
            'status_text' => (string) config('constants.user.status.'.$this->status),
            'role' => $this->role,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at
        ];
    }
}
