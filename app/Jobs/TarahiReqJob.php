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
use App\Notifications\TarahiReqNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class TarahiReqJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tarahi;
    public $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Tarahi $tarahi,$message)
    {

        $this->tarahi = $tarahi;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user)
    {
        $tarahis = Tarahi::find($this->tarahi->id);
        $route = 'reqTarahi.show';
        $user = $user->find($this->tarahi->user_id);

        $message = $this->message;
        Notification::send($user , new TarahiReqNotification($tarahis,$message,$route,$user));

    }
}
