<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'quantity' => $this->quantity,
            'total_points' => $this->total_points,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'state' => $this->state,
            'city' => $this->city,
            'pin_code' => $this->pin_code,
            'order_status' => $this->order_status,
            'order_status_text' => config('constants.order.status.'.$this->order_status),
            'order_status_remark' => $this->order_status_remark,
            'user_remark' => $this->user_remark,
            'redeemed_points' => $this->redeemed_points,
            'payment_amount' => $this->payment_amount,
            'courier_name' => $this->courier_name,
            'tracking_id' => $this->tracking_id,
            'courier_link' => $this->courier_link,
            'payment_method' => $this->payment_method,
            'payment_method_text' => config('constants.order.payment_method.'.$this->payment_method),
            'payment_mode' => $this->payment_mode,
            'payment_mode_text' => config('constants.order.payment_mode.'.$this->payment_mode),
            'payment_transaction_id' => $this->payment_transaction_id,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'products' => $this->products,
        ];
    }
}
