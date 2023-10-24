<?php

namespace App\Http\Resources\Catalogue;

use App\Http\Resources\DataJsonResponse;

class CatalogueCollection extends DataJsonResponse
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
