<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Image;
use App\Models\Route;
use App\Models\Company;
use App\Models\Profile;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;
use App\Notifications\ProfileNotification;
use Illuminate\Support\Facades\Notification;

class ProfileAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Role $role,Company $company,Route $route,
    Profile $profile)
    {
        Gate::authorize('create', $user);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $user = $user->with('profile')->with('session')->orderBy('created_at','desc')->paginate(9);
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $user_names = $request->session()->has('user_names') ? $request->session()->get('user_names'):null;
        $companies = User::with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Profile/Profile-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,
        'user' => $user,'ids'=> $ids,'statuses'=> $statuses,'user_names'=>$user_names,'companies'=>$companies,'descriptions'=>$descriptions,
        'wallet'=>$wallet]);
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
    public function store(Request $request,User $user,Profile $profile,Image $image)
    {
        Gate::authorize('create', $user);
        $request->validate([
            'status'=> 'required',
        ]);
        // dd($request);
        if($request->image)
        {

            $profiles = $user->find($request->id)->profile;
            if($profiles)
            {

                $profile->find($profiles->id)->update([
                    'status' => $request->status,
                ]);
                $users= $user->find($request->id);
                $images = $image->find($users->image->id)->update([
                    'status' => $request->status
                ]);

                $massage = 'عکس پروفایل شما تایید شد.';
                $route = 'profile.index';
                $user = $user->find($request->id);
                Notification::send($user , new ProfileNotification($profiles,$massage,$route,$user));

                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پروفایل!',
                        'text'=> 'باموفقیت تایید شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                    );
                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پروفایل!',
                        'text'=> 'تایید نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );
                return redirect()->back();
            }
        }
        else
        {
            $profiles = $user->find($request->id)->profile;
            if($profiles)
            {

                $profile->find($profiles->id)->update([
                    'status' => $request->status,
                    'biography' => $request->biography,
                ]);

                $user->find($request->id)->update([
                    'name'	=> $request->name,
                    'name_show' => $request->name_show,
                    'lasst_name' => $request->lasst_name,
                    'national_code' => $request->national_code,
<<<<<<< HEAD
=======
                    'email' => $request->email,
                    'tel' => $request->tel
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
                ]);

                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پروفایل!',
                        'text'=> 'باموفقیت تایید شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                    );
                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پروفایل!',
                        'text'=> 'تایید نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );
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
    public function show(Request $request,User $user,Role $role,Company $company,Route $route,$id)
    {
        Gate::authorize('create', $user);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $user = $user->with('image')->with('profile')->with('roles')->find($id);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $rol = $role->where('id','>',-1)->get();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(profileAdmin.show)')->first() && $route->where('name','route(profileAdmin.show)')->first()->descriptions?
            $route->where('name','route(profileAdmin.show)')->first()->descriptions->first():null;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Profile/Profile-show',[
            'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
            'roles'=>$rol,'alert'=>$alert,'user' => $user,'users' => $users,
            'flash'=>$flash,'notifications'=>$notifications,'companies'=>$companies,
            'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,User $user,Role $role,Company $company,Route $route,$id)
    {
        Gate::authorize('create', $user);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $user = $user->with('image')->with('profile')->with('roles')->find($id);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $rol = $role->where('id','>',-1)->get();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(profileAdmin.edit)')->first() && $route->where('name','route(profileAdmin.edit)')->first()->descriptions?
            $route->where('name','route(profileAdmin.edit)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Profile/Profile-edit',[
            'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
            'roles'=>$rol,'alert'=>$alert,'user' => $user,'users' => $users,
            'flash'=>$flash,'notifications'=>$notifications,'companies'=>$companies,
            'descriptions'=>$descriptions,'wallet'=>$wallet]);
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
