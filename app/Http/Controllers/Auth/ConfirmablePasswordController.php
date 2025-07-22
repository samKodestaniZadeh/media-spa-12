<?php

namespace App\Http\Controllers\Auth;

<<<<<<< HEAD
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password page.
     */
<<<<<<< HEAD
    public function show(): Response
    {
        return Inertia::render('Auth/ConfirmPassword');
=======
    public function show(Request $request,User $user,Route $route,Role $role): Response
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

        return Inertia::render('Auth/ConfirmPassword',[
            'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,
            'roles'=>$roles,'path'=>$request->path(),'wallet'=>$wallet
        ]);
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard.index', absolute: false));
    }
}
