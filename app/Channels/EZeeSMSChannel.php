<?php

namespace App\Channels;

use App\Models\User\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class EZeeSMSChannel
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
        $EZeeSMSMessage = $notification->toEZeeSMS($notifiable); // EZeeMessage object
        $number = $notifiable->contact_number;

        //send sms via eZeeSMS API
        Http::get(config('constants.eZee_sms.url') . '?username=' . config('constants.eZee_sms.username') . '&pass=' . config('constants.eZee_sms.pass') . '&senderid=' . config('constants.eZee_sms.senderid') . '&message=' . $EZeeSMSMessage->message . '&dest_mobileno=' . $number . '&msgtype=' . config('constants.eZee_sms.msgtype') . '&response=' . config('constants.eZee_sms.response') . '&dltentityid=' . config('constants.eZee_sms.dltentityid') . '&dlttempid=' . $EZeeSMSMessage->dltTempID . '&dltheaderid=' . config('constants.eZee_sms.dltheaderid'));
    }
}
