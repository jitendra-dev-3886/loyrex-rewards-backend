<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\DataJsonResponse;

class UsersCollection extends DataJsonResponse
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
