<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Newsletter;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewsletterNotification;

class NewsletterAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Newsletter $newsletter,User $user,Company $company,Route $route)
    {
        Gate::authorize('create', $newsletter);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $newsletters = $newsletter->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Newsletter/Newsletter-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'newsletters'=> $newsletters,'users' => $users,
        'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'alert' => $alert,
        'wallet'=>$wallet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Newsletter $newsletter,Company $company,Route $route)
    {
        Gate::authorize('create', $newsletter);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Newsletter/Newsletter-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'users' => $users,'alert' => $alert,
        'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Newsletter $newsletter)
    {
        Gate::authorize('create', $newsletter);

        $request->validate([
            'subject'=> 'required',
            'route'=> 'required',
            'text'=> 'required',
        ]);

        if($request->id)
        {
            $newsletters = $newsletter->find($request->id)->update([
                'user_id'=>auth()->user()->id,
                'subject'=>$request->subject,
                'route'=> $request->route,
                'p_route'=> $request->p_route,
                'text'=>$request->text,
                'status'=> $request->status,
            ]);

            if ($newsletters)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'خبرنامه!',
                        'text'=> 'با موفقیت بروزرسانی شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'خبرنامه!',
                        'text'=> 'بروز رسانی نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }

        }
        else
        {
            $newsletters = $newsletter->create([
                'user_id'=>auth()->user()->id,
                'subject'=>$request->subject,
                'route'=> $request->route,
                'p_route'=> $request->p_route,
                'text'=>$request->text,
            ]);

            if ($newsletters)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'خبرنامه!',
                        'text'=> 'با موفقیت ثبت شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'خبرنامه!',
                        'text'=> 'ثبت نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Gate::authorize('viewAny', $newsletter);
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Newsletter $newsletter,User $user,Company $company,Route $route,$id)
    {
        Gate::authorize('create', $newsletter);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $newsletters = $newsletter->find($id);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(newsletterAdmin.edit)')->first() && $route->where('name','route(newsletterAdmin.edit)')->first()->descriptions?
            $route->where('name','route(newsletterAdmin.edit)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Newsletter/Newsletter-edit',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'newsletters'=> $newsletters,'users' => $users,
        'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'alert' => $alert,
        'wallet'=>$wallet
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Newsletter $newsletter,User $user ,$id)
    {
        Gate::authorize('create', $newsletter);

        $newsletters = $newsletter->find($id);
        // dd($newsletters);
        if($newsletters)
        {
            $massage = $newsletters->text;
            $subject = $newsletters->subject;
            $route = $newsletters->route;
            $p_route = $newsletters->p_route;
            $user = $user->all();

            Notification::send($user , new NewsletterNotification($newsletters,$massage,$route,$p_route,$subject,$user));

            $request->session()->flash(
                'alert' ,[
                    'title'=>'درخواست!',
                    'text'=> 'با موفقیت انجام شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'درخواست!',
                    'text'=> 'انجام نشد.',
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
