<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\Profile;
use App\Jobs\DepositJob;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;


class PaymentAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Payment $payment,User $user,Company $company,Route $route)
    {
        Gate::authorize('create', $payment);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $transactions = $payment->with('user')->orderBy('created_at','desc')->paginate(9);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Payment/Payment-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'transactions' => $transactions,'users' => $users,
        'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'alert' => $alert,
        'wallet'=>$wallet
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,Payment $payment,User $user,Company $company,Route $route)
    {
        Gate::authorize('create', $payment);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $time = new Carbon();
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $karbars = $user->all();
        $banknames = null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Payment/Payment-create',[
        'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'karbars'=>$karbars,'users' => $users,'alert' => $alert,
        'banknames'=>$banknames,'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'time'=>$time,
        'wallet'=>$wallet
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Payment $payment,Profile $profile,User $user,Company $company)
    {
        Gate::authorize('create', $payment);
        $request->validate([
            'transaction'=> 'required',
            'price'=> 'required|numeric',
            'date'=> 'required|date',
            'cart_number'=> 'required|numeric',
            'code_p'=> 'required|numeric',
            'user'=> 'required',
            'code_e'=> 'required|numeric',
        ]);

        if($request->id)
        {

            $deposits = $deposit->find($request->id)->update([
                'user_id'=>$request->user,
                'transaction'=>$request->transaction,
                'price'=>$request->price + $deposit->find($request->id)->price,
                'date'=>$request->date,
                'bank_name'=>0,
                'account_name'=>0,
                'depositable_type'=>User::class,
                'depositable_id'=>auth()->user()->id ,
                'status'=>$request->status,
                'cart_number'=> $request->cart_number,
                'code_p'=> $request->code_p,
                'code_e'=> $request->code_e,
            ]);

            if($deposits)
            {
                // $profile->where('user_id',$request->user)->update([
                //     'wallet'=> $profile->where('user_id',$request->user)->first()->wallet + $request->price
                // ]);

                $request->session()->flash(
                    'alert' ,[
                        'title'=>'تراکنش!',
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
                        'title'=>'تراکنش!',
                        'text'=> 'ثبت نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }


        }
        else
        {
            $deposits = $deposit->create([
                'user_id'=>$request->user,
                'transaction'=>$request->transaction,
                'price'=>$request->price,
                'date'=>$request->date,
                'bank_name'=>0,
                'account_name'=>0,
                'depositable_type'=>User::class,
                'depositable_id'=>auth()->user()->id,
                'status'=>4,
                'cart_number'=> $request->cart_number,
                'code_p'=> $request->code_p,
                'code_e'=> $request->code_e,
            ]);

            if($deposits)
            {
                // $profile->where('user_id',$request->user)->update([
                //     'wallet'=> $profile->where('user_id',$request->user)->first()->wallet + $request->price
                // ]);
                $companies = $company->first();
                DepositJob::dispatch($deposits)->delay(now()->addMinute($companies->job));
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'تراکنش!',
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
                        'title'=>'تراکنش!',
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
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Payment $payment,User $user,$id,Company $company,Route $route)
    {
        Gate::authorize('create', $payment);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $karbars = $user->all();
        $banknames = null;
        $transactions = $payment->with('user')->with('paymentable')->find($id);
        $walletUser = Wallet::all($transactions->user);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(paymentAdmin.edit)')->first() && $route->where('name','route(paymentAdmin.edit)')->first()->descriptions?
            $route->where('name','route(paymentAdmin.edit)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);
        // dd($walletUser);
        return Inertia::render('Users/Admin/Payment/Payment-edit',[
        'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'karbars'=>$karbars,'users' => $users,
        'transactions' => $transactions,'banknames'=>$banknames,'notifications'=> $notifications,'alert' => $alert,
        'companies'=>$companies,'descriptions'=>$descriptions,'wallet'=>$wallet,'walletUser'=>$walletUser]);
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
