<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Deposit;
use App\Models\Profile;
use App\Jobs\DepositJob;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;
use App\Http\Utilities\DepositCreate;

class DepositAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Deposit $deposit,User $user,Company $company,Route $route)
    {
        Gate::authorize('create', $deposit);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $transactions = $deposit->with('user')->orderBy('created_at','desc')->paginate(9);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Deposit/Deposit-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
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
    public function create(Request $request,Deposit $deposit,User $user,Company $company,Route $route)
    {
        Gate::authorize('create', $deposit);
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

        return Inertia::render('Users/Admin/Deposit/Deposit-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
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
    public function store(Request $request,Deposit $deposit,Company $company,User $user)
    {
        // dd($request);
        Gate::authorize('create', $deposit);
        $companies = $company->first();
        $deposits = DepositCreate::all($request);

        if($request->id)
        {


            if($deposits)
            {

                $request->session()->flash(
                    'alert' ,[
                        'title'=>'تراکنش!',
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
                        'title'=>'تراکنش!',
                        'text'=> 'بروزرسانی نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }


        }
        else
        {

            if($deposits)
            {
                $message = 'موجودی کیف پول شما افزایش یافت.';
                DepositJob::dispatch($deposits,$message)->delay(now()->addMinute($companies->job));
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
    public function edit(Request $request,Deposit $deposit,User $user,$id,Company $company,Route $route)
    {
        Gate::authorize('create', $deposit);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $karbars = $user->all();
        $banknames = null;
        $transactions = $deposit->with('user')->with('depositable')->find($id);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(paymentAdmin.edit)')->first() && $route->where('name','route(paymentAdmin.edit)')->first()->descriptions?
            $route->where('name','route(paymentAdmin.edit)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Deposit/Deposit-edit',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'karbars'=>$karbars,'users' => $users,
        'transactions' => $transactions,'banknames'=>$banknames,'notifications'=> $notifications,'alert' => $alert,
        'companies'=>$companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
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
