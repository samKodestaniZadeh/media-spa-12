<?php

namespace App\Jobs;

use App\Models\Bank;
use App\Models\Role;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Notifications\BankNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class BankJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $bank;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Bank $banks)
    {
        return $this->bank = $banks;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user , Role $role)
    {
        $banks = Bank::find($this->bank->id);
        if($banks->status == 0)
        {
            $banks->update(['status' => 1]);

            $massage = 'بانک ایجاد شده شما در وضیعت انتظار قرار گرفت.';
            $route = 'bank.index';
            $user = $user->find($banks->user_id);
            Notification::send($user , new BankNotification($banks,$massage,$route,$user));

            $message = 'یک بانک نیاز به تایید دارد.';
            $route = 'bankAdmin.index';
            $users = $role->find(3);
            foreach ($users->users as $key => $user)
            {
                Notification::send($user , new BankNotification($banks,$message,$route,$user));
            }
        }
        else if($banks->status == 4)
        {
            $massage = 'بانک ایجاد شده شما در وضیعت فعال قرار گرفت.';
            $route = 'bank.index';
            $user = $user->find($banks->user_id);
            Notification::send($user , new BankNotification($banks,$massage,$route,$user));
        }
    }
}
