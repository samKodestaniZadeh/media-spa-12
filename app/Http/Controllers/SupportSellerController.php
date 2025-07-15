<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\File;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Product;
use App\Models\Support;
use App\Models\Description;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Notifications\SupportNotification;
use Illuminate\Support\Facades\Notification;

class SupportSellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Support $support,Company $company,Description $description)
    {
        return abort(404);
        // Gate::authorize('view', $support);
        // $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        // $cart = new Cart($oldCart);
        // $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        // $notifications = $users->unreadnotifications;
        // $subjects = $request->session()->has('subject') ? $request->session()->get('subject'):null;
        // $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        // $ids = $request->session()->has('id') ? $request->session()->get('id'):null;
        // $id=$request->query();
        // $users->notifications->find($id)->MarkAsRead();
        // $tickets = $support->with('replies')->where('destination',auth()->user()->id)->where('parent_id',0)
        // ->orderBy('created_at','desc')->paginate(9);
        // $companies = $company->with('image')->first();
        // $descriptions = $description->where('route','support')->first();

        // return Inertia::render('Users/Seller/Support/support-index',['tickets'=> $tickets,
        // 'subjects'=> $subjects,'ids'=> $ids,'statuses'=> $statuses,'users'=>$users,
        // 'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,
        // 'cartTotal' => $cart->total,'notifications'=>$notifications,'companies'=>$companies,
        // 'descriptions'=>$descriptions]);
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
    public function store(Request $request,User $user,Support $support,Role $role)
    {
        return abort(404);
        // Gate::authorize('viewAny', $support);
        // $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);


        //     $file = $request->file('file')?$request->file('file')->store('files'):null;
        //     if($file)
        //     {
        //         $request->validate([
        //             'menu' => 'required',
        //             'recepiant'=> 'required',
        //             'subject'=>'required',
        //             'text'=>'required',
        //             'file'=> 'mimes:zip,rar',

        //         ]);
        //     }
        //     else
        //     {
        //         $request->validate([
        //             'menu' => 'required',
        //             'recepiant'=> 'required',
        //             'subject'=>'required',
        //             'text'=>'required',
        //         ]);
        //     }

        //     if($request->parent_id)
        //     {

        //         $users = $user->findOrfail($request->destination);
        //         $supports = $support->findOrfail($request->parent_id);

        //         if($supports->user_id  ==  auth()->user()->id)
        //         {
        //             $support->find($request->parent_id)->update([
        //                 'status'=>0,
        //                 'updated_at'=> time(),
        //             ]);

        //             $supports = $support->create([
        //                 'user_id' => auth()->user()->id,
        //                 'parent_id' => $request->parent_id,
        //                 'destination'=> 0 ,
        //                 'menu' => $request->menu,
        //                 'recepiant' => $request->recepiant,
        //                 'subject' => $request->subject,
        //                 'text' => $request->text,
        //                 'status' => 4,
        //             ]);

        //             if($file)
        //             {
        //                 File::create([
        //                     'url'=>$file,
        //                     'fileable_type'=>Support::class,
        //                     'fileable_id'=>$supports->id
        //                 ]);
        //             }

        //             $users = $user->findOrfail($request->destination);

        //             if($users->roles->find(3))
        //             {
        //                 $massage = 'یک تیکت جدید برای شما ارسال شده است.';
        //                 $route = 'supportAdmin.index';
        //                 $users = $user->find($request->destination);
        //                 Notification::send($users , new SupportNotification($supports,$massage,$route,$users));
        //             }
        //             else if($users->roles->find(1))
        //             {

        //             }
        //             else
        //             {
        //                 $massage = 'یک تیکت جدید برای شما ارسال شده است.';
        //                 $route = 'support.index';
        //                 $users = $user->findOrfail($request->destination);
        //                 Notification::send($users , new SupportNotification($supports,$massage,$route,$users));
        //             }


        //             $request->session()->flash(
        //                 'alert' ,[
        //                     'title'=>'!تیکت',
        //                     'text'=> '.باموفقیت ارسال شد',
        //                     'icon'=> 'success',
        //                     'button' => 'ok']
        //             );

        //             return redirect()->route('dashboard.index');

        //         }
        //         else
        //         {
        //             $supports->update([
        //                 'status'=>4,
        //                 'updated_at'=> time(),
        //             ]);

        //             $supports = $support->create([
        //                 'user_id' => auth()->user()->id,
        //                 'parent_id' => $request->parent_id,
        //                 'destination'=> $request->destination ,
        //                 'menu' => $request->menu,
        //                 'recepiant' => $request->recepiant,
        //                 'subject' => $request->subject,
        //                 'text' => $request->text,
        //                 'status' => 4,
        //             ]);

        //             if($file)
        //             {
        //                 File::create([
        //                     'url'=>$file,
        //                     'fileable_type'=>Support::class,
        //                     'fileable_id'=>$supports->id
        //                 ]);
        //             }

        //             $users = $user->findOrfail($request->destination);

        //             if($users->roles->find(3))
        //             {
        //                 $massage = 'یک تیکت جدید برای شما ارسال شده است.';
        //                 $route = 'supportAdmin.index';
        //                 $users = $user->find($request->destination);
        //                 Notification::send($users , new SupportNotification($supports,$massage,$route,$users));
        //             }
        //             else if($users->roles->find(1))
        //             {

        //             }
        //             else
        //             {
        //                 $massage = 'یک تیکت جدید برای شما ارسال شده است.';
        //                 $route = 'support.index';
        //                 $users = $user->findOrfail($request->destination);
        //                 Notification::send($users , new SupportNotification($supports,$massage,$route,$users));
        //             }

        //             $request->session()->flash(
        //                 'alert' ,[
        //                     'title'=>'!تیکت',
        //                     'text'=> '.باموفقیت ارسال شد',
        //                     'icon'=> 'success',
        //                     'button' => 'ok']
        //             );

        //             return redirect()->route('dashboard.index');

        //         }

        //     }
        //     else
        //     {

        //         if($request->product)
        //         {
        //             $products = $product->find($request->product);
        //             $seller = $user->find($products->user_id);

        //             $supports = $support->create([
        //                 'user_id' => auth()->user()->id,
        //                 'parent_id' => 0,
        //                 'destination'=> $seller->id,
        //                 'menu' => $request->menu,
        //                 'recepiant' => $request->recepiant,
        //                 'subject' => $request->subject,
        //                 'text' => $request->text,
        //                 'status' => 0,
        //             ]);

        //             if($file)
        //             {
        //                 File::create([
        //                     'url'=>$file,
        //                     'fileable_type'=>Support::class,
        //                     'fileable_id'=>$supports->id
        //                 ]);
        //             }

        //             $massage = 'یک تیکت جدید برای شما ارسال شده است.';
        //             $route = 'supportSeller.index';
        //             $users = $user->find($seller->id);
        //             Notification::send($users , new SupportNotification($supports,$massage,$route,$users));

        //             $request->session()->flash(
        //                 'alert' ,[
        //                     'title'=>'!تیکت',
        //                     'text'=> '.باموفقیت ارسال شد',
        //                     'icon'=> 'success',
        //                     'button' => 'ok']
        //                 );
        //             return redirect()->route('dashboard.index');
        //         }
        //         else if($request->destination)
        //         {
        //             $supports = $support->create([
        //                 'user_id' => auth()->user()->id,
        //                 'parent_id' => 0,
        //                 'destination'=> $request->destination,
        //                 'menu' => $request->menu,
        //                 'recepiant' => $request->recepiant,
        //                 'subject' => $request->subject,
        //                 'text' => $request->text,
        //                 'file' => $file,
        //                 'status' => 0,
        //             ]);

        //             $massage = 'یک تیکت جدید برای شما ارسال شده است.';
        //             $route = 'support.index';
        //             $users = $user->find($request->destination);
        //             Notification::send($users , new SupportNotification($supports,$massage,$route,$users));

        //             $request->session()->flash(
        //                 'alert' ,[
        //                     'title'=>'!تیکت',
        //                     'text'=> '.باموفقیت ارسال شد',
        //                     'icon'=> 'success',
        //                     'button' => 'ok']
        //                 );
        //             return redirect()->route('dashboard.index');
        //         }
        //         else
        //         {

        //         $supports = $support->create([
        //                 'user_id' => auth()->user()->id,
        //                 'parent_id' => 0,
        //                 'destination'=> 0,
        //                 'menu' => $request->menu,
        //                 'recepiant' => $request->recepiant,
        //                 'subject' => $request->subject,
        //                 'text' => $request->text,
        //                 'status' => 0,
        //             ]);

        //             if($file)
        //             {
        //                 File::create([
        //                     'url'=>$file,
        //                     'fileable_type'=>Support::class,
        //                     'fileable_id'=>$supports->id
        //                 ]);
        //             }


        //             $massage = 'یک تیکت جدید ارسال شده است.';
        //             $route = 'supportAdmin.index';
        //             $user = $role->find(3);
        //             foreach ($user->users as $key => $users)
        //             {
        //                 Notification::send($users , new SupportNotification($supports,$massage,$route,$users));
        //             }

        //             $request->session()->flash(
        //                 'alert' ,[
        //                     'title'=>'!تیکت',
        //                     'text'=> '.باموفقیت ارسال شد',
        //                     'icon'=> 'success',
        //                     'button' => 'ok']
        //                 );
        //             return redirect()->route('dashboard.index');
        //         }

        //     }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Support $support,User $user,Request $request,Product $product,File $file
    ,Company $company,$id)
    {
        return abort(404);
        // Gate::authorize('view', $support);
        // $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        // $cart = new Cart($oldCart);
        // $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        // $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        // $time = time();
        // $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        // $notifications = $users->notifications;
        // $tickets = Support::with('user')->with('userimage')->with('file')->find($id);
        // $replies = Support::with('userimage')->with('user')->with('file')->where('parent_id',$id)
        // ->where('status',4)->get();
        // $companies = $company->with('image')->first();

        // if($users->roles->find(1))
        // {
        //     if($tickets->status == 0)
        //     {
        //         $tickets->update([
        //             'status' => 2
        //         ]);
        //     }

        //     return Inertia::render('Users/Seller/Support/support-show',['users' => $users,'tickets'=> $tickets,
        //     'product'=>$product,'cartCount'=> $cart->count ,'time'=> $time,'replies'=>$replies,
        //     'alert'=>$alert,'flash'=>$flash,'notifications'=>$notifications,'companies'=>$companies]);
        // }
        // else
        // {
        //     abort(401);
        // }

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
