<?php

namespace App\Exports\Order;

use App\Models\Order\Order;
use App\Models\User\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithMapping, WithHeadings
{
    protected $request; // defined private $request variable

    public function __construct($request) // constructor method
    {
        $this->request = $request; // assign $request $this variable
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if($this->request->sort == "" && $this->request->order_by == "") {
            return  User::commonFunctionMethod(Order::with('products')->latest()->orderBy('id', 'desc'), $this->request, true, null, null, true);
        } else {
            return  User::commonFunctionMethod(Order::with('products'), $this->request, true, null, null, true);
        }
    }

    public function map($model) : array {
        $row = [];
        foreach ($model->products as $key => $products) {
            $row[$key]['id'] = $model->id;
            $row[$key]['products'] = $products->product_name;
            $row[$key]['categories'] = $products->category_name;
            $row[$key]['customer_detail'] = $model->first_name . ' ' . $model->last_name . ', ' . $model->email . ', ' . $model->contact_number;
            $row[$key]['address'] = $model->address . ', '. $model->state . ', ' . $model->city . ', ' . $model->pin_code;
            $row[$key]['quantity'] = $model->quantity;
            $row[$key]['total_points'] = number_format($model->total_points);
            $row[$key]['redeemed_points'] = number_format($model->redeemed_points);
            $row[$key]['payment_amount'] = number_format($model->payment_amount);
            $row[$key]['reference_id'] = $model->payment_transaction_id;
            $row[$key]['payment_mode'] = config('constants.order.payment_mode.'.$model->payment_mode);
            $row[$key]['status'] = config('constants.order.status.'.$model->order_status);
            $row[$key]['courier_name'] = $model->courier_name;
            $row[$key]['tracking_id'] = $model->tracking_id;
            $row[$key]['courier_link'] = $model->courier_link;
        }
        return $row;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Items',
            'Category',
            'Customer detail',
            'Address',
            'Qty',
            'Total points',
            'Redeemed points',
            'PG amount',
            'Reference ID',
            'Payment mode',
            'Status',
            'Delivery partners',
            'Tracking ID',
            'Courier link'
        ];
    }
}
