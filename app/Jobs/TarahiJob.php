<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Tarahi;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\TarahiNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class TarahiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,Batchable;
    public $tarahi;
    public $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Tarahi $tarahis,$message)
    {
        $this->tarahi = $tarahis;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user , Role $role  )
    {
        $tarahis = Tarahi::find($this->tarahi->id);
        $route = 'tarahi.index';
        $user = $user->find($tarahis->user_id);

        if($tarahis->status == 0)
        {
            $tarahis->update([
                'status'=>1,
            ]);

            $message = $this->message;

            Notification::send($user , new TarahiNotification($tarahis,$message,$route,$user));

        }
        else // if($tarahis->status == 2)
        {
            $message = $this->message;

            Notification::send($user , new TarahiNotification($tarahis,$message,$route,$user));
        }
        // else if($tarahis->status == 4)
        // {
        //     $message = $this->message;

        //     Notification::send($user , new TarahiNotification($tarahis,$message,$route,$user));

        // }
        // else if($tarahis->status == 5)
        // {

        //     $message = $this->message;

        //     Notification::send($user , new TarahiNotification($tarahis,$message,$route,$user));

        // }
        // else if($tarahis->status == 8)
        // {
        //     $message = $this->message;

        //     Notification::send($user , new TarahiNotification($tarahis,$message,$route,$user));
        // }
    }


}
