<?php

namespace Database\Seeders;

use App\Models\Order\Order;
use App\Models\Order\OrderProducts;
use App\Models\Order\OrderStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_statuses')->truncate();
        DB::table('orders')->truncate();
        DB::table('order_products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        factory(Order::class, 10)->create()->each(function ($order) {
            factory(OrderStatus::class, 1)->create([
                'order_id' => $order->id,
                'status' => $order->order_status,
                'remark' => $order->order_status_remark
            ]);
            factory(OrderProducts::class, 2)->create(['order_id' => $order->id]);
        });
    }
}
