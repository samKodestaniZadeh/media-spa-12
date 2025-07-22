<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Deposit;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\DepositNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class DepositJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $deposit;
    public $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Deposit $deposit,$message)
    {
        $this->deposit = $deposit;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user)
    {
        $deposits = Deposit::find($this->deposit->id);
        $route = 'payment.index';
        $user = $user->find($deposits->user_id);


        if ($deposits->status == 4) {

            $message = $this->message;

            Notification::send($user , new DepositNotification($deposits,$message,$route,$user));
        }

    }
}
