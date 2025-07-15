<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\File;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Tarahi;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Product;
use App\Models\Support;
use App\Jobs\CommentJob;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Notifications\CommentNotif;
use Illuminate\Support\Facades\Gate;
use App\Notifications\CommentNotification;
use App\Notifications\CommentProductNotif;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommentProductNotification;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Comment $comment,User $user,Company $company,Route $route)
    {
        // Gate::authorize('view', $comment);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $dark = $request->session()->has('class') ? $request->session()->get('class') :null;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $tickets = $comment->with('replies')->with('commentable')->where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Buyer/Comment/comment-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=> $tickets,'dark'=>$dark,
            'ids'=> $ids,'notifications'=>$notifications,'statuses'=> $statuses, 'users' =>$users,
            'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet
        ]);

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
    public function store(Request $request,Comment $comment,Company $company)
    {

        $companies = $company->first();
        // dd($request);
        if($request->text && $request->tarahi_id && $request->user_id)
        {
            if($request->parent_id)
            {
                $request->validate([
                    'text' => 'required',
                    'user_id' => 'required',
                    'tarahi_id' => 'required',
                ]);
                $comments = $comment->find($request->parent_id);

                    $comments = $comment->create([
                        'user_id'=> auth()->user()->id,
                        'parent_id'=> $request->parent_id,
                        'commentable_type'=>Tarahi::class,
                        'commentable_id'=> $request->tarahi_id,
                        'text'=> $request->text,
                    ]);

                    if($comments)
                    {
                        CommentJob::dispatch($comments)->delay(now()->addMinute($companies->job));
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'نظر!',
                                'text'=> 'باموفقیت ثبت شد.',
                                'icon'=> 'success',
                                'button' => 'ok']
                            );

                        return redirect()->bacK();
                    }
                    else
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'نظر!',
                                'text'=> 'ثبت نشد، مجدد تلاش نمایید.',
                                'icon'=> 'error',
                                'button' => 'ok']
                            );

                        return redirect()->bacK();
                    }
            }
            else
            {
                $request->validate([
                    'text' => 'required',
                    'user_id' => 'required',
                    'tarahi_id' => 'required',
                ]);

                $comments = $comment->create([
                    'user_id'=> auth()->user()->id,
                    'commentable_type'=>Tarahi::class,
                    'commentable_id'=> $request->tarahi_id,
                    'text'=> $request->text,
                ]);

                if($comments)
                {

                    CommentJob::dispatch($comments)->delay(now()->addMinute($companies->job));
                    $request->session()->flash(
                            'alert' ,[
                                'title'=>'نظر!',
                                'text'=> 'باموفقیت ثبت شد.',
                                'icon'=> 'success',
                                'button' => 'ok']
                            );

                    return redirect()->bacK();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'نظر!',
                            'text'=> 'ثبت نشد، مجدد تلاش نمایید.',
                            'icon'=> 'error',
                            'button' => 'ok']
                        );

                    return redirect()->bacK();
                }


            }
        }
        else if($request->text && $request->product_id && $request->user_id)
        {
            if($request->parent_id)
            {
                $request->validate([
                    'text' => 'required',
                    'user_id' => 'required',
                    'product_id' => 'required',
                ]);
                $comments = $comment->find($request->parent_id);

                    $comments = $comment->create([
                        'user_id'=> auth()->user()->id,
                        'parent_id'=> $request->parent_id,
                        'commentable_type'=>Product::class,
                        'commentable_id'=> $request->product_id,
                        'text'=> $request->text,
                    ]);

                    if($comments)
                    {
                        CommentJob::dispatch($comments)->delay(now()->addMinute($companies->job));
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'نظر!',
                                'text'=> 'باموفقیت ثبت شد.',
                                'icon'=> 'success',
                                'button' => 'ok']
                            );

                        return redirect()->bacK();
                    }
                    else
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'نظر!',
                                'text'=> 'ثبت نشد، مجدد تلاش نمایید.',
                                'icon'=> 'error',
                                'button' => 'ok']
                            );

                        return redirect()->bacK();
                    }


            }
            else
            {
                $request->validate([
                    'text' => 'required',
                    'user_id' => 'required',
                    'product_id' => 'required',
                ]);

                $comments = $comment->create([
                    'user_id'=> auth()->user()->id,
                    'commentable_type'=>Product::class,
                    'commentable_id'=> $request->product_id,
                    'text'=> $request->text,
                ]);

                if($comments)
                {
                    CommentJob::dispatch($comments)->delay(now()->addMinute($companies->job));
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'نظر!',
                                'text'=> 'باموفقیت ثبت شد.',
                                'icon'=> 'success',
                                'button' => 'ok']
                            );

                    return redirect()->bacK();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'نظر!',
                            'text'=> 'ثبت نشد، مجدد تلاش نمایید.',
                            'icon'=> 'error',
                            'button' => 'ok']
                        );

                    return redirect()->bacK();
                }


            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Comment $comment)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Comment $comment,$id,User $user)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment,$id,User $user,Product $product)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        return abort(404);
    }

}
