<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\CommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CommentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $comment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        return $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user , Role $role)
    {
        $comments = Comment::find($this->comment->id);
        // dd($comments);
        if($comments->status == 0)
        {
            $comments->update(['status' => 1]);

            $massage = 'نظر شما در وضیعت انتظار قرار گرفت.';
            $route = 'comment.index';
            $user = $user->find($comments->user_id);
            Notification::send($user , new CommentNotification($comments,$massage,$route,$user));

            $message = 'یک نظر نیاز به تایید دارد.';
            $route = 'commentAdmin.index';
            $users = $role->find(3);
            foreach ($users->users as $key => $user)
            {
                Notification::send($user , new CommentNotification($comments,$message,$route,$user));
            }
        }
        else if($comments->status == 4)
        {
            // $comments = $comment->find($request->id);
            // dd($comments);
            $message = 'نظر شما در وضعیت انتشار قرار گرفت.';
            $route = 'comment.index';
            $user = $user->find($comments->user_id);
            Notification::send($user , new CommentNotification($comments,$message,$route,$user));

            // $products = $comments->commentable;
            // $message = 'نظری برای محصول/خدمات شما ارسال شده است.';
            // $route = 'guest_comment.show';
            // $user = $user->find($comments->commentable->user_id);
            // Notification::send($user , new CommentNotification($comments,$message,$route,$user));

            if($comments->parent_id !== null)
            {
                // $comments = $comment->find($comments->parent_id);
                $message = 'پاسخی برای نظر شما ارسال شده است.';
                $route = 'guest_comment.show';
                $user = $user->find($comments->user_id);
                Notification::send($user , new CommentNotification($comments,$message,$route,$user));
            }
        }
    }
}
