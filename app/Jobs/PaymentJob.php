<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\User;
use App\Models\Payment;
use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\PaymentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $payment;
    public $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Payment $payments,$message)
    {
       $this->payment = $payments;
       $this->message = $message;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user , Role $role)
    {
        $payments = Payment::find($this->payment->id);
        $route = 'payment.index';
        $user = $user->find($payments->user_id);

        if($payments->status == 4)
        {
            $message = $this->message;

            Notification::send($user , new PaymentNotification($payments,$message,$route,$user));
        }
    }
}
