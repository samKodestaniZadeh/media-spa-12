<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Routeable;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Http\Utilities\RouteCreate;
use Illuminate\Support\Facades\Gate;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Company $company,Route $route)
    {
        // Gate::authorize('create',$section);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $routes = $route->with('user')->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/route-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'routes'=> $routes,
            'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Company $company,Route $route)
    {
        Gate::authorize('create',$route);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/route-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications
            ,'companies' => $companies,'descriptions'=>$descriptions,'path'=>$request->path(),'wallet'=>$wallet
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $user,Route $route)
    {

        Gate::authorize('create',$route);
        $routes = RouteCreate::all($request);

        if($routes)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'روت!',
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
                    'title'=>'روت!',
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
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Company $company,Route $route,Routeable $sub)
    {
        Gate::authorize('create',$route);

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $routes = $route->find($route->id);
        $subs = $sub->where('route_id',$route->id)->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(menu.show)')->first() && $route->where('name','route(menu.show)')->first()->descriptions?
            $route->where('name','route(menu.show)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/route-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications,
            'menus'=> $routes,'companies' => $companies,'descriptions'=>$descriptions,'subs'=>$subs,'wallet'=>$wallet
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function edit(Route $route)
    {
       return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Routeable $routeable,Route $route)
    {
        Gate::authorize('create',$route);
        if($request->del)
        {

            // dd($request);
            $routes = $routeable->where('route_id',$request->id )->
            where('routeable_type',$request->type)->
            where('routeable_id',$request->del)->delete();
            // $routeables = $route->find($request->id)->sub()->detach($routes);
            // dd($routes,$routeables);
            if($routes)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'زیرمجموع!',
                        'text'=> 'باموفقیت حذف شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                    );
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'زیرمجموع!',
                        'text'=> 'حذف نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );
            }
            return redirect()->back();
        }
        else
        {
            $request->validate([
                'id' => 'required|numeric',
                'routeable_type' => 'required',
                'routeable_id' => 'required|numeric',
            ]);

            $routes = $routeable->updateorcreate([
                'route_id'=> $request->id,
                'routeable_type' => $request->routeable_type ,
                'routeable_id' => $request->routeable_id,
            ]);

            if($routes)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'زیرمجموع!',
                        'text'=> 'باموفقیت ثبت شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                    );
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'زیرمجموع!',
                        'text'=> 'ثبت نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );
            }
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function destroy(Route $route,Request $request)
    {
        // dd($route,$request);
        Gate::authorize('create',$route);

        $routes = $route->delete();

        if($routes)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'!روت',
                    'text'=> '.باموفقیت حذف شد',
                    'icon'=> 'success',
                    'button' => 'ok']
            );
            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'!روت',
                    'text'=> '.حذف نشد، مجدد تلاش نمایید',
                    'icon'=> 'error',
                    'button' => 'ok']
                );
                return redirect()->back();
        }

    }
}
