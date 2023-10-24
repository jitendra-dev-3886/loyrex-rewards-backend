<?php

namespace App\Http\Resources\User;

use App\Http\Resources\DataJsonResponse;
use Illuminate\Http\Request;

class UsersCollection extends DataJsonResponse
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
