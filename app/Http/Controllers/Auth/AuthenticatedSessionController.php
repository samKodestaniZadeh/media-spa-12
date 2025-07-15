<?php

namespace App\Http\Controllers\Auth;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request,Company $company): Response
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart') : null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $companies = $company->with('image')->first();
        // dd($alert);
        return Inertia::render('Auth/Login2', ['cartCount' => $cart->count,
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),'alert' => $alert,'companies'=>$companies
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request,User $user): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user->find(auth()->user()->id)->update(['status'=> 4]);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
