<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($request->get('is_light',false)) {
            $tableResponse = array_merge($this->attributesToArray(), $this->relationsToArray());
            $additionalResponse = [
                //  additional response will add here.
            ];
            return array_merge($tableResponse, $additionalResponse);
        }
        if((int)$this->parent_id == 0) {
            // main category product count and products
            $no_of_items = $this->products->count();
            $products = $this->products;
        } else {
            // sub category product count and products
            $no_of_items = $this->subcategory_products->count();
            $products = $this->subcategory_products;
        }
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'step' => $this->step,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'no_of_items' => $no_of_items,
            'products' => $products,
            'parent_category' => $this->parent_categories,
        ];
    }
}
