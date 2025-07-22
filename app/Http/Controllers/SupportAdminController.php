<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\File;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Product;
use App\Models\section;
use App\Models\Support;
use App\Jobs\SupportJob;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Http\Utilities\FileCreate;
use Illuminate\Support\Facades\Gate;
use App\Http\Utilities\SupportCreate;
use App\Notifications\SupportNotification;
use Illuminate\Support\Facades\Notification;

class SupportAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Support $support,Company $company,Route $route,section $section,Menu $menu)
    {
        Gate::authorize('create', $support);
        $times = Carbon::now();
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $subject = $request->query->get('subject') == null?'All':$request->query->get('subject');
        $status = $request->query->get('status') == null? 'All' : $request->query->get('status');
        $time = $request->query->get('time') == null?'All':$request->query->get('time');
        $menus = $section->where('name','supports')->first() && $section->where('name','supports')->first()->menus?
         $section->where('name','supports')->first()->menus->where('parent_id','>',0) : null;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $tickets = $support->with('replies')->with('subject')->where('parent_id',null)->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name', $request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        // dd($tickets);
        if($time !== 'All' && $status == 'All' &&  $subject == 'All')
        {

            // dd($time,$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00'));
            $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],
                ['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->paginate(9)->withQueryString();

            return Inertia::render('Users/Admin/Support/support-index',['tickets'=> $tickets,'wallet'=>$wallet,
                'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
                'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=>$menus]);
        }
        elseif($status !== 'All' && $time == 'All' &&  $subject == 'All')
        {
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where('status',$status)->paginate(9)->withQueryString();

                // dd($tickets,$subject,$status);
                // return back()->with('statuses',$statuses);
                return Inertia::render('Users/Admin/Support/support-index',['tickets'=> $tickets,'wallet'=>$wallet,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
                    'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=>$menus]);

        }
        elseif($subject !== 'All' && $time == 'All' && $status == 'All')
        {
            if($subject == 'All')
            {

                $subject = 'All';
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Support/support-index',['tickets'=> $tickets,'wallet'=>$wallet,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
                    'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=>$menus]);
            }
            else
            {
                $subjects = $menu->find($subject);
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where('subject',$subjects->id)->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Support/support-index',['tickets'=> $tickets,'wallet'=>$wallet,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
                    'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=>$menus]);

            }

        }
        else
        {
            if($subject !== 'All' && $status !== 'All' && $time !== 'All')
            {
                $tickets = $support->with('subject')->with('recepiant')->where('subject',$subject)->where('parent_id',null)->where('status',$status)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Support/support-index',['tickets'=> $tickets,'wallet'=>$wallet,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
                    'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=>$menus]);
            }
            else if($subject !== 'All' && $status !== 'All')
            {
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where('subject',$subject)->where('status',$status)
                ->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Support/support-index',['tickets'=> $tickets,'wallet'=>$wallet,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
                    'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=>$menus]);
            }
            else if($subject !== 'All' && $time !== 'All')
            {
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where('subject',$subject)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Support/support-index',['tickets'=> $tickets,'wallet'=>$wallet,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
                    'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=>$menus]);
            }
            else if($status !== 'All' && $time !== 'All')
            {
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where('status',$status)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Support/support-index',['tickets'=> $tickets,'wallet'=>$wallet,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
                    'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=>$menus]);
            }
            else
            {

                return Inertia::render('Users/Admin/Support/support-index',['tickets'=> $tickets,'wallet'=>$wallet,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
                    'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'menus'=>$menus]);
            }

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,Menu $menu,User $user,Support $support,Company $company,Route $route)
    {
        Gate::authorize('create', $support);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $user->notifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $menus =$route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()?
            $route->where('name',$request->path())->first()->menus:null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name', $request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);
        $user = $user->all();

        return Inertia::render('Users/Admin/Support/support-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'menus' => $menus,'user'=>$user,'alert'=>$alert,
        'users'=>$users,'notifications'=>$notifications,'companies'=>$companies,'descriptions'=>$descriptions,
        'path'=>$request->path(),'wallet'=>$wallet]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $user,Product $product,Support $support,Role $role,Company $company)
    {
        Gate::authorize('create', $support);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        // dd($request->destination);
        $file = $request->file('file')?$request->file('file')->store('files'):null;
        if($file)
        {
            $request->validate([
                'recepiant'=> 'required',
                'subject'=>'required',
                'text'=>'required',
                'file'=> 'mimes:zip,rar',

            ]);
        }
        else
        {
            $request->validate([
                'recepiant'=> 'required',
                'subject'=>'required',
                'text'=>'required',
            ]);
        }

        if($request->parent_id)
        {

            $users = $user->find($request->destination);
            $supports = $support->find($request->parent_id);
            if($supports->status  !== 3 )
            {
                if($supports->user_id  ==  auth()->user()->id)
                {
                    $support->find($request->parent_id)->update([
                        'status'=>4,
                        'updated_at'=> time(),
                    ]);

                    $supports = $support->create([
                        'user_id' => auth()->user()->id,
                        'parent_id' => $request->parent_id,
                        'destination'=> $request->destination ,
                        // 'menu' => $request->menu,
                        'recepiant' => $request->recepiant,
                        'subject' => $request->subject,
                        'text' => $request->text,
                        'status' => 4,
                    ]);

                    if($supports)
                    {
                        if($file)
                        {
                            File::create([
                                'url'=>$file,
                                'fileable_type'=>Support::class,
                                'fileable_id'=>$supports->id
                            ]);
                        }
                        $users = $user->findOrfail($request->destination);

                        if($users->roles->find(3))
                        {
                            $message = 'یک پیام جدید برای شما ارسال شده است.';
                            $route = 'supportAdmin.index';
                            $user = $user->find($request->destination);
                            Notification::send($user , new SupportNotification($supports,$message,$route,$user));
                        }
                        else if($users->roles->find(1))
                        {

                            $message = 'یک پیام جدید برای شما ارسال شده است.';
                            $route = 'support.index';
                            $user = $user->find($request->destination);
                            Notification::send($user , new SupportNotification($supports,$message,$route,$user));
                        }
                        else
                        {
                            $message = 'یک پیام جدید برای شما ارسال شده است.';
                            $route = 'support.index';
                            $user = $user->find($request->destination);
                            Notification::send($user , new SupportNotification($supports,$message,$route,$user));
                        }

                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'!پیام',
                                'text'=> '.باموفقیت ارسال شد',
                                'icon'=> 'success',
                                'button' => 'ok']
                        );

                        return redirect()->back();
                    }
                    else
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'!پیام',
                                'text'=> '.ارسال نشد،مشکلی پیش آمده با پشتیبانی تماس حاصل فرمایید',
                                'icon'=> 'success',
                                'button' => 'ok']
                        );

                        return redirect()->back();
                    }

                }
                else
                {

                    $supports->update([
                        'status'=>4,
                        'updated_at'=> time(),
                    ]);

                    $supports = $support->create([
                        'user_id' => auth()->user()->id,
                        'parent_id' => $request->parent_id,
                        'destination'=> $request->destination ,
                        // 'menu' => $request->menu,
                        'recepiant' => $request->recepiant,
                        'subject' => $request->subject,
                        'text' => $request->text,
                        'status' => 4,
                        // 'supportable_id'=>0,
                        // 'supportable_type'=>0,
                    ]);

                    if($supports)
                    {
                        if($file)
                        {
                            File::create([
                                'url'=>$file,
                                'fileable_type'=>Support::class,
                                'fileable_id'=>$supports->id
                            ]);
                        }

                        $users = $user->findOrfail($request->destination);

                        if($users->roles->find(3))
                        {
                            $message = 'یک پیام جدید برای شما ارسال شده است.';
                            $route = 'supportAdmin.index';
                            $user = $user->find($request->destination);
                            Notification::send($user , new SupportNotification($supports,$message,$route,$user));
                        }
                        else if($users->roles->find(1))
                        {
                            $message = 'یک پیام جدید برای شما ارسال شده است.';
                            $route = 'support.index';
                            $user = $user->find($request->destination);
                            Notification::send($user , new SupportNotification($supports,$message,$route,$user));
                        }
                        else
                        {
                            $message = 'یک پیام جدید برای شما ارسال شده است.';
                            $route = 'support.index';
                            $user = $user->find($request->destination);
                            Notification::send($user , new SupportNotification($supports,$message,$route,$user));
                        }

                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'پیام!',
                                'text'=> 'باموفقیت ارسال شد.',
                                'icon'=> 'success',
                                'button' => 'ok']
                            );
                        return redirect()->back();
                    }
                    else
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'پیام!',
                                'text'=> 'ارسال نشد،مشکلی پیش آمده با پشتیبانی تماس حاصل فرمایید.',
                                'icon'=> 'error',
                                'button' => 'ok']
                        );

                        return redirect()->back();
                    }
                }
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پیام!',
                        'text'=> 'بسته شده و دیگر امکان ارسال پیام در این گفت و گو وجود ندارد.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }

        }
        else
        {

            $supports = SupportCreate::Admin($request);
            if($supports)
            {
                if($file)
                {
                    $files = FileCreate::all($request,$supports,Support::class);
                }

                $companies = $company->first();

                SupportJob::dispatch($supports)->delay(now()->addMinute($companies->job));
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پیام!',
                        'text'=> 'باموفقیت ارسال شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پیام!',
                        'text'=> 'ارسال نشد،مشکلی پیش آمده با پشتیبانی تماس حاصل فرمایید.',
                        'icon'=> 'success',
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
    public function show(Support $support,User $user,Request $request,Company $company,Route $route,$id)
    {
        Gate::authorize('create', $support);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $time = new Carbon;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->notifications;
        $tickets = Support::with('user')->with('file')->find($id);
        $replies = Support::with('user')->with('file')->where('parent_id',$id)->where('status',4)->get();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(supportAdmin.show)')->first() && $route->where('name','route(supportAdmin.show)')->first()->descriptions?
            $route->where('name','route(supportAdmin.show)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);
        // dd($tickets->status == 0 && $tickets->user_id !== $users->id);
        if ($tickets->status == 0 && $tickets->user_id !== $users->id)
        {
            if($tickets->status == 0)
            {
                $tickets->update([
                    'status' => 2
                ]);
            }

            return Inertia::render('Users/Admin/Support/support-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'users' => $users,'tickets'=> $tickets,
            'cartCount'=> $cart->count ,'time'=> $time,'replies'=>$replies,'alert'=>$alert,'flash'=>$flash,
            'notifications'=>$notifications,'companies'=>$companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
        }
        elseif($users->roles->find(3))
        {
            if($tickets->status == 4)
            {
                $tickets->update([
                    'status' => 2
                ]);
            }

            return Inertia::render('Users/Admin/Support/support-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'users' => $users,'tickets'=> $tickets,
            'cartCount'=> $cart->count ,'time'=> $time,'replies'=>$replies,'alert'=>$alert,'flash'=>$flash,
            'notifications'=>$notifications,'companies'=>$companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
        }
        else
        {
            abort(401);
        }

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
