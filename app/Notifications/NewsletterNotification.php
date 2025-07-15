<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Newsletter;
use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewsletterNotification extends Notification
{
    use Queueable;
    public $newsletter;
    public $massage;
    public $route;
    public $p_route;
    public $subject;
    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Newsletter $newsletters,$massage,$route,$p_route,$subject,$user)
    {
        // dd($newsletters,$user);
        $this->newsletter = $newsletters;
        $this->massage = $massage;
        $this->route = $route;
        $this->p_route = $p_route;
        $this->subject = $subject;
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
        foreach ($this->user as $key => $user)
        {
            // dd($user);
            if(User::find($user->id)->profile->email == 1 &&
            User::find($user->id)->profile->mobile == 1 &&
            User::find($user->id)->profile->notification == 1)
            {
                return ['mail','database',SmsChannel::class];
            }

            else if(User::find($user->id)->profile->email == 1 &&
            User::find($user->id)->profile->mobile == 1 &&
            User::find($user->id)->profile->notification !== 1 )
            {
                return ['mail',SmsChannel::class];
            }

            else if(User::find($user->id)->profile->email == 1 &&
            User::find($user->id)->profile->mobile !== 1 &&
            User::find($user->id)->profile->notification == 1 )
            {
                return ['mail','database'];
            }

            else if(User::find($user->id)->profile->email !== 1 &&
            User::find($user->id)->profile->mobile == 1 &&
            User::find($user->id)->profile->notification == 1 )
            {
                return ['database',SmsChannel::class];
            }
            else if(User::find($user->id)->profile->email == 1)
            {
                return ['mail'];
            }
            else if(User::find($user->id)->profile->mobile == 1)
            {
                return [SmsChannel::class];
            }
            else if(User::find($user->id)->profile->notification == 1 )
            {
                return ['database'];
            }
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
        // dd($this->route,$this->subject,$this->massage);
        return (new MailMessage)
                    ->line($this->subject)
                    ->line($this->massage)
                    ->action('Notification Action', route($this->route,$this->p_route))
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
            'massage'=> $this->massage,
            'route' => $this->route,
            'id'=> $this->p_route,
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
            'massage'=> $this->massage,
            'route' => $this->route ,
            'id'=> $this->p_route,
        ];
    }
}
