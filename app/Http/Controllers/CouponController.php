<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Coupon;
use App\Models\Company;
use App\Models\Product;
use App\Models\Description;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Coupon $coupon,Company $company,Route $route)
    {
        Gate::authorize('create', $coupon);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;
        $coupons = $coupon->with('user')->with('order')->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Coupon/coupon-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'statuses' => $statuses,
        'ids' => $ids,'prices'=> $prices,'users' => $users,'notifications'=> $notifications,
        'coupons'=> $coupons,'companies' => $companies,'descriptions'=>$descriptions,
        'path'=>$request->path(),'wallet'=>$wallet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Company $company,Coupon $coupon,Route $route)
    {
        Gate::authorize('create', $coupon);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Coupon/coupon-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'users' => $users,'alert'=>$alert,
        'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,
        'path'=>$request->path(),'wallet'=>$wallet]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product ,Coupon $coupon)
    {
        // dd($request->coupon);
        if($request->number)
        {
            $request->validate([
                'number' => 'required|string',
                'number_digits'=>'required|numeric',
                'min'=> 'required|numeric',
                'max'=> 'required|numeric',
            ]);

            $myChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                for ($i=0; $i < $request->number; $i++) {
                    $coupons = $coupon->create([
                        'user_id' => null,
                        'code' => substr( str_shuffle($myChars), 7, 10 ),
                        'price'=> rand($request->min,$request->max)* $request->number_digits,
                        // 'couponable_type'=> Product::class,
                        // 'couponable_id'=>$coupon->latest()->first() ? ($coupon->latest()->first()->id+1):1,
                    ]);
                };

            $request->session()->flash(
            'alert' ,[
                'title'=>'بن تخفیف!',
                'text'=> 'باموفقیت ایجاد شد.',
                'icon'=> 'success',
                'button' => 'ok']
            );

            return redirect()->back();

        }
        elseif($request->coupon)
        {

            $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
            $cart = new Cart($oldCart);

            $request->validate([
                'coupon' => 'required',
            ]);


            $codes = $coupon->where('code',$request->coupon)->where('user_id',null)->where('expired_at',null)->first();

            if($codes)
            {

                $mutable = new Carbon;
                $coupons = $coupon->where('user_id',auth()->user()->id)->latest()->first();
                $dt = Carbon::parse($coupon->where('user_id',auth()->user()->id)->max('expired_at'));

                if($coupons == null || $dt->addDays(1) <= $mutable)
                {

                    $cart->updateCoupon($cart,$coupon->find($codes->id));

                    $request->session()->put('cart',$cart);

                    $coupon->find($codes->id)->update([
                        'user_id' =>auth()->user()->id,
                        'expired_at' => $mutable
                    ]);

                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'بن تخفیف!',
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
                            'title'=>'بن تخفیف!',
                            'text'=> 'سقف استفاده بن تخفیف روزانه برای امروز به اتمام رسیده است.',
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
                        'title'=>'بن تخفیف!',
                        'text'=> 'کد وارد شده منقضی شده است.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back()->withInput();

            }

        }
        else
        {

            $request->validate([
                'code' => 'required',
                'price'=> 'required',
            ]);
            $coupons = $coupon->where('code',$request->code)->first();

            if($coupons)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'بن تخفیف!',
                        'text'=> 'کد وارد شده قبلا ایجاد شده است.',
                        'icon'=> 'error',
                        ]
                    );
                    return redirect()->back();
            }
            else
            {
                $coupon->create([
                    'code'=> $request->code,
                    'price'=>$request->price,
                ]);

                $request->session()->flash(
                'alert' ,[
                    'title'=>'بن تخفیف!',
                    'text'=> 'باموفقیت ایجاد شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
                );

                return redirect()->back();
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon,Request $request)
    {
        Gate::authorize('create', $coupon);
        if($coupon->order_id == null)
        {
            $coupon->delete();

            $request->session()->flash(
                'alert' ,[
                    'title'=>'بن تخفیف!',
                    'text'=> 'با موفقیت حذف شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'بن تخفیف!',
                    'text'=> 'استفاده شده و امکان حذف وجود ندارد.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );
            return redirect()->back();
        }

    }
}
