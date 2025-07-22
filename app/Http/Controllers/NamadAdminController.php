<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Models\Namad;
use Illuminate\Support\Facades\Gate;

class NamadAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,namad $namad,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $namads = $namad->with('menu')->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()?
            $route->where('name',$request->path())->first()->menus:null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/namad-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'namads'=> $namads,
            'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'menus'=>$menus,
            'path'=>$request->path(),'wallet'=>$wallet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Route $route,namad $namad)
    {
        // Gate::authorize('create', $social);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())?
            $route->where('name',$request->path())->first()->menus:null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/namad-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'users' => $users,'alert' => $alert,'menus'=>$menus,
        'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Namad $namad)
    {
        // dd($request);
        if($request->id)
        {
            // dd($request);
            $request->validate([
                'tag'=>'required',
                'status'=>'required'
            ]);

            $namads = $namad->find($request->id)->update([
                'user_id'=> auth()->user()->id,
                'title'=>$request->title,
                'tag'=>$request->tag,
                'status'=>$request->status,
            ]);

            if($namads)
            {
                $request->session()->flash(
                    'alert',[
                        'title'=>'نماد!',
                        'text'=> 'باموفقیت بروزرسانی شد.',
                        'icon'=> 'success',
                        'button' => 'ok'
                    ]);

                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert',[
                        'title'=>'نماد!',
                        'text'=> 'بروزرسانی نشد، مجدد تلاش نمایید',
                        'icon'=> 'error',
                        'button' => 'ok'
                    ]);

                return redirect()->back();
            }
        }
        else
        {
            $request->validate([
                'title'=>'required',
                'tag'=>'required',
            ]);

            $namads = $namad->create([
                'user_id'=> auth()->user()->id,
                'title'=>$request->title,
                'tag'=>$request->tag,
                'status'=>4,
            ]);

            if($namads)
            {
                $request->session()->flash(
                    'alert',[
                        'title'=>'نماد!',
                        'text'=> 'باموفقیت ثبت شد.',
                        'icon'=> 'success',
                        'button' => 'ok'
                    ]);

                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert',[
                        'title'=>'نماد!',
                        'text'=> 'ثبت نشد، مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok'
                    ]);

                return redirect()->back();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Namad $namad, Route $route,$namadAdmin)
    {

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $user = $user->with('file')->with('profile')->with('roles')->find($namadAdmin);
        // dd($namadAdmin);
        $users = User::with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $namads = $namad->with('menu')->find($namadAdmin);
        $companies = User::with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name','users/namadAdmin/create')->first() && $route->where('name','users/namadAdmin/create')?
            $route->where('name','users/namadAdmin/create')->first()->menus:null;
        $wallet = Wallet::all($users);
        // dd($namads,$menus);
        return Inertia::render('Users/Admin/Public/namad-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'namads'=> $namads,
            'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'menus'=>$menus,
            'user' => $user,'path'=>'users/namadAdmin/create','wallet'=>$wallet]);
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
    public function destroy($id,Namad $namad,Request $request)
    {

        $namad = $namad->findOrfail($id);

        Gate::authorize('create',$namad);

        // $request->validate([
        //     'id'=>'required'
        // ]);

        $namads = $namad->find($id)->update([
            'user_id'=>auth()->user()->id,
            'status'=>3
        ]);

        if($namads)
        {
            $request->session()->flash(
                'alert',[
                    'title'=>'نماد!',
                    'text'=> 'باموفقیت بروز رسانی انجام شد.',
                    'icon'=> 'success',
                    'button' => 'ok'
                ]);

            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert',[
                    'title'=>'نماد!',
                    'text'=> 'بروز رسانی انجام نشد. مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok'
                ]);

            return redirect()->back();
        }
    }
}
