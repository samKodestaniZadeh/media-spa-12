<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\ProductNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $product;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        return $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user , Role $role )
    {
        $products = product::find($this->product->id);
        if($products->status == 0)
        {
            $products->update(['status' => 1]);

            $massage = 'محصول شما در وضیعت انتظار قرار گرفت.';
            $route = 'product.index';
            $user = $user->find($products->user_id);
            Notification::send($user , new ProductNotification($products,$massage,$route,$user));

            $massage = 'یک محصول نیاز به تایید دارد.';
            $route = 'productAdmin.index';
            $users = $role->find(4);
            foreach ($users->users as $key => $user)
            {
                Notification::send($user , new ProductNotification($products,$massage,$route,$user));
            }
        }
        else if($products->status == 4)
        {
            $massage = 'محصول شما توسط کارشناس تایید و در صفحه محصولات نمایش داده شد.';
            $route = 'product.index';
            $user = $user->findOrfail($products->user_id);
            Notification::send($user , new ProductNotification($products,$massage,$route,$user));

        }
        else if($products->status == 5)
        {

            $massage = 'محصول شما در وضیعت توقف فروش قرار گرفت.';
            $route = 'product.index';
            $user = $user->find($products->user_id);
            Notification::send($user , new ProductNotification($products,$massage,$route,$user));

        }

    }
}
