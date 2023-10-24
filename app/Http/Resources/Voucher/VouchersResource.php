<?php

namespace App\Http\Resources\Voucher;

use App\Models\Voucher\ProductVoucher;
use Illuminate\Http\Resources\Json\JsonResource;

class VouchersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $ProductVoucher = ProductVoucher::where('voucher_id', $this->id)->first();
        $productId = '';
        if (!is_null($ProductVoucher)) {
            $productId = $ProductVoucher->product_id;
        }
        return [
            'id' => (string)$this->id,
            'name' => (string)$this->name,
            'description' => (string)$this->description,
            'voucher_type' => (string)$this->voucher_type,
            'voucher_type_text' => config('constants.vouchers.voucher_type.' . $this->voucher_type),
            'no_of_vouchers' => (string)$this->no_of_vouchers,
            'points' => (string)$this->points,
            'category_id' => (string)$this->category_id,
            'category' => $this->category,
            'user_id' => (string)$this->user_id,
            'contact_number' => (string)$this->contact_number,
            'reference_voucher_no' => (string)$this->reference_voucher_no,
            'link' => $this->link,
            'voucher_code' => $this->voucher_code,
            'user' => $this->user,
            'product_id' => (string)$productId,
            'product' => $this->products,
            'voucher_redeem' => (string)$this->voucher_redeem,
            'voucher_redeem_text' =>  config('constants.vouchers.voucher_redeem.' . $this->voucher_redeem),
            'status' => (string)$this->status,
            'status_text' =>  config('constants.vouchers.status.' . $this->status),
            'valid_till' => (string)$this->valid_till,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at
        ];
    }
}
