<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRegisterNotification extends Notification
{
    use Queueable;
    public $user;
    public $message;
    public $route;
    public $password;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $users,$message,$route,$password)
    {
        $this->user = $users;
        $this->message = $message;
        $this->route = $route;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                ->line($this->user->name )
                ->line($this->message)
                ->line('email')
                ->line($this->user->email)
                ->line('password')
                ->line($this->password)
                ->line('The introduction to the notification.')
                ->action('Notification Action', route('login'))
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
            //
        ];
    }
}
