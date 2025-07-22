<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\Profile;
use App\Jobs\PaymentJob;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Shetabit\Multipay\Invoice;
use Illuminate\Support\Facades\Gate;
use App\Http\Utilities\PaymentCreate;
use App\Notifications\PaymentNotification;
use Illuminate\Support\Facades\Notification;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index (Request $request,User $user,Payment $payment,Company $company,Route $route,Deposit $deposit)
    {
        // Gate::authorize('viewAny', $payment);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $transaction = $request->session()->has('transaction') ? $request->session()->get('transaction'):null;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;
        $payments = $payment->where('user_id',auth()->user()->id);
        $transactions = $deposit->where('user_id',auth()->user()->id)->union($payments)->orderBy('created_at','desc')->paginate(9);
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);
        // dd($wallet );
        return Inertia::render('Users/Buyer/Mali/payment-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'transactions' => $transactions,'transaction' => $transaction,'statuses' => $statuses,'ids' => $ids,'prices'=> $prices,
            'users' => $users,'notifications'=>$notifications,'companies'=>$companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Bank $bank,Company $company,Route $route)
    {

        // Gate::authorize('viewAny', $payment);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $banks = $bank->with('menu')->where('user_id',auth()->user()->id)->where('status',4)->get();
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $time = Carbon::now();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
         $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus ?
            $route->where('name',$request->path())->first()->menus:null;
        $wallet = Wallet::all($users);
        // dd($wallet );
        return Inertia::render('Users/Buyer/Mali/payment-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'banks' => $banks,'alert'=>$alert,'path'=>$request->path(),
            'users' => $users,'notifications'=>$notifications,'now'=> $time,'companies'=>$companies,'menus'=>$menus,'descriptions'=>$descriptions,'wallet'=>$wallet
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $user,Payment $payment,Profile $profile,Role $role,
    Company $company)
    {
        // Gate::authorize('viewAny', $payment);
        $companies = $company->first();
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $wallet = Wallet::all($users);

        // dd($request);
        if($request->id)
        {
            // Gate::authorize('viewAny', $payment);
            $users = $user->with('file')->with('profile')->find(auth()->user()->id);
            if($users->profile->wallet >= $request->wallet  )
            {

                if($users->profile->wallet + $payment->price >= $request->price )
                {

                    // $users->profile->update([
                    //     'wallet'=> $users->profile->wallet + ($payment->price - $request->price)
                    // ]);

                    $payment->update([
                        'transaction'=>$request->transaction,
                        'price'=>$request->price,
                        'bank_name'=>$request->bank,
                        'account_name'=>$request->accountName,
                        'cart_number'=>0,
                        'code_p'=> 0,
                        'code_e'=>0,
                        'date'=>$request->date,
                        'financialable_type'=>User::class,
                        'financialable_id'=>auth()->user()->id,
                    ]);

                    PaymentJob::dispatch($payment->find($request->id))->delay(now()->addMinute(5));
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'برداشت!',
                            'text'=> 'باموفقیت بروز شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );

                    return redirect()->back();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'برداشت!',
                            'text'=> 'موجودی کافی نمی باشد.',
                            'icon'=> 'error',
                            'button' => 'ok']
                        );
                    return redirect()->back();
                }

            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'برداشت!',
                        'text'=> 'موجودی کافی نمی باشد.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
        }
        if($request->transaction['name'] == 'برداشت')
        {

            if($wallet >= $request->price)
            {

                $payments = PaymentCreate::all($request);

                PaymentJob::dispatch($payments)->delay(now()->addMinute($companies->job));
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'برداشت!',
                        'text'=> 'باموفقیت ثبت شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                    );
                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'برداشت!',
                        'text'=> 'ثبت نشد، موجوی کیف پول کافی نمی باشد.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
                return redirect()->back();
            }

        }
        else
        {
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment,User $user,Bank $bank,Request $request,Company $company,Route $route)
    {
        Gate::authorize('viewAny', $payment);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->findOrfail(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $time = new Carbon;
        $companies = $company->with('image')->first();
        $banks = $bank->where('user_id',auth()->user()->id)->get();
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $descriptions = $route->where('name','route(payment.edit)')->first() && $route->where('name','route(payment.edit)')->first()->descriptions?
         $route->where('name','route(payment.edit)')->first()->descriptions->first():null;
        $menus = $route->where('name','route(payment.edit)')->first() && $route->where('name','route(payment.edit)')->first()->menus ?
            $route->where('name','route(payment.edit)')->first()->menus:null;
        $wallet = Wallet::all($users);

        if($payment->status == 0 )
        {

            return Inertia::render('Users/Buyer/Mali/payment-edit',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'banks' => $banks,'alert'=>$alert,'users' => $users,'notifications'=>$notifications,'payment'=>$payment,'companies'=>$companies,
            'descriptions'=>$descriptions,'path'=>'route(payment.edit)','menus'=>$menus,'now'=> $time,'payment'=>$payment,'wallet'=>$wallet
            ]);
        }
        else
        {
            abort(401);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment,User $user,Profile $profile)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Payment $payment,User $user)
    {
        Gate::authorize('viewAny', $payment);
        $users = $user->with('file')->with('profile')->findOrfail(auth()->user()->id);

        if($payment->status == 0 && $payment->user_id == $users->id)
        {
            // $payment->delete();
            $payment->update([
                'status'=> 3
            ]);

            $users->profile->update([
                'wallet'=> $users->profile->wallet + $payment->price
            ]);

            $request->session()->flash(
                'alert' ,[
                    'title'=>'درخواست!',
                    'text'=> 'باموفقیت حذف شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
        else
        {
           return abort(401);
        }
    }

    /**
     * verify the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request,Payment $payment)
    {
        dd($request);
        if($request->Status == 'OK')
        {


            // dd(Financial::where('transactionId',$request->Authority)->first(),$request->Authority,auth()->user());
            $financial = $payment->where('transactionId',$request->Authority)->first()->update([
                'status'=> 4
            ]);
            if($financial)
            {
                User::find($payment->where('transactionId',$request->Authority)->first()->user_id)->profile->update([
                    'wallet'=> (User::find($payment->where('transactionId',$request->Authority)->first()->user_id)->profile->wallet+
                    $payment->where('transactionId',$request->Authority)->first()->price)
                ]);

                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!تراکنش',
                        'text'=> '.تراکنش باموفقیت انجام شد',
                        'icon'=> 'success',
                        'button' => 'ok']
                );

                return redirect()->route('dashboard.index');
            }
            else
            {
                $flash = $request->session()->flash(
                    'message' , '.مشکلی پیش آمده به پشتیبانی تماس حاصل فرمایید'
                );
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!تراکنش',
                        'text'=> '.مشکلی پیش آمده به پشتیبانی تماس حاصل فرمایید',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
                return redirect()->route('dashboard.index');
            }

        }
        else
        {
            $financial = $payment->where('transactionId',$request->Authority)->first()->update([
                'status'=> 3
            ]);

            $request->session()->flash(
                'alert' ,[
                    'title'=>'!تراکنش',
                    'text'=> '.تراکنش ناموفق.لطفا مجدد تلاش نمایید',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->route('dashboard.index');
        }
    }
}
