<?php

namespace App\Models\Dashboard;

use App\Http\Resources\DataTrueResource;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\User\ActivityLog;
use App\Models\User\User;
use App\Models\Voucher\Voucher;
use App\Traits\Scopes;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class Dashboard extends Model
{
    use Notifiable, Scopes, HasApiTokens;

    /**
     * This method is used to get All Dashboard listing
     * @param
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public static function getAllData(){

        /* Products */
        $products = [];
        // get total products from products table
        $products['total_products'] = (string)Product::all()->count();
        // get total available products from products table
        $products['total_available_products'] = (string)Product::where('available_status',config('constants.products.available_status_code.available'))->count();
        // get total out of stock products from products table
        $products['total_out_of_stock_products'] = (string)Product::where('available_status',config('constants.products.available_status_code.not_available'))->count();

        /*Orders*/
        $orders = [];
        // get total orders from orders table
        $orders['total_orders'] = (string)Order::all()->count();
        // get total Pending orders from orders table
        $orders['total_pending_orders'] = (string)Order::where('order_status',config('constants.order.status_text.pending'))->count();
        // get total Delivered orders from orders table
        $orders['total_delivered_orders'] = (string)Order::where('order_status',config('constants.order.status_text.delivered'))->count();
        // get last 5 orders from orders table
        $listorders = Order::latest()->limit(5)->get();
        $order_data_array = [];
        foreach ($listorders as $list_orders){
            $order_data_array[] = [
                'id' => $list_orders->id,
                'first_name' => $list_orders->first_name,
                'last_name' => $list_orders->last_name,
                'total_points' => $list_orders->total_points,
                'order_status' => $list_orders->order_status,
                'order_status_text' => config('constants.order.status.'.$list_orders->order_status),
                'created_at' => (string)$list_orders->created_at,
            ];
        }
        $orders['last_orders'] = $order_data_array;

        // Orders Status Pie Chart (Current Month)
        $loyaltyConstant = 'constants.order.status';
        $orderstartDate = config('constants.calender.start_Of_month');
        $start_Of_month = explode(" ",$orderstartDate->toDateTimeString());
        $order_start_Date =$start_Of_month[0];
        $order_end_Date =config('constants.calender.date_format');
        $orders['orders_pie_chart'] = [
            'label' => [
                config($loyaltyConstant . '.0'),
                config($loyaltyConstant . '.1'),
                config($loyaltyConstant . '.2'),
                config($loyaltyConstant . '.3'),
                config($loyaltyConstant . '.4'),
            ],
            'data' => [
                Dashboard::getOrderStatusByCount($order_start_Date,$order_end_Date,config('constants.order.status_text.pending')),
                Dashboard::getOrderStatusByCount($order_start_Date,$order_end_Date,config('constants.order.status_text.inprocess')),
                Dashboard::getOrderStatusByCount($order_start_Date,$order_end_Date,config('constants.order.status_text.shipped')),
                Dashboard::getOrderStatusByCount($order_start_Date,$order_end_Date,config('constants.order.status_text.delivered')),
                Dashboard::getOrderStatusByCount($order_start_Date,$order_end_Date,config('constants.order.status_text.cancel')),
            ],
        ];

        /*Users*/
        $users = [];
        // get total front-end users from users table
        $users['total_users'] = (string)User::all()->count();

        // Users Activity chart (Last One Year)
        $startDate = config('constants.calender.last_year_date');
        $endDate = new DateTime();
        $month = Dashboard::getMonthListFromDate($startDate,$endDate);
        $usersCount = Dashboard::getUsersCountFromActivityLog($month);
        $users['users_activity_chart'] = [
            'label' => $month,
            'data' => $usersCount,
        ];

        /*Vouchers*/
        $vouchers = [];
        // get total Vouchers from vouchers table
        $vouchers['total_vouchers'] = (string)Voucher::all()->count();
        // get total Vouchers Redeem from vouchers table
        $vouchers['total_voucher_redeem'] = (string)Voucher::where('voucher_redeem',config('constants.vouchers.voucher_redeem_text.yes'))->count();

        $response['products'] = $products;
        $response['users'] = $users;
        $response['vouchers'] = $vouchers;
        $response['orders'] = $orders;

        return $response;

    }

    /**
     * Common code for get the months list between teo date range.
     *
     * @param $startDate        - start date 'Y-m-d' format.
     * @param $endDate          - end date 'Y-m-d' format.
     * @return array
     */
    public static function getMonthListFromDate($startDate, $endDate)
    {
        $periods = CarbonPeriod::create($startDate, '1 month', $endDate);
        $months = [];
        foreach ($periods as $dt) {
            $months[] = $dt->format("M Y");
        }

        return $months;
    }

    /**
     * Common code for get the Order Status By count between to date range.
     *
     * @param $order_start_Date   - start date 'Y-m-d' format.
     * @param $order_end_Date     - end date 'Y-m-d' format.
     * @param $OrderStatus        - order status.
     * @return array
     */

    public static function getOrderStatusByCount($order_start_Date,$order_end_Date,$OrderStatus){

        if (Carbon::createFromFormat('Y-m-d', trim($order_start_Date)) !== false) {
            $dates[0] = $order_start_Date.' 00:00:00';
        }
        if (Carbon::createFromFormat('Y-m-d', trim($order_end_Date)) !== false) {
            $dates[1] =$order_end_Date.' 23:59:59';
        }
        $orders_count = Order::where('order_status',$OrderStatus)->whereBetween('created_at',$dates)->count();
        return $orders_count;
    }

    /**
     * get Users Count From Activity Log Table
     *
     * @param $month
     * @return array
     */

    public static function getUsersCountFromActivityLog($month){

        $usersCount = [];
        foreach ($month as $item) {
            $startdate = Carbon::parse($item)->format('Y-m-d');
            $enddate = Carbon::parse($startdate)->endOfMonth()->format('Y-m-d');
            $dates[0] = $startdate.' 00:00:00';
            $dates[1] =$enddate.' 23:59:59';
            $usersCount[] = ActivityLog::whereBetween('created_at',$dates)->count();
        }

        return $usersCount;

    }
}
