<?php

namespace App\Jobs;

use App\Models\Orderable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\OrderableNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class OrderableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $orderable;
    public $message;
    public $route;
    public $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Orderable $orderables,$message,$route,$user)
    {
        return [$this->orderable = $orderables,$this->message = $message,$this->route = $route,$this->user = $user];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orderables = Orderable::find($this->orderable->id);
        $message = $this->message;
        $route = $this->route;
        $user = $this->user;

        Notification::send($user , new OrderableNotification($orderables,$message,$route,$user));
    }
}
