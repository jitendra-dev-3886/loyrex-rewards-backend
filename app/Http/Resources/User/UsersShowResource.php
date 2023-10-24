<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'job_title' => $this->job_title,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'reward_points' => $this->reward_points,
            'user_type' => $this->user_type,
            'user_type_text' => config('constants.user.user_type.'.$this->user_type),
            'status' => $this->status,
            'status_text' => config('constants.user.status.'.$this->status),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'no_of_userGroup' => $this->userGroup->count(),
            'userGroup_id' => $this->userGroup()->pluck('id'),
            'userGroup_array' => $this->userGroup,
        ];
    }
}
