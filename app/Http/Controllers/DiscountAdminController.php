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
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;

class DiscountAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Discount $discount,Company $company,Route $route)
    {
        Gate::authorize('create', $discount);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;
        $discounts = $discount->orderBy('created_at','desc')->with('discountable')->paginate(9);
        $time = new Carbon;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Discount/discount-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'statuses' => $statuses,
        'ids' => $ids,'prices'=> $prices,'users' => $users,'notifications'=> $notifications,
        'discounts'=> $discounts,'time'=> $time,'cartCount'=> $cart->count,'companies' => $companies,
        'descriptions'=>$descriptions,'path'=>$request->path(),'wallet'=>$wallet]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Product $product,Discount $discount,Company $company,Route $route)
    {
        Gate::authorize('create', $discount);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $updated = $request->get('updated');
        $time = new Carbon;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus ?
            $route->where('name',$request->path())->first()->menus:null;

        $subject = $request->query->get('subject') == null?'All':$request->query->get('subject');
        $wallet = Wallet::all($users);

        if( $subject == 'All')
        {
            $results = $product->with('type')->with('image')->where('status',4)->paginate(9);
            // dd($menus);
            return Inertia::render('Users/Admin/Discount/discount-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'results'=> $results,'cartCount'=> $cart->count,
            'alert'=>$alert,'time'=>$time ,'users' => $users,'notifications'=> $notifications,'companies'=>$companies,
            'descriptions'=>$descriptions,'path'=>$request->path(),'subjects'=> $subject,'menus'=> $menus,'wallet'=>$wallet]);
        }
        else
        {

            $results = $product->with('type')->where('type',$subject)->where('status',4)->paginate(9);

            return Inertia::render('Users/Admin/Discount/discount-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'results'=> $results,'cartCount'=> $cart->count,
            'alert'=>$alert,'time'=>$time ,'users' => $users,'notifications'=> $notifications,'companies'=>$companies,
            'descriptions'=>$descriptions,'path'=>$request->path(),'subjects'=> $subject,'menus'=> $menus,'wallet'=>$wallet]);
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
        Gate::authorize('create', $discount);
        // dd($request);
        $time = new Carbon;
        if($request->number)
        {
            $request->validate([
                'number'=> 'required',
                'expired'=> 'required',
                'percent_min'=> 'required',
                'percent_max'=> 'required',
            ]);

            for ($i=0; $i < $request->number; $i++) {
                $discounts = $discount->create([
                    'user_id' => auth()->user()->id,
                    'expired' => $request->expired,
                    'percent'=> rand($request->percent_min,$request->percent_max),
                    'discountable_type'=> Product::class,
                    'discountable_id'=>$discount->latest()->first()? $discount->latest()->first()->id+1:1,
                ]);
            };


            if($discounts)
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
            $request->validate([
                'results' => 'required|array',
                'expired' => 'required|date',
                'percent_min' => 'required|numeric',
                'percent_max' => 'required|numeric',
            ]);

            $time = Carbon::now();
            $success = false;

            foreach ($request->results as $key => $result) {
                if (is_null($result) || $result === false) {
                    continue; // رد کردن موارد نامعتبر ولی حفظ کلیدها
                }

                $existingDiscount = $discount->where('discountable_type', Product::class)
                    ->where('discountable_id', $key)
                    ->where('expired', '>', $time)
                    ->exists();

                if (!$existingDiscount) {
                    $newDiscount = $discount->create([
                        'user_id' => auth()->user()->id,
                        'expired' => $request->expired,
                        'percent' => rand($request->percent_min, $request->percent_max),
                        'discountable_type' => Product::class,
                        'discountable_id' => $key,
                    ]);

                    if ($newDiscount) {
                        $success = true;
                    }
                }
            }

            // فقط یک بار flash می‌زنیم
            if ($success) {
                $request->session()->flash('alert', [
                    'title' => 'تخفیف!',
                    'text' => 'با موفقیت ایجاد شد.',
                    'icon' => 'success',
                    'button' => 'ok',
                ]);
            } else {
                $request->session()->flash('alert', [
                    'title' => 'تخفیف!',
                    'text' => 'ایجاد نشد، برخی محصولات قبلاً تخفیف دارند یا مشکلی پیش آمده.',
                    'icon' => 'error',
                    'button' => 'ok',
                ]);
            }

            return redirect()->back();


        }


    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Discount $discount,Company $company,Description $description,Route $route,$id)
    {
        Gate::authorize('create', $discount);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;
        $discounts = $discount->with('discountable')->find($id);
        $time = new Carbon;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(discountAdmin.show)')->first() && $route->where('name','route(discountAdmin.show)')->first()->descriptions?
            $route->where('name','route(discountAdmin.show)')->first()->descriptions->first():null;
        $menus = $route->where('name','route(discountAdmin.show)')->first() && $route->where('name','route(discountAdmin.show)')->first()->menus ?
            $route->where('name','route(discountAdmin.show)')->first()->menus:null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Discount/discount-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'statuses' => $statuses,
        'ids' => $ids,'prices'=> $prices,'users' => $users,'notifications'=> $notifications,
        'discounts'=> $discounts,'time'=> $time,'companies' => $companies,'descriptions'=>$descriptions,
        'menus'=> $menus,'wallet'=>$wallet,'path'=>'route(discountAdmin.show)']);
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
    public function update(Request $request, Discount $discount)
    {
        Gate::authorize('create', $discount);
        $request->validate([
            'expired'=> 'required',
            'percent'=> 'required',
        ]);

        $discounts = $discount->find($request->id)->update([
            'user_id' => auth()->user()->id,
            'expired' => $request->expired,
            'percent' => $request->percent,
            'updated_at' => $request->expired,
        ]);
        if($discounts)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'تخفیف!',
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
                    'title'=>'تخفیف!',
                    'text'=> 'بروزرسانی نشد، مجدد تلاش نمایید.',
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
    public function destroy(Request $request,Discount $discount,$id)
    {
        // dd($request,$id);
        Gate::authorize('create', $discount->find($id));

        $discounts = $discount->find($id)->delete();
        if($discounts)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'تخفیف!',
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
                    'title'=>'تخفیف!',
                    'text'=> 'حذف نشد، مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroyAll(Request $request,Discount $discount)
    {
        Gate::authorize('create', $discount);
        if($discount->all()->first())
        {
            $discount->truncate();

            $request->session()->flash(
                'alert' ,[
                    'title'=>'تخفیف!',
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
                    'title'=>'تخفیف!',
                    'text'=> 'حذف نشد، مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }

    }

}
