<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\Support;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\SupportNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SupportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $support;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Support $support)
    {
        return $this->support = $support;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Role $role,User $user)
    {
        $supports = Support::find($this->support->id);
        if($supports->status == 0 && $supports->destination == null)
        {
            Support::find($this->support->parent_id)->update([
                'status'=>4,
            ]);

            $supports->update([
                'status'=>4,
            ]);

            $message = 'یک پیام ارسال شده است.';
            $route = 'supportAdmin.index';
            $users = $role->find(3);
            foreach ($users->users as $key => $user)
            {
                Notification::send($user , new SupportNotification($supports,$message,$route,$user));

            }
        }
        else if($supports->status == 0 && $supports->destination)
        {
            $supports->update([
                'status'=>1,
            ]);

            $message = 'یک پیام ارسال شده است.';
            $route = 'support.index';
            $user = $user->find($supports->destination);
            Notification::send($user , new SupportNotification($supports,$message,$route,$user));
        }
    }
}
