<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\Page;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Page $page,User $user,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $time = new Carbon;
         $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()?
            $route->where('name',$request->path())->first()->menus:null;
        $menu = Menu::where('parent_id',null)->where('status',4)->with('children','sections','routes')->get();

        $pages = $page->where('route','privacy')->with('user')->first();
        $companies = $user->with('image')->first();
        // dd($pages);
        return  $pages ? Inertia::render('Guest/Privacy',['pages'=>$pages,'companies'=>$companies,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,
                'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,
                'payment'=>$cart->payment,'balance'=>$cart->balance],'menus' => $menus, 'menu' => $menu]) : abort(404);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
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
