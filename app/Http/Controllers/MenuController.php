<?php

namespace App\Http\Controllers;

use App\Http\Utilities\MenuCreate;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Menu $menu,Company $company,Route $route)
    {
        Gate::authorize('create',$menu);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $menus = $menu->with('user')->with('sections')->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);
        // dd($menus);
        return Inertia::render('Users/Admin/Public/menu-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'menus'=> $menus,
            'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Menu $menu,Company $company,Route $route)
    {
        Gate::authorize('create',$menu);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()?
            $route->where('name',$request->path())->first()->menu:null;
        $wallet = Wallet::all($users);
        // dd($menus);
        return Inertia::render('Users/Admin/Public/menu-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications
            ,'companies' => $companies,'descriptions'=>$descriptions,'path'=>$request->path(),'menus'=>$menus,'wallet'=>$wallet]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $user,Menu $menu)
    {
        // dd(request(),$menu);
        Gate::authorize('create',$menu);

        $menus = MenuCreate::all($request);

        if($menus)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'منو!',
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
                    'title'=>'منو!',
                    'text'=> 'ثبت نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
                );

            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Menu $menu,Company $company,Route $route)
    {
        Gate::authorize('create',$menu);

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $menu = $menu->with('children')->with('sections')->with('descriptions')->with('routes')->with('products')->with('supports')
        ->with('socials')->find($menu->id);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(menu.show)')->first() && $route->where('name','route(menu.show)')->first()->descriptions?
            $route->where('name','route(menu.show)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);
        $menus = $route->where('name','users/menu/create')->first() && $route->where('name','users/menu/create')->first()?
            $route->where('name','users/menu/create')->first()->menu:null;

        return Inertia::render('Users/Admin/Public/menu-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications,
            'menus'=> $menus,'menu'=> $menu,'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        // Gate::authorize('create',$menu);

        // $request->validate([
        //     'id'=> 'required',
        //     'name' => 'required',
        // ]);

        // $menus = $menu->find($request->id)->update([
        //     'user_id'=> auth()->user()->id,
        //     'parent_id' => $request->parent_id,
        //     'name' => $request->name,
        //     'status' => $request->status,
        // ]);

        // if($menus)
        // {
        //     $request->session()->flash(
        //         'alert' ,[
        //             'title'=>'منو!',
        //             'text'=> 'باموفقیت بروز رسانی شد.',
        //             'icon'=> 'success',
        //             'button' => 'ok']
        //         );

        //     return redirect()->back();
        // }
        // else
        // {
        //     $request->session()->flash(
        //         'alert' ,[
        //             'title'=>'منو!',
        //             'text'=> 'بروزرسانی نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
        //             'icon'=> 'error',
        //             'button' => 'ok']
        //         );

        //     return redirect()->back();
        // }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Menu $menu)
    {
        Gate::authorize('create',$menu);

        $menus = $menu->delete();

        if($menus)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'!منو',
                    'text'=> '.باموفقیت حذف شد',
                    'icon'=> 'success',
                    'button' => 'ok']
            );
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'!درخواست',
                    'text'=> '.انجام نشد',
                    'icon'=> 'error',
                    'button' => 'ok']
                );
        }
        return redirect()->back();
    }
}
