<?php

namespace App\Jobs\Order;

use App\Mail\Order\OrderDetails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderData;
    protected $orderProducts;
    protected $toEmail;
    protected $subject;
    protected $text;

    /**
     *  Create a new job instance.
     * @param $data
     */
    public function __construct($orderData, $orderProducts, $toEmail, $subject, $text)
    {
        $this->orderData = $orderData;
        $this->orderProducts = $orderProducts;
        $this->toEmail = $toEmail;
        $this->subject = $subject;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->toEmail)->send(new OrderDetails($this->orderData, $this->orderProducts, 'OrderDetails', $this->subject, $this->text));
    }
}
