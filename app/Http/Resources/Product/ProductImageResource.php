<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->get('is_light', false))
            return array_merge($this->attributesToArray(), $this->relationsToArray());

        return [

            'id' => $this->id,
            'product_id' => $this->product_id,
            'filename' => $this->filename,
        ];
    }
}
