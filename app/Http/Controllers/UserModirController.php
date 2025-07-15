<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;

class UserModirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Role $role,Company $company,Route $route)
    {

        Gate::authorize('update', $user);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $roles = $user->with('image')->with('roles')->where('id','>',0)->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions =  $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $subject = $request->query->get('subject') == null?'All':$request->query->get('subject');
        $status = $request->query->get('status') == null? 'All' : $request->query->get('status');
        // dd($subject,$status);
        $wallet = Wallet::all($users);
        if($status == 'All' &&  $subject == 'All')
        {

            return Inertia::render('Users/Modir/User/user-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'notifications'=>$notifications,'alert'=>$alert,'users' => $users,
            'user' => $roles,'statuses'=> $status,'subjects'=>$subject,'companies'=>$companies,'descriptions'=>$descriptions,
            'wallet'=>$wallet
            ]);
        }
        elseif($status !== 'All' &&  $subject == 'All')
        {

            $roles = $user->with('image')->with('roles')->where('status',$status)->orderBy('created_at','desc')->paginate(9);
            // dd($roles);
            return Inertia::render('Users/Modir/User/user-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'notifications'=>$notifications,'alert'=>$alert,'users' => $users,
            'user' => $roles,'statuses'=> $status,'subjects'=>$subject,'companies'=>$companies,'descriptions'=>$descriptions,
            'wallet'=>$wallet
            ]);
        }
        elseif($subject !== 'All' && $status == 'All')
        {

                $roles = $user->with('image')->with('roles')->where('user_name',$subject)->orderBy('created_at','desc')->paginate(9);
                // dd($roles);
                return Inertia::render('Users/Modir/User/user-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'notifications'=>$notifications,'alert'=>$alert,'users' => $users,
                'user' => $roles,'statuses'=> $status,'subjects'=>$subject,'companies'=>$companies,'descriptions'=>$descriptions,
                'wallet'=>$wallet
                ]);
        }
        else
        {
            if($subject !== 'All' && $status !== 'All')
            {
                // $tarahis = $tarahi->with('user')->with('type')->with('reqDesigner')->with('registerDesigner')
                // ->where('user_id',$users->id)->where([
                //     ['created_at','>=',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                //     ['created_at','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]
                //     ])->where('status',$status)->where('type',$subject)->orderBy('created_at','desc')->paginate(9);

                // return Inertia::render('Users/Buyer/Tarahi/Tarahi-index',['tarahis'=> $tarahis,'time'=>$time,
                // 'users' => $users,'names'=> $names,'menus' => $menus,'notifications'=> $notifications,
                // 'companies'=>$companies,'descriptions'=>$descriptions,'alert'=> $alert,'subjects'=> $subject,
                // 'times'=> $times,'statuses'=> $status,'path'=>$request->path()]);
            }
            else if($subject !== 'All' && $status !== 'All')
            {
                // $tarahis = $tarahi->with('user')->with('type')->with('reqDesigner')->with('registerDesigner')
                // ->where('user_id',$users->id)->where('status',$status)->where('type',$subject)->orderBy('created_at','desc')->paginate(9);

                // return Inertia::render('Users/Buyer/Tarahi/Tarahi-index',['tarahis'=> $tarahis,'time'=>$time,
                // 'users' => $users,'names'=> $names,'menus' => $menus,'notifications'=> $notifications,
                // 'companies'=>$companies,'descriptions'=>$descriptions,'alert'=> $alert,'subjects'=> $subject,
                // 'times'=> $times,'statuses'=> $status,'path'=>$request->path()]);
            }
            else
            {
                return Inertia::render('Users/Modir/User/user-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'notifications'=>$notifications,'alert'=>$alert,'users' => $users,
                    'user' => $roles,'statuses'=> $status,'subjects'=>$subject,'companies'=>$companies,'descriptions'=>$descriptions,
                    'wallet'=>$wallet
                    ]);
            }


        }


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
    public function store(Request $request,User $user,Role $role)
    {
        $users = $user->with('file')->with('profile')->with('roles')->find($request->user);
        $roles = $users->roles->where('id',$request->role)->first();

        if($request->role_id)
        {
            $roles = $users->roles->where('id',$request->role_id)->first();
            $request->validate([
                'user'=> 'required',
                'role_id'=>'required',
            ]);


            if($roles !== null)
            {

               $roles = $users->roles()->detach($role->find($request->role_id));
                if($roles)
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'درخواست!',
                            'text'=> 'باموفقیت انجام شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );
                    return redirect()->back();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'درخواست!',
                            'text'=> 'انجام نشد،مشکلی پیش آمده، مجدد تلاش نمایید.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );
                    return redirect()->back();
                }

            }
                $request->session()->flash(
                'alert' ,[
                    'title'=>'درخواست!',
                    'text'=> 'این نقش به این کاربر اختصاص داده نشده است.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
        else
        {
            $request->validate([
                'user'=> 'required',
                'status'=> 'required',
                'role'=>'required',
            ]);

            $user->find($request->user)->update([
                'status'=> $request->status
            ]);

            if($roles == null)
            {
                $roles = $users->roles()->save($role->find($request->role));

                if($roles)
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'درخواست!',
                            'text'=> 'باموفقیت انجام شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );
                    return redirect()->back();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'درخواست!',
                            'text'=> 'انجام نشد،مشکلی پیش آمده، مجدد تلاش نمایید.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );
                    return redirect()->back();
                }
            }
             $request->session()->flash(
                'alert' ,[
                    'title'=>'درخواست!',
                    'text'=> 'این نقش قبلا به این کاربر اختصاص داده شده است.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
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
        Gate::authorize('update', $user);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $user = $user->with('file')->with('profile')->with('roles')->find($id);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $rol = $role->where('id','>',-1)->get();
        $companies = $user->with('image')->first();
        $descriptions =  $route->where('name','route(userModir.show)')->first() && $route->where('name','route(userModir.show)')->first()->descriptions?
            $route->where('name','route(userModir.show)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Modir/User/user-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'roles'=>$rol,'alert'=>$alert,'user' => $user,'users' => $users,
            'flash'=>$flash,'notifications'=>$notifications,'companies'=>$companies,
            'descriptions'=>$descriptions,'wallet'=>$wallet
            ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
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
    public function update(Request $request)
    {
        // Gate::authorize('update', $user);
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
