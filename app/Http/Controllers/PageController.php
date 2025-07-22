<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Page;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Page $page,Company $company,Route $route)
    {
        Gate::authorize('create', $page);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $pages = $page->with('user')->orderBy('created_at','desc')->paginate();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/Page-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'pages'=>$pages,'users'=>$users,'alert' => $alert,
        'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Page $page,Company $company,Route $route)
    {
        Gate::authorize('create', $page);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/Page-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'users'=>$users,'companies' => $companies,'alert' => $alert,
        'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Page $page)
    {
        Gate::authorize('create', $page);
        // dd($request);
        $request->validate([
            'route'=>'required|max:255',
            'title' => 'required',
            'text'=>'required',
        ]);

        $pages = $page->create([
            'user_id'=>auth()->user()->id,
            'route'=>$request->route,
            'title' => $request->title,
            'data'=>$request->text
        ]);

        if($pages)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'صفحه!',
                    'text'=> 'با موفقیت ایجاد شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'صفحه!',
                    'text'=> 'ایجاد نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Page $page,User $user,Company $company,Route $route)
    {
        Gate::authorize('create', $page);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $pages = $page;
        $companies = $company->with('image')->first();
        $descriptions = $route->where('name','route(page.show)')->first() && $route->where('name','route(page.show)')->first()->descriptions?
            $route->where('name','route(page.show)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/Page-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'pages'=>$pages,'users'=>$users,'alert' => $alert,
        'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        // Gate::authorize('viewAny', $page);

        $request->validate([
            'route' => 'required',
            'title'=> 'required',
            'text'=> 'required',
            'id'=> 'required',
        ]);
        $page->find($page->id)->update([
            'route'=>$request->route,
            'title' => $request->title,
            'data'=>$request->text,
        ]);
        $request->session()->flash(
            'alert' ,[
                'title'=>'صفحه!',
                'text'=> 'با موفقیت بروز رسانی شد.',
                'icon'=> 'success',
                'button' => 'ok']
        );

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page,Request $request)
    {
        Gate::authorize('create', $page);

        $page->delete();
        $request->session()->flash(
            'alert' ,[
                'title'=>'صفحه!',
                'text'=> '.با موفقیت حذف شد',
                'icon'=> 'success',
                'button' => 'ok']
        );

        return redirect()->back();
    }
}
