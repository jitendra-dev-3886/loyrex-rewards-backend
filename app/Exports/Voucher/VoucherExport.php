<?php

namespace App\Exports\Voucher;

use App\Models\Voucher\Voucher;
use App\Models\User\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class VoucherExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if($this->request->sort == "" && $this->request->order_by == "") {
            return  User::commonFunctionMethod(Voucher::select('vouchers.id', 'vouchers.reference_voucher_no', 'vouchers.name',
                DB::raw('(
                CASE WHEN voucher_type = "' . config('constants.vouchers.voucher_type_code.product') . '" THEN
                    "' . config('constants.vouchers.voucher_type.0').'"
                ELSE
                    "'.config('constants.vouchers.voucher_type.1').'"
                END) AS voucher_type'),
                DB::raw('(
                CASE WHEN voucher_type = "' . config('constants.vouchers.voucher_type_code.points') . '" THEN
                   FORMAT(vouchers.points, 0)
                ELSE
                    '.  DB::raw('(SELECT GROUP_CONCAT(DISTINCT (SELECT products.name FROM products WHERE products.id = product_voucher.product_id)) from product_voucher WHERE product_voucher.voucher_id = vouchers.id)') .'
                END) AS points_product'),
                DB::raw('(
                CASE WHEN vouchers.user_id = "0" THEN
                    "N/A"
                ELSE
                    '.  DB::raw('(SELECT CONCAT(users.first_name, " ", users.last_name) from users WHERE users.id = vouchers.user_id)') .'
                END) AS user_name'),
                'no_of_vouchers',
                DB::raw('CONCAT("Valid till ", DATE_FORMAT(vouchers.valid_till, "%d/%m/%Y"))'),
                DB::raw('(SELECT categories.name from categories WHERE categories.id = vouchers.category_id) AS category_name'),
                'description',
                DB::raw('(
                CASE WHEN vouchers.status = "' . config('constants.vouchers.status_code.deactive') . '" THEN
                    "' . config('constants.vouchers.status.0').'"
                ELSE
                    "'.config('constants.vouchers.status.1').'"  END) AS status'),
                'vouchers.voucher_code',  'vouchers.link',
                DB::raw('(
                CASE WHEN vouchers.voucher_redeem = "' . config('constants.vouchers.voucher_redeem_text.no') . '" THEN
                    "' . config('constants.vouchers.voucher_redeem.0').'"
                ELSE
                    "'.config('constants.vouchers.voucher_redeem.1').'"  END) AS voucher_redeem'),
            )->latest()->orderBy('vouchers.id', 'desc'), $this->request, true, null, null, true);
        } else {
            return  User::commonFunctionMethod(Voucher::select('vouchers.id', 'vouchers.reference_voucher_no', 'vouchers.name',
                DB::raw('(
                CASE WHEN voucher_type = "' . config('constants.vouchers.voucher_type_code.product') . '" THEN
                    "' . config('constants.vouchers.voucher_type.0').'"
                ELSE
                    "'.config('constants.vouchers.voucher_type.1').'"
                END) AS voucher_type'),
                DB::raw('(
                CASE WHEN voucher_type = "' . config('constants.vouchers.voucher_type_code.points') . '" THEN
                   FORMAT(vouchers.points, 0)
                ELSE
                    '.  DB::raw('(SELECT GROUP_CONCAT(DISTINCT (SELECT products.name FROM products WHERE products.id = product_voucher.product_id)) from product_voucher WHERE product_voucher.voucher_id = vouchers.id)') .'
                END) AS points_product'),
                DB::raw('(
                CASE WHEN vouchers.user_id = "0" THEN
                    "N/A"
                ELSE
                    '.  DB::raw('(SELECT CONCAT(users.first_name, " ", users.last_name) from users WHERE users.id = vouchers.user_id)') .'
                END) AS user_name'),
                'no_of_vouchers',
                DB::raw('CONCAT("Valid till ", DATE_FORMAT(vouchers.valid_till, "%d/%m/%Y"))'),
                DB::raw('(SELECT categories.name from categories WHERE categories.id = vouchers.category_id) AS category_name'),
                'description',
                DB::raw('(
                CASE WHEN vouchers.status = "' . config('constants.vouchers.status_code.deactive') . '" THEN
                    "' . config('constants.vouchers.status.0').'"
                ELSE
                    "'.config('constants.vouchers.status.1').'"  END) AS status'),
                'vouchers.voucher_code',  'vouchers.link',
                DB::raw('(
                CASE WHEN vouchers.voucher_redeem = "' . config('constants.vouchers.voucher_redeem_text.no') . '" THEN
                    "' . config('constants.vouchers.voucher_redeem.0').'"
                ELSE
                    "'.config('constants.vouchers.voucher_redeem.1').'"  END) AS voucher_redeem'),
            ), $this->request, true, null, null, true);
        }
    }

    public function headings():array
    {
        return[
            'Voucher ID',
            'Reference Voucher No.',
            'Voucher name',
            'Voucher type',
            'Points/Products',
            'User',
            'No of voucher',
            'Validity',
            'Category name',
            'Description',
            'Voucher status',
            'Voucher code',
            'Voucher link',
            'Voucher Redeem'
        ];
    }
}
