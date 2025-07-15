<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bank;
// use App\Models\Payment;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Bankname;
use App\Models\Financial;
use App\Models\Description;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentModirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Financial $payment,User $user,Company $company,Route $route)
    {
        return abort(404);
        // Gate::authorize('update', $payment);
        // $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        // $transaction = $request->session()->has('transaction') ? $request->session()->get('transaction'):null;
        // $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        // $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        // $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;
        // $transactions = $payment->where('id','>',0)->orderBy('created_at','desc')->paginate(9);
        // $notifications = $users->unreadnotifications;
        // $id=$request->query();
        // $users->notifications->find($id)->MarkAsRead();
        // $companies = $company->with('image')->first();
        // $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
        //     $route->where('name',$request->path())->first()->descriptions->first():null;

        // return Inertia::render('Users/Modir/Payment/payment-index',['transactions' => $transactions,
        // 'transaction' => $transaction,'statuses' => $statuses,'ids' => $ids,'prices'=> $prices,
        // 'users' => $users,'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions]);
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
    public function store(Request $request,Financial $payment,Profile $profile)
    {
        Gate::authorize('update', $payment);
        $request->validate([
            'transaction'=> 'required',
            'price'=> 'required',
            'date'=> 'required',
            'bankname'=> 'required',
            'accountName'=> 'required',
            'user'=> 'required',
        ]);

        if($request->id)
        {

            if($request->transaction=='واریز')
            {
                $payments = $payment->find($request->id)->update([
                    'user_id'=>$request->user,
                    'transaction'=>$request->transaction,
                    'price'=>$request->price + $payment->find($request->id)->price,
                    'date'=>$request->date,
                    'bank_name'=>$request->bankname,
                    'account_name'=>$request->accountName,
                    'paymentable_type'=>User::class,
                    'paymentable_id'=>auth()->user()->id,
                    'status'=>$request->status,
                ]);

                if($payments)
                {
                    $profile->where('user_id',$request->user)->update([
                        'wallet'=> $profile->where('user_id',$request->user)->first()->wallet + $request->price
                    ]);

                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'!واریز',
                            'text'=> '.با موفقیت انجام شد',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );

                    return redirect()->route('dashboard.index');
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'!واریز',
                            'text'=> '.انجام نشد',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );

                    return redirect()->route('dashboard.index');
                }

            }
            else if($request->transaction=='برداشت')
            {

                if($payment->find($request->id)->price > $request->price)
                {
                    $payments = $payment->find($request->id)->update([
                        'user_id'=>$request->user,
                        'transaction'=>'برداشت',
                        'price'=>($payment->find($request->id)->price - $request->price),
                        'date'=>$request->date,
                        'bank_name'=>$request->bankname,
                        'account_name'=>$request->accountName,
                        'paymentable_type'=>User::class,
                        'paymentable_id'=>auth()->user()->id,
                        'status'=>$request->status,
                    ]);
                    if($payments)
                    {
                        $profile->where('user_id',$request->user)->update([
                            'wallet'=> $profile->where('user_id',$request->user)->first()->wallet - $request->price
                        ]);

                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'!برداشت',
                                'text'=> '.با موفقیت انجام شد',
                                'icon'=> 'success',
                                'button' => 'ok']
                        );

                        return redirect()->route('dashboard.index');
                    }
                    else
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'!برداشت',
                                'text'=> '.انجام نشد',
                                'icon'=> 'error',
                                'button' => 'ok']
                        );

                        return redirect()->route('dashboard.index');
                    }

                }
                else if($payment->find($request->id)->price == $request->price)
                {
                    $payments = $payment->find($request->id)->update([
                        'user_id'=>$request->user,
                        'transaction'=>'برداشت',
                        'price'=>($payment->find($request->id)->price),
                        'date'=>$request->date,
                        'bank_name'=>$request->bankname,
                        'account_name'=>$request->accountName,
                        'paymentable_type'=>User::class,
                        'paymentable_id'=>auth()->user()->id,
                        'status'=>$request->status,
                    ]);
                    if($payments)
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'!برداشت',
                                'text'=> '.با موفقیت انجام شد',
                                'icon'=> 'success',
                                'button' => 'ok']
                        );
                        return redirect()->route('dashboard.index');
                    }
                    else
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'!برداشت',
                                'text'=> '.انجام نشد',
                                'icon'=> 'error',
                                'button' => 'ok']
                        );

                        return redirect()->route('dashboard.index');
                    }

                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'!برداشت',
                            'text'=> '.انجام نشد',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );

                    return redirect()->route('dashboard.index');
                }

            }
            else
            {
                return abort(404);
            }

        }
        else
        {
            $payments = $payment->create([
                'user_id'=>$request->user,
                'transaction'=>$request->transaction,
                'price'=>$request->price,
                'date'=>$request->date,
                'bank_name'=>$request->bankname,
                'account_name'=>$request->accountName,
                'paymentable_type'=>User::class,
                'paymentable_id'=>auth()->user()->id,
                'status'=>4
            ]);

            if($payments)
            {
                $profile->where('user_id',$request->user)->update([
                    'wallet'=> $profile->where('user_id',$request->user)->first()->wallet + $request->price
                ]);

                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!درخواست',
                        'text'=> '.با موفقیت انجام شد',
                        'icon'=> 'success',
                        'button' => 'ok']
                );

                return redirect()->route('dashboard.index');
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!درخواست',
                        'text'=> '.انجام نشد',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->route('dashboard.index');
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Financial $payment,User $user,Bankname $bankname,$id)
    {
        return abort(404);
        // Gate::authorize('update', $payment);
        // $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        // $transaction = $request->session()->has('transaction') ? $request->session()->get('transaction'):null;
        // $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        // $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        // $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;
        // $transactions = $payment->with('user')->with('paymentable')
        // ->where('financialable_type',Product::class)->where('user_id',$id)
        // ->OrWhere('financialable_id',$id)->where('financialable_type',User::class)->paginate(9);
        // $notifications = $users->unreadnotifications;
        // $karbars = $user->all();
        // $banknames = $bankname->all();

        // return Inertia::render('Users/Modir/Payment/Payment-show',['transactions' => $transactions,
        // 'transaction' => $transaction,'statuses' => $statuses,'ids' => $ids,'prices'=> $prices,'karbars'=>$karbars,
        // 'users' => $users,'banknames'=>$banknames,'notifications'=> $notifications]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Financial $payment,User $user,Company $company,Route $route,Bank $bank,$id)
    {
        return abort(404);
        // Gate::authorize('update', $payment);
        // $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        // $transaction = $request->session()->has('transaction') ? $request->session()->get('transaction'):null;
        // $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        // $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        // $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;
        // $transactions = $payment->with('user')->with('paymentable')->find($id);
        // $notifications = $users->unreadnotifications;
        // $karbars = $user->all();
        // $banks = $bank->all();
        // $companies = $company->with('image')->first();
        // $time = new Carbon;
        // $descriptions = $route->where('name','route(paymentModir.edit)')->first() && $route->where('name','route(paymentModir.edit)')->first()->descriptions?
        //     $route->where('name','route(paymentModir.edit)')->first()->descriptions->first():null;
        // $menus = $route->where('name', 'route(paymentModir.edit)')->first() && $route->where('name','route(paymentModir.edit)')->first()->menus ?
        //     $route->where('name', 'route(paymentModir.edit)')->first()->menus:null;

        // return Inertia::render('Users/Modir/Payment/Payment-edit',['transactions' => $transactions,'banks' => $banks,
        // 'transaction' => $transaction,'statuses' => $statuses,'ids' => $ids,'prices'=> $prices,'karbars'=>$karbars,
        // 'users' => $users,'menus'=>$menus,'notifications'=> $notifications,'companies'=>$companies,
        // 'now'=>$time->addDay(2),'descriptions'=>$descriptions]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Financial $payment,User $user,$id)
    {
        return abort(404);
        // Gate::authorize('update', $payment);

        // $request->validate([
        //     'id'=> 'required',
        //     'status'=> 'required',
        // ]);

        // $payments = $payment->find($request->id)->update([
        //     'status'=>$request->status
        // ]);

        // if($payments)
        // {
        //     $request->session()->flash(
        //         'alert' ,[
        //             'title'=>'!درخواست',
        //             'text'=> '.با موفقیت انجام شد',
        //             'icon'=> 'success',
        //             'button' => 'ok']
        //     );

        //     return redirect()->route('dashboard.index');
        // }
        // else
        // {
        //     $request->session()->flash(
        //         'alert' ,[
        //             'title'=>'!درخواست',
        //             'text'=> '.انجام شد.',
        //             'icon'=> 'error',
        //             'button' => 'ok']
        //     );

        //     return redirect()->route('dashboard.index');
        // }


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

    public function search(Request $request,Financial $payment)
    {
        return abort(404);
    //     Gate::authorize('update', $payment);
    //     $transaction = $request->query->get('transaction');
    //     $statu = $request->query->get('status');
    //     $id = $request->query->get('id');
    //     $price = $request->query->get('price');

    //    if($transaction == null && $statu == null && $id ==null && $price == null)
    //    {
    //         $request->validate([
    //             'id'=> 'required',
    //             'transaction'=> 'required',
    //             'status' => 'required',
    //             'price'
    //         ]);
    //     }
    //     elseif($id)
    //     {

    //         $ids = $payment->where('id',$id)->paginate(9)->withQueryString();

    //         return back()->with('ids',$ids);
    //     }
    //     elseif($statu > -1)
    //     {

    //             $statuses = $payment->where('status',$statu)->paginate(9)->withQueryString();

    //             return back()->with('statuses',$statuses);
    //     }
    //     elseif($transaction)
    //     {
    //         $transaction = $payment->where('transaction',$transaction)->paginate(9)->withQueryString();

    //         return back()->with('transaction',$transaction);
    //     }
    //     elseif($price)
    //     {
    //         $prices = $payment->where('price',$price)->paginate(9)->withQueryString();

    //         return back()->with('prices',$prices);
    //     }
    }
}
