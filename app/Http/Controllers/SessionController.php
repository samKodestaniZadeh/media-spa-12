<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Session;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Session $session, User $user,Company $company,Route $route)
    {
        Gate::authorize('create', $session);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $subjects = $request->session()->has('subject') ? $request->session()->get('subject') : null;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses') : null;
        $ids = $request->session()->has('id') ? $request->session()->get('id') : null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $all = $session->all()->count();
        $second = $session->subSecond()->count();
        $minute = $session->subMinute()->count();
        $hour = $session->subHour()->count();
        $day = $session->subDay()->count();
        $week = $session->subWeek()->count();
        $month = $session->subMonth()->count();
        $year = $session->subYear()->count();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Session/Session-index', ['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'all' => $all,'second'=>$second,
            'minute'=>$minute,'hour'=>$hour,'day'=>$day,'week'=>$week,'month'=>$month,'year'=>$year,'users' => $users,
            'notifications' => $notifications,'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Session $session
     * @return \Illuminate\Http\Response
     */
    public function show(Session $session)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Session $session
     * @return \Illuminate\Http\Response
     */
    public function edit(Session $session)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Session $session
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Session $session)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Session $session
     * @return \Illuminate\Http\Response
     */
    public function destroy(Session $session)
    {
        return abort(404);
    }
}
