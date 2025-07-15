<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\User;
use App\Models\Tarahi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\TarahiNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class TarahiAdminJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
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
        $route = 'tarahiAdmin.index';

        $message = $this->message;

        $users = $role->find(3);
        foreach ($users->users as $key => $user)
        {
            Notification::send($user , new TarahiNotification($tarahis,$message,$route,$user));
        }


    }
}
