<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function send($notifiable , Notification $notification)
    {
        //Send Sms
        return $notification->toSms($notifiable);
    }
}
