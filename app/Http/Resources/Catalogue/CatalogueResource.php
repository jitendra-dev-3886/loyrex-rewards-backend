<?php

namespace App\Http\Resources\Catalogue;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
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
            'id' => $this->id,
            'name' => $this->name,
            'no_of_items' => $this->products->count(),
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,

        ];
    }
}
