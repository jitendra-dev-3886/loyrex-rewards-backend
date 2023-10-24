<?php

namespace App\Http\Requests\Voucher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class VouchersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $todayDate = date('d-m-Y');

        $commonRule = [
            'name' => 'required | max:191',
            'contact_number' => 'nullable|regex:/^[6-9]\d{9}$/|digits:10',
            'description' => 'nullable|max:500',
            'voucher_type' => ['required', Rule::in([0, 1])],
            'status' => ['required', Rule::in([0, 1])],
            'no_of_vouchers' => 'nullable|integer|min:1|max:100',
            'category_id' => 'nullable|integer|exists:categories,id,deleted_at,NULL|required_if:voucher_type,0',
            "product_id" => "nullable|integer|required_if:voucher_type,0|exists:products,id,deleted_at,NULL",
            "valid_till" => "nullable|required_if:validity_status,0|date_format:d-m-Y|after_or_equal:" . $todayDate,
            "validity_status" => "nullable|required_if:valid_till,NULL",
            "points" => "nullable|integer|min:1|max:99999|required_if:voucher_type,1",
        ];

        if ($request->user_id == '0') {
            $commonRule['user_id'] = 'required|integer|min:0';
        } else {
            $commonRule['user_id'] = 'required|integer|exists:users,id,deleted_at,NULL,user_type,1';
        }
        return $commonRule;
    }
}
