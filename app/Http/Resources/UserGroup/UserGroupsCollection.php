<?php

namespace App\Http\Resources\UserGroup;

use App\Http\Resources\DataJsonResponse;
use Illuminate\Http\Request;

class UserGroupsCollection extends DataJsonResponse
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
