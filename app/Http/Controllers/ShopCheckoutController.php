<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Coupon;
use App\Models\Company;
use App\Models\Description;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class ShopCheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Coupon $coupon,User $user,Company $company,Route $route)
    {

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $asidemini = $request->query->has('asidemini') ? $request->query->get('asidemini') :null;
        $flash = $request->session()->get('message');
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $dark = $request->query->has('dark') ? $request->query->get('dark') :null;
        $codes = $coupon->all();
        $users = Auth::check()?$user->with('file')->with('profile')->with('roles')->find(auth()->user()->id):null;
        $notifications = Auth::check()? $users->unreadnotifications:null;
        $random_coupon = $codes->where('user_id',0)->first();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $token = $request->session()->token();
        // foreach ($cart->designers as  $designer)
        // dd($users,$cart);
        return Inertia::render('Guest/shop-checkout',['cartPrice'=> $cart->price,'cart'=> $cart,
        'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,'random_coupon'=>$random_coupon,
        'cartTotal' => $cart->total,'products' => $cart->products,'designers' => $cart->designers, 'alert'=> $alert,
        'flash' => $flash,'codes'=> $codes,'users' => $users,'notifications'=> $notifications,'cartTax' => $cart->tax,
        'cartComplications'=> $cart->complications,'companies' => $companies,'cartComison' => $cart->comison,
        'descriptions'=>$descriptions,'dark'=>$dark,'asidemini'=> $asidemini,'path'=>$request->path(),'token'=>$token,
        'cartTax' => $cart->tax,'cartCol' => $cart->col,'cartPayment' => $cart->payment,'cartBalance' => $cart->balance,
        'cartRquest'=>$cart->request]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd(request());
         $request->validate([
            'user_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'lasst_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

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
    public function edit($id)
    {
        //
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
