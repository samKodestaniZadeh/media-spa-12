<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Profile;
use App\Models\Financial;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;

class NetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index (Request $request,User $user,Payment $payment,Company $company,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $transaction = $request->session()->has('transaction') ? $request->session()->get('transaction'):null;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;
        $transactions = $payment->where('user_id',auth()->user()->id)->paginate(9);
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Buyer/Network/network-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,
            'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,
            'balance'=>$cart->balance],'transactions' => $transactions,'transaction' => $transaction,'statuses' => $statuses,'ids' => $ids,'prices'=> $prices,
            'users' => $users,'notifications'=>$notifications,'companies'=>$companies,'alert'=>$alert,'descriptions'=>$descriptions,'wallet'=>$wallet
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
    public function store(Request $request,User $user)
    {

        $profiles = $user->find(auth()->user()->id)->profile->update([
            'notification'=>$request->notification,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
        ]);

        if ($profiles)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'درخواست!',
                    'text'=> 'باموفقیت انجام شد.',
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
                    'text'=> 'انجام نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );
            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return abort(404);
    }
}
