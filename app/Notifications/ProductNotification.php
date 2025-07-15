<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Product;
use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProductNotification extends Notification
{
    use Queueable;
    public $product;
    public $message;
    public $route;
    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Product $products,$message,$route,$user)
    {
        $this->product = $products;
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
    $user = User::find($this->user->id);

    // بررسی اینکه کاربر یا پروفایلش وجود داره یا نه
    if (!$user || !$user->profile) {
        \Log::warning('User or profile not found for notification', ['user_id' => $this->user->id]);
        return ['database'];
    }

    $profile = $user->profile;

    $channels = [];

    if ($profile->email == 1) {
        $channels[] = 'mail';
    }

    if ($profile->mobile == 1) {
        $channels[] = SmsChannel::class;
    }

    if ($profile->notification == 1) {
        $channels[] = 'database';
    }

    // اگر هیچ‌کدوم فعال نبود، پیش‌فرض فقط database
    return count($channels) > 0 ? $channels : ['database'];
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
            'id'=> '',
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
