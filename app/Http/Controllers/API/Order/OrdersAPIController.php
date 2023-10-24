<?php

namespace App\Http\Controllers\API\Order;

use App\Exports\Order\OrdersExport;
use App\Helpers\Easebuzz;
use App\Http\Controllers\Controller;
use App\Http\Requests\Import\CsvRequest;
use App\Http\Requests\Order\DeliverPartnerRequest;
use App\Http\Requests\Order\OrderStatusRequest;
use App\Http\Resources\Order\OrdersCollection;
use App\Http\Resources\Order\OrdersResource;
use App\Imports\Order\OrdersImport;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Models\PointHistory\PointHistory;
use App\Models\User\User;
use App\Notifications\PinnacleEmailNotification;
use App\Notifications\SmsNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;

class OrdersAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return OrdersCollection
     */
    public function index(Request $request)
    {
        $query = User::commonFunctionMethod(Order::class, $request);
        return new OrdersCollection(OrdersResource::collection($query), OrdersResource::class);
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return OrdersResource
     */
    public function show(Order $order)
    {
        return new OrdersResource($order->load([]));
    }

    /**
     * Update the specified resource in storage.
     * @param OrderStatusRequest $request
     * @param Order $order
     * @return OrdersResource|\Illuminate\Http\JsonResponse
     */
    public function orderStatus(OrderStatusRequest $request, Order $order)
    {
        if (!is_null($request->get('status'))) {
            if ($request->get('status') == config('constants.order.status_text.cancel')) {

                $carbonDate  = Carbon::parse($order->created_at); //Get created_at date from orders table
                $createdAt  = $carbonDate->addHours(24)->timestamp; // Add 24 hours into order created_at date
                $currentTimestamp = now()->timestamp; //Get current timestamp


                if ($order->order_status == config('constants.order.status_text.pending') && $currentTimestamp < $createdAt) {

                    $user = $request->user();

                    $order->order_status = $request->get('status');
                    $order->order_status_remark = $request->get('remark');
                    $order->update();

                    OrderStatus::create([
                        'order_id' => $order->id,
                        'status' => $request->get('status'),
                        'remark' => $request->get('remark')
                    ]);

                    // If payment_amount is not null
                    if (!is_null($order->payment_amount) && $order->payment_amount != '') {
                        $totalPoints = $order->total_points - $order->payment_amount;
                        //order by Refund Payment to user
                        $MERCHANT_KEY = config('constants.merchant_key');
                        $SALT = config('constants.salt_key');
                        $ENV = config('constants.env');

                        # create Easebuzz object and pass the merchant key, salt and enviroment
                        $easebuzzObj = new Easebuzz($MERCHANT_KEY, $SALT, $ENV);


                        $data['txnid'] = $order->payment_transaction_id;
                        $data['refund_amount'] = $order->payment_amount;
                        $data['phone'] = $user->contact_number;
                        $data['email'] = $user->email;
                        $data['amount'] = $order->payment_amount;

                        //order by Refund Payment to user
                        $easebuzzObj->refundAPI($data);
                    } else {
                        $totalPoints = $order->total_points;
                    }

                    //Point History
                    $pointHistory['user_id'] = $order->user_id;
                    $pointHistory['action_type '] = config('constants.user.action_type.credit');
                    $pointHistory['points'] = $totalPoints;
                    $pointHistory['description'] = config('constants.user.action_type_text.1');
                    $pointHistory['order_id'] = $order->id;
                    PointHistory::create($pointHistory); // Create points & credit into point_histories table

                    $user = User::where('id', $order->user_id)->first();

                    $remainingPoints['reward_points'] = $user->reward_points + $totalPoints;
                    $user->update($remainingPoints); // Update reward_points into users table

                    $customerMailText = 'We are sorry to inform you that your order number: #' . $order->id . ', has been withdraw due to ' . $order->order_status_remark . '. This happens rarely and we apologize for the inconvenience. We will initiate a refund to your account (*if applicable)';
                    $subject = 'Your order has been withdrawn by team Loyrex';
                    $template = 'Order.OrderDetails';

                    $adminSubject = 'Order Cancellation';
                    $adminMailText = 'You have canceled the order with the order number: #' . $order->id;


                    $user->notify(new PinnacleEmailNotification($user->email, $subject, $customerMailText, $user, $template, $type = '2')); // Email to User
                    $user->notify(new PinnacleEmailNotification($user->email, $adminSubject, $adminMailText, $user, $template, $type = '2')); // Email to Admin

                    $message = $user->dynamicData($request, config('constants.eZee_sms.messages.cancel_order'), $user, $order); // Get sms text from constants file & replace legend to respactive text 
                    $user->notify(new SmsNotification($message, config('constants.eZee_sms.messages.cancel_order_tempid'))); /* Send SMS into Background thread  */

                    return new OrdersResource($order);
                } else {
                    return response()->json(['error' => "You cannot cancel this order because this order has been  " . config('constants.order.status.' . $order->order_status) . "."], config('constants.validation_codes.unprocessable_entity'));
                }
            } else {

                $user = User::where('id', $order->user_id)->first();

                $data['order_status'] = $request->get('status');
                $data['order_status_remark'] = $request->get('remark');
                $order->update($data);

                OrderStatus::create([
                    'order_id' => $order->id,
                    'status' => $order->order_status,
                    'remark' => $order->order_status_remark
                ]);

                if ($request->get('status') != config('constants.order.status_text.pending')) {

                    $template = 'Order.OrderDetails';
                    if ($request->get('status') == config('constants.order.status_text.inprocess')) {
                        $subject = 'Your Loyrex order is in process';
                        $customerMailText = 'Hi ' . $user->first_name . ', We appreciate your patience! Your order number: #' . $order->id . ' is in process. Our team is working hard to deliver your order on time. Further status of the order will be updated to you via SMS and email.';
                    } else if ($request->get('status') == config('constants.order.status_text.shipped')) {
                        $subject = 'Your Loyrex order has been shipped';
                        $customerMailText = 'Hi ' . $user->first_name . ', We are happy to inform you, Your order number: #' . $order->id . ' has been shipped via ' . $order->courier_name . '. Your tracking number is: ' . $order->tracking_id . ' You can track your order on ' . $order->courier_link;
                    } else if ($request->get('status') == config('constants.order.status_text.delivered')) {
                        $subject = 'Your Loyrex order has been delivered';
                        $customerMailText = nl2br("Hi " . $user->first_name . ", Your order number: #" . $order->id . " has been delivered to your registered address. Hope we made you smile! \n\nIn case of any queries or concerns, please contact us on " . config('constants.contact_url'));
                    }

                    $order->notify(new PinnacleEmailNotification($user->email, $subject, $customerMailText, $user, $template, $type = '2')); // Email to User
                    /*
                    // Send SMS to user
                    if ($request->get('status') == config('constants.order.status_text.inprocess')) { // status is in-Process
                        $smsText = config('constants.eZee_sms.messages.inprocess_order');
                        $tempId  = config('constants.eZee_sms.messages.inprocess_order_tempid');
                    } else if ($request->get('status') == config('constants.order.status_text.shipped')) { // status is shipped
                        $smsText = config('constants.eZee_sms.messages.shipped_order');
                        $tempId  = config('constants.eZee_sms.messages.shipped_order_tempid');
                    } else if ($request->get('status') == config('constants.order.status_text.delivered')) { // status is delivered
                        $smsText = config('constants.eZee_sms.messages.delivered_order');
                        $tempId  = config('constants.eZee_sms.messages.delivered_order_tempid');
                    }

                    $message = $user->dynamicData($request, $smsText, $user, $order); // Get sms text from constants file & replace legend to respactive text 
                    $user->notify(new SmsNotification($message, $tempId));*/  /* Send SMS into Background thread  */
                }
                return new OrdersResource($order);
            }
        } else {
            return response()->json(['error' => "Select one status in order to complete the process."], config('constants.validation_codes.unprocessable_entity'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Order $order
     * @return OrdersResource
     */
    public function deliveryPartners(DeliverPartnerRequest $request, Order $order)
    {
        $order->courier_name = $request['courier_name'];
        $order->tracking_id = $request['tracking_id'];
        $order->courier_link = $request['courier_link'];
        $order->update();
        return new OrdersResource($order);
    }

    /**
     * Import bulk
     * @param CsvRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function importBulk(CsvRequest $request)
    {
        return User::importBulk(
            $request,
            new OrdersImport(),
            config('constants.models.order_model'),
            config('constants.import_dir_path.order_dir_path')
        );
    }

    /**
     * Export Users Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new OrdersExport($request), 'Orders_' . config('constants.file.name') . '.csv');
    }
}
