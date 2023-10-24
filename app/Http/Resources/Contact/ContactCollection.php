<?php

namespace App\Http\Resources\Contact;

use App\Http\Resources\DataJsonResponse;
use Illuminate\Http\Request;

class ContactCollection extends DataJsonResponse
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
