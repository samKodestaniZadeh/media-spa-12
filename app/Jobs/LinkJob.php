<?php

namespace App\Jobs;

use App\Models\Link;
use App\Models\Role;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Notifications\LinkNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class LinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $link;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user , Role $role)
    {
        $links = Link::find($this->link->id);
        if($links->status == 0)
        {
            $links->update(['status' => 1]);

            $massage = 'لینک شبکه اجتماعی شما در وضیعت انتظار قرار گرفت.';
            $route = 'social.index';
            $user = $user->find($links->user_id);
            Notification::send($user , new LinkNotification($links,$massage,$route,$user));

            $massage = 'یک لینک شبکه اجتماعی نیاز به تایید دارد.';
            $route = 'socialAdmin.show';
            $users = $role->find(4);
            foreach ($users->users as $key => $user)
            {
                Notification::send($user , new LinkNotification($links,$massage,$route,$user));
            }
        }
        else if($links->status == 4)
        {
            $massage = 'لینک شما توسط کارشناس تایید و در صفحه شبکه های اجتماعی نمایش داده شد.';
            $route = 'social.index';
            $user = $user->findOrfail($links->user_id);
            Notification::send($user , new LinkNotification($links,$massage,$route,$user));

        }
    }
}
