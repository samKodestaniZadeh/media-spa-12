<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Tarahi;
use App\Models\ReqDesigner;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\TarahiNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class TarahiDesignerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $reqDesigner;
    public $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ReqDesigner $reqDesigner,$message)
    {

        $this->reqDesigner = $reqDesigner;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user)
    {
        $tarahi = Tarahi::find($this->reqDesigner->tarahi_id);
        $route = 'tarahiDesigner.index';
        $user = $user->find($this->reqDesigner->user_id);

        $message = $this->message;

        Notification::send($user , new TarahiNotification($tarahi,$message,$route,$user));
        $notification = $user->unreadNotifications;

        if($notification && count($notification) > 0)
        {
            $tarahi->update([
                'notification_id'=>$notification[count($notification)-1]->id,
            ]);
        }

    }
}
