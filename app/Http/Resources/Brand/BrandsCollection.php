<?php

namespace App\Http\Resources\Brand;

use Illuminate\Http\Request;
use App\Http\Resources\DataJsonResponse;

class BrandsCollection extends DataJsonResponse
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
