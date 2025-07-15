<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Product;
use App\Jobs\CommentJob;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommentProductNotification;

class CommentAdminController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Comment $comment,User $user,Company $company,Route $route)
    {
        Gate::authorize('create', $comment);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $tickets = $comment->with('replies')->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return  Inertia::render('Users/Admin/Comment/comment-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=> $tickets,
        'ids'=> $ids,'notifications'=>$notifications,'statuses'=> $statuses, 'users' =>$users,
        'companies' => $companies,'descriptions'=>$descriptions,'path'=>$request->path(),'wallet'=>$wallet]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Comment $comment,User $user,Role $role,Product $product)
    {
        Gate::authorize('create', $comment);

        // dd($request);
        $request->validate([
            'text' => 'required',
            'user_id' => 'required',
            'product_id' => 'required',
        ]);

        if($request->parent_id)
        {
            $comments = $comment->find($request->parent_id);

            $users = $user->find(auth()->user()->id);

            $comment = $comment->create([
                'user_id'=> auth()->user()->id,
                'parent_id'=> $request->parent_id,
                'commentable_type'=>$comments->commentable_type,
                'commentable_id'=> $request->product_id,
                'text'=> $request->text,
                'status' => 4,
            ]);

            if($comment)
            {
                $users = $user->find($comments->user_id);

                if($users->roles->find(3))
                {
                    $massage = 'یک نظر جدید برای شما ارسال شده است.';
                    $route = 'commentAdmin.index';
                    $user = $user->find($comments->user_id);
                    Notification::send($user , new CommentNotification($comments,$massage,$route,$user));
                }
                else if($users->roles->find(1))
                {
                    $massage = 'یک نظر جدید برای شما ارسال شده است.';
                    $route = 'commentSeller.index';
                    $user = $user->find($comments->suer_id);
                    Notification::send($user , new CommentNotification($comments,$massage,$route,$user));
                }
                else
                {
                    $massage = 'یک نظر جدید برای شما ارسال شده است.';
                    $route = 'comment.index';
                    $user = $user->find($comments->user_id);
                    Notification::send($users , new CommentNotification($comments,$massage,$route,$user));
                }

                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!نظر شما',
                        'text'=> '.باموفقیت ثبت و نمایش داده شد',
                        'icon'=> 'success',
                        'button' => 'ok']
                    );


            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!نظر شما',
                        'text'=> '.ثبت نشد',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );


            }
            return redirect()->back();

        }
        else
        {

          $comments = $comment->create([
                'user_id'=> auth()->user()->id,
                'parent_id'=> 0,
                'commentable_type'=>Product::class,
                'commentable_id'=> $request->product_id,
                'text'=> $request->text,
            ]);
            if($comments)
            {
                $user = $role->find(3);
                $massage = 'نظر جدیدی ارسال شده است.';
                $route = 'commentAdmin.show';
                foreach ($user->users as $key => $user)
                {
                    Notification::send($user , new CommentNotification($comments,$massage,$route,$user));
                }
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!نظر شما',
                        'text'=> '.باموفقیت ثبت شد،پس از تایید کارشناسان نمایش داده خواهد شد',
                        'icon'=> 'success',
                        'button' => 'ok']
                    );


            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!نظر شما',
                        'text'=> '.ثبت نشد',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );


            }
            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($commentModir)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Comment $comment,User $user,Company $company,Route $route,$id)
    {
        Gate::authorize('create', $comment);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $comments = $comment->with('user')->with('replies')->find($id);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name', 'route(commentAdmin.edit)')->first() && $route->where('name','route(commentAdmin.edit)')->first()->descriptions?
            $route->where('name','route(commentAdmin.edit)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Comment/comment-edit',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'comments'=> $comments,'alert' => $alert,
        'users' =>$users,'notifications'=>$notifications,'companies' => $companies,'wallet'=>$wallet,
        'descriptions'=>$descriptions,'path'=>'route(commentAdmin.edit)']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Comment $comment,User $user,Company $company)
    {
        Gate::authorize('create', $comment);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $companies = $company->first();
        $comments = $comment->find($request->id)->update(['status'=> $request->status]);

        if($comments)
        {
            $comment = $comment->find($request->id);
            CommentJob::dispatch($comment)->delay(now()->addMinute($companies->job));
            $request->session()->flash(
                'alert' ,[
                    'title'=>'نظر!',
                    'text'=> 'با موفقیت بروز رسانی شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'نظر!',
                    'text'=> 'بروز رسانی نشد،مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(404);
    }
}
