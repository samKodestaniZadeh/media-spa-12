<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Coupon;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Product $product,Coupon $coupon,User $user,Route $route)
    {
        // dd($request->path(),route('guest-cart.index'));
        if(Auth::check())
        {
            return redirect()->route('cart.index');
        }
        else
        {
            $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
            $cart = new Cart($oldCart);
            $flash = $request->session()->get('message');
            $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
            $codes = $coupon->all();
            $random_coupon = $codes->where('user_id',0)->first();
            $companies = $user->with('image')->with('profile')->first();
            $wallet = null;
            $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus ?
            $route->where('name',$request->path())->first()->menus:null;
            // dd($menus);
            if($companies)
            {

                return Inertia::render('Guest/shop-cart',['cartPrice'=> $cart->price,'cartCount'=> $cart->count,
                'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,'cartTotal' => $cart->total,
                'products' => $cart->products,'tarahis' => $cart->tarahis,'alert'=> $alert,'flash' => $flash,
                'random_coupon'=>$random_coupon,'cartTax' => $cart->tax,'cartComplications'=> $cart->complications,
                'companies' => $companies,'cartComison' => $cart->comison,'random_coupon'=>$random_coupon,'wallet'=>$wallet,
                'path'=> $request->path(),'cartTax' => $cart->tax,'cartCol' => $cart->col,'cartPayment' => $cart->payment,
                'cartBalance' => $cart->balance,'menus'=>$menus]);
            }
            else
            {
                return abort(503);
            }
        }
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
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        return abort(404);
    }
}
