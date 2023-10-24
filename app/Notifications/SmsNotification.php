<?php

namespace App\Notifications;

use App\Channels\EZeeSMSChannel;
use App\EZeeSMS\EZeeMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;
    public $dltTempID;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $dltTempID)
    {
        $this->message = $message;
        $this->dltTempID = $dltTempID;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [EZeeSMSChannel::class];
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param mixed $notifiable
     * @return EZeeMessage
     */
    public function toEZeeSMS($notifiable)
    {
        return (new EZeeMessage())->message($this->message, $this->dltTempID);
    }
}
