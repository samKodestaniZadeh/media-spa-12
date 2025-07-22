<?php

namespace App\Http\Controllers\Auth;

<<<<<<< HEAD
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
=======
use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b

class EmailVerificationPromptController extends Controller
{
    /**
     * Show the email verification prompt page.
     */
<<<<<<< HEAD
    public function __invoke(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : Inertia::render('auth/VerifyEmail', ['status' => $request->session()->get('status')]);
=======
    public function __invoke(Request $request,User $user,Route $route,Role $role): RedirectResponse|Response
    {
        $roles = $role->all();
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('image')->with('profile')->with('payments')->with('deposits')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        // $time = new Carbon();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : Inertia::render('Auth/VerifyEmail', ['status' => $request->session()->get('status'),
                'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,
            'roles'=>$roles,'path'=>$request->path(),'wallet'=>$wallet
        ]);
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
    }
}
