<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Notifications\OrderNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class OrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $order;
    public $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order,$message)
    {
        $this->order = $order;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user)
    {

        $orders = Order::find($this->order->id) ;
        $route = 'order.index';
        $user = $user->find($orders->user_id);
        if ($orders->status == 4)
        {
            $message = $this->message;

            Notification::send($user , new OrderNotification($orders,$message,$route,$user));
        }


    }
}
