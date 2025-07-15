<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Jobs\BankJob;
use App\Models\Route;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Http\Utilities\BankCreate;
use App\Http\Utilities\ImageCreate;
use Illuminate\Support\Facades\Gate;

class BankAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Bank $bank,User $user,Route $route)
    {
        Gate::authorize('create', $bank);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $banks = $bank->with('menu')->with('user')->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);
        // dd($cart);
        return Inertia::render('Users/Admin/Payment/bank-index',['banks' => $banks,'users' => $users,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
        'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'alert' => $alert,
        'wallet'=>$wallet
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
    public function store(Request $request,Company $company,Bank $bank)
    {
        Gate::authorize('create', $bank);
        // dd($request);
        if ($request->id)
        {


            $companies = $company->first();
            $banks = BankCreate::all($request);

            if($banks)
            {
                $banks = Bank::find($request->id);
                BankJob::dispatch($banks)->delay(now()->addMinute($companies->job));
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'حساب!',
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
                        'title'=>'حساب!',
                        'text'=> 'بروز رسانی نشد، مجدد تلاش نمایید.',
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
    public function show(Request $request,User $user,Bank $Bank,Route $route,$id)
    {
        Gate::authorize('create', $Bank);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $banks = $Bank->with('menu')->with('image')->findOrFail($id);
        $notifications = $users->unreadnotifications;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
         $route->where('name',$request->path())->first()->descriptions->first():null;
         $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Payment/bank-show',[
            'banks' => $banks,'users'=> $users,'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,
            'alert'=>$alert,'wallet'=>$wallet,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
        ]);
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
