<?php

namespace App\Channels;

use App\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;

class PinnacleEmailChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $EmailMessage = $notification->toPinnacleEmailMessage($notifiable);

        $postFields =  [
            'EmailHTMLBody' => base64_encode($EmailMessage),
            'EmailRecipient' => [$notification->email],
            'EmailSender' =>  config('constants.pinnacle_email.email_sender'),
            'SenderName' => 'LoyrexTeam',
            'SendingType' => '1',
            'Subject' =>  $notification->subject,
        ];

        Http::withHeaders([
            'content-type' => 'application/json',
        ])->post('https://webapi.pinnacle.in/EMAIL.svc/SendEmail/ServiceAuthToken=' . config('constants.pinnacle_email.service_auth_token') . '/UserAuthToken=' . config('constants.pinnacle_email.user_auth_token'), $postFields);
    }
}
