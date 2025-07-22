<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Notifications\OrderNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class OrderModirJob implements ShouldQueue
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
    public function handle(User $user, Role $role)
    {

        $orders = Order::find($this->order->id) ;
        $route = 'orderModir.index';
        $message = $this->message;

        $users = $role->find(4);

        foreach ($users->users as $key => $user)
        {
            Notification::send($user , new OrderNotification($orders,$message,$route,$user));
        }

    }
}
