<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\User;
use App\Models\Identity;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\IdentityNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class IdentityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $identity;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Identity $identity)
    {
        return $this->identity = $identity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user , Role $role)
    {
        $identitys = Identity::find($this->identity->id);
        if($identitys->status == 0)
        {
            $identitys->update(['status' => 1]);

            $massage = 'اطلاعات هویتی شما در وضیعت انتظار قرار گرفت.';
            $route = 'identity.index';
            $user = $user->find($identitys->user_id);
            Notification::send($user , new IdentityNotification($identitys,$massage,$route,$user));

            $massage = 'اطلاعت هویتی شخصی نیاز به تایید دارد.';
            $route = 'identityAdmin.index';
            $users = $role->find(4);
            foreach ($users->users as $key => $user)
            {
                Notification::send($user , new IdentityNotification($identitys,$massage,$route,$user));
            }
        }
        else if($identitys->status == 4)
        {

            $message = 'اطلاعات هویتی شما در وضعیت تایید قرار گرفت.';
            $route = 'identity.index';
            $user = $user->find($identitys->user_id);
            Notification::send($user , new IdentityNotification($identitys,$message,$route,$user));
        }
    }
}
