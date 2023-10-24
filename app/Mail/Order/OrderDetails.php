<?php

namespace App\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $orderData;
    protected $orderProducts;
    protected $text;

    public function __construct($orderData, $orderProducts, $emailTemplate, $subject, $text)
    {
        $this->orderData = $orderData;
        $this->orderProducts = $orderProducts;
        $this->emailTemplate = $emailTemplate;
        $this->subject = $subject;
        $this->text = $text;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->markdown('emails.Order.' . $this->emailTemplate, ['orderData' => $this->orderData, 'orderProducts' => $this->orderProducts, 'text' => $this->text]);
    }
}
