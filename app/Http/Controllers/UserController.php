<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\File;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Comment;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Role $role)
    {
        return abort(404);
        // Gate::authorize('create', $user);
        // $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        // $cart = new Cart($oldCart);
        // $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        // $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        // $notifications = $users->unreadnotifications;
        // $rol = $users->roles->max('id');
        // $roles = $user->with('roles')->where('id','<',$rol)->paginate(9);
        // $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        // $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        // $user_names = $request->session()->has('user_names') ? $request->session()->get('user_names'):null;

        // return Inertia::render('Users/Admin/User/user-index',['cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,
        // 'cartCoupon' => $cart->coupon,'notifications'=>$notifications,'cartTotal' => $cart->total,'alert'=>$alert,'users' => $users,
        // 'user' => $roles,'ids'=> $ids,'statuses'=> $statuses,'user_names'=>$user_names]);
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
    public function edit(Request $request,User $user,Role $role)
    {
        return abort(404);
        // Gate::authorize('create', $user);
        // $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        // $cart = new Cart($oldCart);
        // $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        // $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        // $user = $user->with('file')->with('profile')->with('roles')->find($user->id);
        // $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        // $notifications = $users->unreadnotifications;
        // $rol = $role->where('id','>',-1)->get();

        // return Inertia::render('Users/Admin/User/user-edit',['cartCount'=> $cart->count,
        //     'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,'roles'=>$rol,
        //     'cartTotal' => $cart->total,'alert'=>$alert,'user' => $user,'users' => $users,
        //     'flash'=>$flash,'notifications'=>$notifications]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user,Role $role)
    {
        return abort(404);
        // Gate::authorize('create', $user);
        // $users = $user->with('file')->with('profile')->with('roles')->find($request->user);
        // $roles = $users->roles->where('id',$request->role)->first();

        // if($request->role_id)
        // {
        //     $roles = $users->roles->where('id',$request->role_id)->first();
        //     $request->validate([
        //         'user'=> 'required',
        //         'role_id'=>'required',
        //     ]);


        //     if($roles !== null)
        //     {

        //         $users->roles()->detach($role->find($request->role_id));

        //         $request->session()->flash(
        //             'alert' ,[
        //                 'title'=>'درخواست!',
        //                 'text'=> 'باموفقیت انجام شد',
        //                 'icon'=> 'success',
        //                 'button' => 'ok']
        //             );
        //         return redirect()->route('dashboard.index');
        //     }
        //     $flash = $request->session()->flash(
        //         'message' , 'این نقش به این کاربر اختصاص داده نشده است.'
        //     );

        //     return redirect()->back();
        // }
        // else
        // {
        //     $request->validate([
        //         'user'=> 'required',
        //         'status'=> 'required',
        //         'role'=>'required',
        //     ]);

        //     $user->find($request->user)->update([
        //         'status'=> $request->status
        //     ]);

        //     if($roles == null)
        //     {
        //         $users->roles()->save($role->find($request->role));

        //         $request->session()->flash(
        //             'alert' ,[
        //                 'title'=>'درخواست!',
        //                 'text'=> 'باموفقیت انجام شد',
        //                 'icon'=> 'success',
        //                 'button' => 'ok']
        //             );
        //         return redirect()->route('dashboard.index');
        //     }
        //     $flash = $request->session()->flash(
        //         'message' , 'این نقش قبلا به این کاربر اختصاص داده شده است.'
        //     );

        //     return redirect()->back();
        // }

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

    public function search(Request $request,User $user)
    {
        return abort(404);
        // Gate::authorize('create', $user);
        // $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        // $rol = $users->roles->max('id')-1;
        // $roles = $user->with('roles')->where('id','<',$rol)->paginate(9);

        // $statu = $request->query->get('status');
        // $id = $request->query->get('id');
        // $user_name = $request->query->get('user_name');

        // if( $statu == null && $id == null && $user_name == null)
        // {
        //     $request->validate([
        //         'id'=> 'required',
        //         'status' => 'required',
        //         'user_name'=> 'required'
        //     ]);
        // }
        // elseif($id)
        // {

        //     $ids = $user->with('roles')->where('id',$id)->paginate(9);

        //     if($ids[0] && $ids[0]->roles->max('id')<$rol)
        //     {
        //         return back()->with('ids',$ids);
        //     }
        //     else
        //     {
        //         return back()->with('ids','');
        //     }


        // }
        // elseif($statu > -1)
        // {

        //     $statuses = $user->with('roles')->where('status',$statu)->paginate(9);
        //     foreach ($statuses as $key => $value)
        //     {
        //         if($value->roles->max('id')<$rol)
        //         {
        //             return back()->with('statuses',$statuses);
        //         }
        //         else
        //         {
        //             return back()->with('statuses','');
        //         }

        //     }


        // }
        // else
        // {

        //     $user_names = $user->with('roles')->where('user_name',$user_name)->paginate(9);

        //     if($user_names[0] && $user_names[0]->roles->max('id')<$rol)
        //     {
        //         return back()->with('user_names',$user_names);
        //     }
        //     else
        //     {
        //         return back()->with('user_names','');
        //     }

        // }

    }

}
