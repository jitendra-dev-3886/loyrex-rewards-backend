<?php

namespace App\Imports\Order;

use App\Models\Order\OrderStatus;
use App\Models\Order\Order;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;

class OrdersImport implements ToCollection, WithStartRow, WithHeadingRow
{
    use Scopes, CreatedbyUpdatedby;

    private $errors = [];
    private $rows = 0;

    public function startRow(): int
    {
        return 2; // fetch record from second row at import bulk order time.
    }

    public function getErrors()
    {
        return $this->errors; // return all errors
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required | integer | exists:orders,id,deleted_at,NULL',
            'delivery_partner' => 'nullable | max:191',
            'tracking_id' => 'nullable | max:191',
            'tracking_link' => 'nullable | max:255 | url',
            'status' => 'nullable | integer | in:0,1,2,3,4',
            'remark' => 'nullable',
        ];
    }

    public function validationMessages()
    {
        return [
            'order_id.required' => trans('Order id is required'),
            'order_id.integer' => trans('Order id should be integer'),
            'order_id.exists' => trans('Order id is not exists in orders'),

            'delivery_partner.max' => trans('Delivery partner contains only 191 characters'),

            'tracking_id.max' => trans('Tracking id contains only 191 characters'),

            'tracking_link.url' => trans('URL is invalid'),
            'tracking_link.max' => trans('URL contains only 255 characters'),

            'status.integer' => trans('Status should be integer'),
            'status.in' => trans('Status should be in [0, 1, 2, 3, 4]'),
        ];
    }

    public function validateBulk($collections) {
        $i=1;

        $keys = ['order_id', 'delivery_partner', 'tracking_id', 'tracking_link', 'status', 'remark'];

        foreach ($collections as $collection) {
            $i++;

            if (count(array_intersect_key(array_flip($keys), $collection->toArray())) !== count($keys)) {
                $this->errors[] = 'Invalid file format, Please download sample file.';
                break;
            }

            $validator = Validator::make($collection->toArray(), $this->rules(), $this->validationMessages());
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $messages) {
                    foreach ($messages as $error) {
                        $this->errors[] = $error.' on row '. $i;
                    }
                }
            }
        }

        return $this->getErrors();
    }

    public function collection(Collection $collections)
    {
        $error = $this->validateBulk($collections);
        if($error){
            return;
        } else {
            $order_columns = ['id', 'courier_name', 'tracking_id', 'courier_link', 'order_status', 'order_status_remark'];
            $keys = ['order_id', 'delivery_partner', 'tracking_id', 'tracking_link', 'status', 'remark'];
            foreach ($collections as $j => $collection) {
                $orders = [];
                $order_statuses = [];

                $orders[$order_columns[0]] = (string)$collection[$keys[0]];
                $orders[$order_columns[1]] = (string)$collection[$keys[1]];
                $orders[$order_columns[2]] = (string)$collection[$keys[2]];
                $orders[$order_columns[3]] = (string)$collection[$keys[3]];
                $orders[$order_columns[4]] = (string)$collection[$keys[4]];
                $orders[$order_columns[5]] = (string)$collection[$keys[5]];

                $orders['updated_by'] = Auth::guard('api')->user()->id;

                $order_cols = collect($orders)->filter()->all();

                $id = $order_cols['id'];
                unset($order_cols['id']);

                Order::find($id)->update($order_cols);

                $order_statuses['order_id'] = $id;
                $order_statuses['status'] = (string)$collection[$keys[4]];
                $order_statuses['remark'] = (string)$collection[$keys[5]];
                $order_statuses['created_by'] = Auth::guard('api')->user()->id;
                $order_statuses['updated_by'] = Auth::guard('api')->user()->id;
                $order_statuses_cols = collect($order_statuses)->filter()->all();

                OrderStatus::create($order_statuses_cols);

                $this->rows++;
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}

