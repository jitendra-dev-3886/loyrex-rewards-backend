<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Product\ProductImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->get('is_light', false)) {
            $tableResponse = array_merge($this->attributesToArray(), $this->relationsToArray());
            $additionalResponse = [
                //  additional response will add here.
            ];
            return array_merge($tableResponse, $additionalResponse);
        }
        return [
            'id' => (string)$this->id,
            'name' => (string)$this->name,
            'category_id' => (string)$this->category_id,
            'category' => $this->category,
            'sub_category_id' => (string)$this->sub_category_id,
            'subcategory' => $this->subcategory,
            'brand_id' => (string)$this->brand_id,
            'no of catalogue' => $this->catalogues->count(),
            'catalogue_id' => $this->catalogues->pluck('id'),
            'catalogue_array' => $this->catalogues,
            'brand' => $this->brand,
            'featured_image' => (string)$this->featured_image,
            'upload_images' => ProductImageResource::collection($this->product_images),
            'description' => (string)$this->description,
            'point' => (string)$this->point,
            'available_status' => (string)$this->available_status,
            'available_status_text' => config('constants.products.available_status.' . $this->available_status),
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at
        ];
    }
}
