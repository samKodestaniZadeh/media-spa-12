<?php

namespace App\Notifications;

use App\Models\User;
use App\Channels\SmsChannel;
use App\Models\Sikll;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SikllNotification extends Notification
{
    use Queueable;
    public $sikll;
    public $message;
    public $route;
    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Sikll $siklls,$message,$route,$user)
    {
        $this->sikll = $siklls;
        $this->message = $message;
        $this->route = $route;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if(User::find($this->user->id)->profile->email == 1 &&
        User::find($this->user->id)->profile->mobile == 1 &&
        User::find($this->user->id)->profile->notification == 1)
        {
            return ['mail','database',SmsChannel::class];
        }

        else if(User::find($this->user->id)->profile->email == 1 &&
        User::find($this->user->id)->profile->mobile == 1 &&
        User::find($this->user->id)->profile->notification !== 1 )
        {
            return ['mail',SmsChannel::class];
        }

        else if(User::find($this->user->id)->profile->email == 1 &&
        User::find($this->user->id)->profile->mobile !== 1 &&
        User::find($this->user->id)->profile->notification == 1 )
        {
            return ['mail','database'];
        }

        else if(User::find($this->user->id)->profile->email !== 1 &&
        User::find($this->user->id)->profile->mobile == 1 &&
        User::find($this->user->id)->profile->notification == 1 )
        {
            return ['database',SmsChannel::class];
        }
        else if(User::find($this->user->id)->profile->email == 1)
        {
            return ['mail'];
        }
        else if(User::find($this->user->id)->profile->mobile == 1)
        {
            return [SmsChannel::class];
        }
        else if(User::find($this->user->id)->profile->notification == 1 )
        {
            return ['database'];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line($this->message)
                    ->action('Notification Action', route($this->route))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'massage'=> $this->message,
            'route' => $this->route,
            'id'=> $this->route == 'sikllAdmin.show'? $this->sikll->user_id:null,
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSms($notifiable)
    {
        return [
            'massage'=> $this->message,
            'route' => $this->route,
            'id'=> '',
        ];
    }
}
