<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Identity;
use App\Jobs\IdentityJob;
use Illuminate\Http\Request;
<<<<<<< HEAD
=======
use App\Http\Utilities\Ostan;
use App\Http\Utilities\Shahr;
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IdentityAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return abort(404);
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
    public function store(Request $request ,User $user,Identity $identity)
    {
        Gate::authorize('create', $user);

        $request->validate([
            'status'=> 'required',
        ]);

        $identitys = $identity->find($request->identity)->update([
            'status' => $request->status,
        ]);
        if ($identitys)
        {
            IdentityJob::dispatch($identity->find($request->identity))->delay(now()->addMinute(5));

            $request->session()->flash(
                'alert' ,[
                    'title'=>'اطلاعات هویتی!',
                    'text'=> 'باموفقیت بروزرسانی شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'اطلاعات هویتی!',
                    'text'=> 'بروزرسانی نشد ، مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Company $company,Route $route,$id)
    {
        if(Auth::check())
        {
            $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
            $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
            $cart = new Cart($oldCart);
            $time = new Carbon();
<<<<<<< HEAD
=======
            $ostans = Ostan::all();
            $shahrs = Shahr::all();
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
            $user = $user->with('image')->with('identity')->with('profile')->with('roles')->find($id);
            $users = $user->with('image')->with('identity')->with('profile')->with('roles')->findOrfail(auth()->user()->id);
            $id=$request->query();
            $users->notifications->find($id)->MarkAsRead();
            $notifications = $users->unreadnotifications;
            $companies = $user->with('image')->first();
            $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
                $route->where('name',$request->path())->first()->descriptions->first():null;
            $wallet = Wallet::all($users);

            return Inertia::render('Users/Admin/Profile/Identityuser-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
<<<<<<< HEAD
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
=======
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'ostans'=>$ostans,'shahrs'=>$shahrs,
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
            'users' => $users,'notifications'=> $notifications,'orders_count'=>$user->orders->count(),'companies' => $companies,'cartComison' => $cart->comison,
                'descriptions'=>$descriptions,'alert' => $alert,'user' => $user, 'time' => $time,'wallet'=>$wallet
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return abort(404);
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
