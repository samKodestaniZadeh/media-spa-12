<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\ProfileNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProfileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $profile;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Profile $profile)
    {
        return $this->profile = $profile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user , Role $role,Profile $profile)
    {
        $profiles = $profile::find($this->profile->id);
        if($profiles->status == 0)
        {
            $massage = 'یک پروفایل نیاز به تایید دارد.';
            $route = 'profileAdmin.show';
            $users = $role->find(3);
            foreach ($users->users as $key => $user)
            {
                Notification::send($user , new ProfileNotification($profiles,$massage,$route,$user));
            }
        }
    }
}
