<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Product;
use App\Models\Discount;
use App\Models\Description;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DiscountVisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Discount $discount,Company $company,Route $route,Product $product)
    {
        $time = Carbon::now('+3:30');
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $asidemini = $request->query->has('asidemini') ? $request->query->get('asidemini') :null;
        // $dark = $request->query->has('dark') ? $request->query->get('dark') :null;
        $dark = request()->session()->get('class') ? request()->session()->get('class') :null;
        $users = $user->with('image')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;

        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $dicounts = $discount->with('discountable')->where('user_id',$users->id)->orderBy('created_at','desc')->paginate(9);
        $menus = $product->where('user_id',$users->id)->get()?$product->where('user_id',$users->id)->where('status',4)->get(): null;
        // dd($dicounts);

        $subject = $request->query->get('subject') == null?'All':$request->query->get('subject');
        $status = $request->query->get('status') == null? 'All' : $request->query->get('status');
        $times = $request->query->get('time') == null?'All':$request->query->get('time');

        if($times !== 'All' && $status == 'All' &&  $subject == 'All')
        {


                $dicounts = $discount->with('discountable')->where('user_id',$users->id)->where([
                    ['expired','>=',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                    ['expired','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]
                    ])->orderBy('created_at','desc')->paginate(9);

                    return Inertia::render('Users/Seller/Discount/discount-index',['cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,
                    'cartCoupon' => $cart->coupon,'cartTotal' => $cart->total,'alert'=>$alert,'users' => $users,'cartPrice'=>$cart->price,
                    'notifications'=> $notifications,'dark'=>$dark,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=> $menus,
                    'asidemini'=>$asidemini,'path'=>$request->path(),'discounts'=>$dicounts,'time'=>$time,'subjects'=> $subject,'times'=> $times,
                    'statuses'=> $status]);
        }
        elseif($status !== 'All' && $times == 'All' &&  $subject == 'All')
        {
                // dd($times);
                $dicounts = $status == 4?$discount->with('discountable')->where('user_id',$users->id)->where('expired','>',$time)->orderBy('created_at','desc')->paginate(9):$discount->with('discountable')->where('user_id',$users->id)->where('expired','<',$time)->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Seller/Discount/discount-index',['cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,
                'cartCoupon' => $cart->coupon,'cartTotal' => $cart->total,'alert'=>$alert,'users' => $users,'cartPrice'=>$cart->price,
                'notifications'=> $notifications,'dark'=>$dark,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=> $menus,
                'asidemini'=>$asidemini,'path'=>$request->path(),'discounts'=>$dicounts,'time'=>$time,'subjects'=> $subject,'times'=> $times,
                'statuses'=> $status]);

        }
        elseif($subject !== 'All' && $times == 'All' && $status == 'All')
        {
            if($subject == 'All')
            {

                return Inertia::render('Users/Seller/Discount/discount-index',['cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,
                'cartCoupon' => $cart->coupon,'cartTotal' => $cart->total,'alert'=>$alert,'users' => $users,'cartPrice'=>$cart->price,
                'notifications'=> $notifications,'dark'=>$dark,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=> $menus,
                'asidemini'=>$asidemini,'path'=>$request->path(),'discounts'=>$dicounts,'time'=>$time,'subjects'=> $subject,'times'=> $times,
                'statuses'=> $status]);
            }
            else
            {

                $dicounts = $discount->with('discountable')->where('user_id',$users->id)->where('discountable_id',$subject)->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Seller/Discount/discount-index',['cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,
                'cartCoupon' => $cart->coupon,'cartTotal' => $cart->total,'alert'=>$alert,'users' => $users,'cartPrice'=>$cart->price,
                'notifications'=> $notifications,'dark'=>$dark,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=> $menus,
                'asidemini'=>$asidemini,'path'=>$request->path(),'discounts'=>$dicounts,'time'=>$time,'subjects'=> $subject,'times'=> $times,
                'statuses'=> $status]);

            }

        }
        else
        {
            if($subject !== 'All' && $status !== 'All' && $times !== 'All')
            {
                $dicounts = $status == 4?$discount->with('discountable')->where('user_id',$users->id)->where('expired','>',$time)
                ->where('discountable_id',$subject)->where([['expired','>=',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                ['expired','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]])->orderBy('created_at','desc')->paginate(9)
                :
                $discount->with('discountable')->where('user_id',$users->id)->where('expired','<',$time)->where([['expired','>=',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                ['expired','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]])->where('discountable_id',$subject)->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Seller/Discount/discount-index',['cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,
                'cartCoupon' => $cart->coupon,'cartTotal' => $cart->total,'alert'=>$alert,'users' => $users,'cartPrice'=>$cart->price,
                'notifications'=> $notifications,'dark'=>$dark,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=> $menus,
                'asidemini'=>$asidemini,'path'=>$request->path(),'discounts'=>$dicounts,'time'=>$time,'subjects'=> $subject,'times'=> $times,
                'statuses'=> $status]);
            }
            else if($subject !== 'All' && $status !== 'All')
            {
                $dicounts = $status == 4?$discount->with('discountable')->where('user_id',$users->id)->where('expired','>',$time)
                ->where('discountable_id',$subject)->orderBy('created_at','desc')->paginate(9)
                :
                $discount->with('discountable')->where('user_id',$users->id)->where('expired','<',$time)->where('discountable_id',$subject)->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Seller/Discount/discount-index',['cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,
                'cartCoupon' => $cart->coupon,'cartTotal' => $cart->total,'alert'=>$alert,'users' => $users,'cartPrice'=>$cart->price,
                'notifications'=> $notifications,'dark'=>$dark,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=> $menus,
                'asidemini'=>$asidemini,'path'=>$request->path(),'discounts'=>$dicounts,'time'=>$time,'subjects'=> $subject,'times'=> $times,
                'statuses'=> $status]);
            }
            else if($subject !== 'All' && $times !== 'All')
            {

                $dicounts = $discount->with('discountable')->where('user_id',$users->id)->where('discountable_id',$subject)->where([
                    ['expired','>=',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                    ['expired','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]
                    ])->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Seller/Discount/discount-index',['cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,
                'cartCoupon' => $cart->coupon,'cartTotal' => $cart->total,'alert'=>$alert,'users' => $users,'cartPrice'=>$cart->price,
                'notifications'=> $notifications,'dark'=>$dark,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=> $menus,
                'asidemini'=>$asidemini,'path'=>$request->path(),'discounts'=>$dicounts,'time'=>$time,'subjects'=> $subject,'times'=> $times,
                'statuses'=> $status]);
            }
            else if($status !== 'All' && $times !== 'All')
            {

                $dicounts = $status == 4?$discount->with('discountable')->where('user_id',$users->id)->where('expired','>',$time)
                ->where([['expired','>=',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                ['expired','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]])->orderBy('created_at','desc')->paginate(9)
                :
                $discount->with('discountable')->where('user_id',$users->id)->where('expired','<',$time)->where([
                    ['expired','>=',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                    ['expired','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]
                    ])->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Seller/Discount/discount-index',['cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,
                'cartCoupon' => $cart->coupon,'cartTotal' => $cart->total,'alert'=>$alert,'users' => $users,'cartPrice'=>$cart->price,
                'notifications'=> $notifications,'dark'=>$dark,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=> $menus,
                'asidemini'=>$asidemini,'path'=>$request->path(),'discounts'=>$dicounts,'time'=>$time,'subjects'=> $subject,'times'=> $times,
                'statuses'=> $status]);

            }
            else
            {
                // dd($dicounts);
                return Inertia::render('Users/Seller/Discount/discount-index',['cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,
                'cartCoupon' => $cart->coupon,'cartTotal' => $cart->total,'alert'=>$alert,'users' => $users,'cartPrice'=>$cart->price,
                'notifications'=> $notifications,'dark'=>$dark,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=> $menus,
                'asidemini'=>$asidemini,'path'=>$request->path(),'discounts'=>$dicounts,'time'=>$time,'subjects'=> $subject,'times'=> $times,
                'statuses'=> $status]);
            }


        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Product $product,Discount $discount,Company $company,Route $route)
    {
        Gate::authorize('viewAny', $discount);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $updated = $request->get('updated');
        $time = Carbon::now();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;// $description->where('route',$request->path())->first();
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus ?
            $route->where('name',$request->path())->first()->menus:null;

        $subject = $request->query->get('subject') == null?'All':$request->query->get('subject');
        // dd($menus,$subject);
        if( $subject == 'All')
        {
            $results = $product->with('type')->with('image')->where('status',4)->where('user_id',$users->id)->paginate(9);

            return Inertia::render('Users/Seller/Discount/discount-create',['results'=> $results,'cartCount'=> $cart->count,
            'alert'=>$alert,'time'=>$time ,'users' => $users,'notifications'=> $notifications,'companies'=>$companies,
            'descriptions'=>$descriptions,'path'=>$request->path(),'subjects'=> $subject,'menus' => $menus]);
        }
        else
        {
            $results = $product->with('type')->with('image')->where('type',$subject)->where('user_id',auth()->user()->id)->paginate(9);

            return Inertia::render('Users/Seller/Discount/discount-create',['results'=> $results,'cartCount'=> $cart->count,
            'alert'=>$alert,'time'=>$time ,'users' => $users,'notifications'=> $notifications,'companies'=>$companies,
            'descriptions'=>$descriptions,'path'=>$request->path(),'subjects'=> $subject,'menus' => $menus]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Discount $discount)
    {
        // dd($request);
        $time = Carbon::now('+3:30');
        if ($request->results)
        {
            foreach ($request->results as $key => $result)
            {
                $discou = $discount->where('discountable_type', Product::class)->where('discountable_id',$result)->first();

                if($discou)
                {
                    Gate::authorize('viewAny', $discou);
                }
            }
        }


            $request->validate([
                'results'=> 'required',
                'expired'=> 'required',
                'percent_min'=> 'required',
                'percent_max'=> 'required',
            ]);

            foreach ($request->results as $key => $result)
            {
                $discoun = $discount->where('discountable_type', Product::class)->where('discountable_id',$result)->latest()->first();

                if($discoun == null || $discoun && $time > $discoun->expired)
                {
                    if (Product::find($result)->user_id == auth()->user()->id)
                    {

                        $discountss = $discount->create([
                            'user_id' => auth()->user()->id,
                            'expired' => $request->expired,
                            'percent'=> rand($request->percent_min,$request->percent_max),
                            'discountable_type'=> Product::class,
                            'discountable_id'=>$result,
                        ]);

                        if($discountss)
                        {
                            $request->session()->flash(
                                'alert' ,[
                                    'title'=>'تخفیف!',
                                    'text'=> 'باموفقیت ایجاد شد.',
                                    'icon'=> 'success',
                                    'button' => 'ok']
                                );

                                return redirect()->back();
                        }
                        else
                        {
                            $request->session()->flash(
                                'alert' ,[
                                    'title'=>'تخفیف!',
                                    'text'=> 'ایجاد نشد، مشکلی پیش آمده بعدا مجددا تلاش نمایید.',
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
                                'title'=>'تخفیف!',
                                'text'=> 'شما مجوز صدور تخفیف به این محصول را ندارید.',
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
                            'title'=>'تخفیف!',
                            'text'=> 'ایجاد نشد، تخفیف این محصول فعال می باشد.',
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Product $product,Discount $discount)
    {
        dd($id,$product->find($id),$discount->all());
        Gate::authorize('view', $product->find($id)->discount);

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
