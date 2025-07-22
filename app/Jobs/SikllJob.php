<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\User;
use App\Models\Sikll;
use App\Notifications\SikllNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SikllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $sikll;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Sikll $sikll)
    {
        return $this->sikll = $sikll;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user , Role $role)
    {
        $siklls = Sikll::find($this->sikll->id);
        if($siklls->status == 0)
        {
            $siklls->update(['status' => 1]);

            $massage = 'مهارت شما در وضیعت انتظار قرار گرفت.';
            $route = 'sikll.index';
            $user = $user->find($siklls->user_id);
            Notification::send($user , new SikllNotification($siklls,$massage,$route,$user));

            $massage = 'یک مهارت نیاز به تایید دارد.';
            $route = 'sikllAdmin.show';
            $users = $role->find(4);
            foreach ($users->users as $key => $user)
            {
                Notification::send($user , new SikllNotification($siklls,$massage,$route,$user));
            }
        }
        else if($siklls->status == 4)
        {
            $massage = 'مهارت شما توسط کارشناس تایید و در صفحه مهارت نمایش داده شد.';
            $route = 'sikll.index';
            $user = $user->findOrfail($siklls->user_id);
            Notification::send($user , new SikllNotification($siklls,$massage,$route,$user));

        }
    }
}
