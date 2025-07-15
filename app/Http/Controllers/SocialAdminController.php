<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Social;
use App\Models\Company;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\Console\Descriptor\Descriptor;

class SocialAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Social $social,Company $company,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $socials = $social->with('menu')->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()?
            $route->where('name',$request->path())->first()->menus:null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/social-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'socials'=> $socials,
            'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'menus'=>$menus,
            'path'=>$request->path(),'wallet'=>$wallet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Company $company,Route $route,Social $social)
    {
        Gate::authorize('create', $social);
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

        return Inertia::render('Users/Admin/Public/social-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
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
    public function store(Request $request,Social $social)
    {

        if($request->id)
        {
            // dd($request);
            $request->validate([
                'tag'=>'required',
                'status'=>'required'
            ]);

            $socials = $social->find($request->id)->update([
                'user_id'=> auth()->user()->id,
                'title'=>$request->title,
                'tag'=>$request->tag,
                'status'=>$request->status,
            ]);

            if($socials)
            {
                $request->session()->flash(
                    'alert',[
                        'title'=>'لینک!',
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
                        'title'=>'لینک!',
                        'text'=> 'بروزرسانی نشد، مجدد تلاش نمایید',
                        'icon'=> 'error',
                        'button' => 'ok'
                    ]);

                return redirect()->back();
            }
        }
        else if($request->social)
        {
            // dd($request->social);
            foreach ($request->social as $key => $value)
            {
                // dd($request->social);

                if($value['social'] && $value['social']['id'] !== null)
                {
                    $links = $social->find($value['social']['id'])->update([
                        'user_id' => auth()->user()->id,
                        'title'=>$value['social']['title'],
                        'tag'=>$value['social']['tag'],
                        // 'link' => $value['link'],
                        // 'linkable_type' => Social::class,
                        // 'linkable_id' => $value['id'],
                    ]);

                }
                else if($value['social']  && $value['social']['id'] == null)
                {
                    $links = $social->create([
                        'user_id' => auth()->user()->id,
                        'title'=>$value['title'],
                        'tag'=>$value['social']['tag'],
                        'status'=> 4,
                        // 'link' => $value['link'],
                        // 'linkable_type' => Social::class,
                        // 'linkable_id' => $value['id'],
                    ]);
                }

            }

            if($links)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'لینک!',
                        'text'=> 'با موفقیت ثبت شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'لینک!',
                        'text'=> 'ثبت نشد، مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
        }
        else
        {
            $request->validate([
                'title'=>'required',
                'tag'=>'required',
            ]);

            $socials = $social->create([
                'user_id'=> auth()->user()->id,
                'title'=>$request->title,
                'tag'=>$request->tag,
                'status'=>4,
            ]);

            if($socials)
            {
                $request->session()->flash(
                    'alert',[
                        'title'=>'لینک!',
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
                        'title'=>'لینک!',
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
    public function show(Request $request,User $user,Social $social,Company $company, Route $route,$socialAdmin)
    {

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $user = $user->with('file')->with('profile')->with('roles')->find($socialAdmin);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $socials = $social->with('link')->with('menu')->where('status',4)->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name','users/social')->first() && $route->where('name','users/social')?
            $route->where('name','users/social')->first()->menus:null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/social-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'socials'=> $socials,
            'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'menus'=>$menus,
            'user' => $user,'path'=>'users/social','wallet'=>$wallet]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,User $user,Company $company,Route $route,Social $social,$id)
    {
        Gate::authorize('create', $social);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $socials = $social->with('menu')->find($id);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(socialAdmin.edit)')->first() && $route->where('name','route(socialAdmin.edit)')->first()->descriptions?
            $route->where('name','route(socialAdmin.edit)')->first()->descriptions->first():null;
        $menus = $route->where('name','route(socialAdmin.edit)')->first() && $route->where('name','route(socialAdmin.edit)')->first()?
            $route->where('name','route(socialAdmin.edit)')->first()->menus:null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/social-edit',['users' => $users,'alert' => $alert,'socials'=> $socials,'menus'=>$menus,
        'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet,
        'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],]);
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
    public function destroy($id,Social $social,Request $request)
    {

        $social = $social->findOrfail($id);

        Gate::authorize('create',$social);

        // $request->validate([
        //     'id'=>'required'
        // ]);

        $socials = $social->find($id)->update([
            'user_id'=>auth()->user()->id,
            'status'=>3
        ]);

        if($socials)
        {
            $request->session()->flash(
                'alert',[
                    'title'=>'لینک!',
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
                    'title'=>'لینک!',
                    'text'=> 'بروز رسانی انجام نشد. مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok'
                ]);

            return redirect()->back();
        }
    }
}
