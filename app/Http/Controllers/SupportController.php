<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\File;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Route;
use App\Models\Tarahi;
use App\Models\Company;
use App\Models\Product;
use App\Models\section;
use App\Models\Support;
use App\Jobs\SupportJob;
use App\Models\Description;
use App\Models\Supportable;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Http\Utilities\FileCreate;
use App\Http\Utilities\SupportCreate;
use App\Notifications\SupportNotification;
use Illuminate\Support\Facades\Notification;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Support $support,Company $company,Menu $menu,Route $route,section $section)
    {
        // dd($request);
        // Gate::authorize('viewAny', $support);
        $times = Carbon::now();
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $tickets = $support->with('replies')->with('subject')->with('recepiant')->with('supportables')->where('user_id',auth()->user()->id)->where('parent_id',null)
            ->OrWhere('destination',auth()->user()->id)->where('parent_id',null)->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
         $route->where('name',$request->path())->first()->descriptions->first():null; //$description->where('route',$request->path())->first();
        $menus = $section->where('name','supports')->first() && $section->where('name','supports')->first()->menus?
         $section->where('name','supports')->first()->menus->where('parent_id','>',0) : null;
        $subject = $request->query->get('subject') == null?'All':$request->query->get('subject');
        $status = $request->query->get('status') == null? 'All' : $request->query->get('status');
        $time = $request->query->get('time') == null?'All':$request->query->get('time');
        $wallet = Wallet::all($users);

        if($time !== 'All' && $status == 'All' &&  $subject == 'All')
        {

            // dd($time,$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00'));
            $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],
                ['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();

            return Inertia::render('Users/Buyer/Support/support-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=> $tickets,
                'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,
                'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,
                'cartTotal' => $cart->total,'notifications'=>$notifications,'companies'=>$companies,
                'descriptions'=>$descriptions,'menus'=>$menus,'wallet'=>$wallet]);
        }
        elseif($status !== 'All' && $time == 'All' &&  $subject == 'All')
        {
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where('status',$status)->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();

                // dd($tickets,$subject,$status);
                // return back()->with('statuses',$statuses);
                return Inertia::render('Users/Buyer/Support/support-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=>  $tickets,
                'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,
                'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,
                'cartTotal' => $cart->total,'notifications'=>$notifications,'companies'=>$companies,
                'descriptions'=>$descriptions,'menus'=>$menus,'wallet'=>$wallet]);

        }
        elseif($subject !== 'All' && $time == 'All' && $status == 'All')
        {
            if($subject == 'All')
            {

                $subject = 'All';
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();

                return Inertia::render('Users/Buyer/Support/support-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=> $tickets,
                'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,
                'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,
                'cartTotal' => $cart->total,'notifications'=>$notifications,'companies'=>$companies,
                'descriptions'=>$descriptions,'menus'=>$menus,'wallet'=>$wallet]);
            }
            else
            {
                $subjects = $menu->find($subject);
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where('subject',$subjects->id)->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();
                // return back()->with('subject',$subject);
                // dd($subjects,$tickets,$menus);
                return Inertia::render('Users/Buyer/Support/support-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=> $tickets,
                'subjects'=> $subjects->id,'times'=> $time,'statuses'=> $status,'users'=>$users,
                'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,
                'cartTotal' => $cart->total,'notifications'=>$notifications,'companies'=>$companies,
                'descriptions'=>$descriptions,'menus'=>$menus,'wallet'=>$wallet]);

            }

        }
        else
        {
            if($subject !== 'All' && $status !== 'All' && $time !== 'All')
            {
                $tickets = $support->with('subject')->with('recepiant')->where('subject',$subject)->where('parent_id',null)->where('status',$status)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();
                return Inertia::render('Users/Buyer/Support/support-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=> $tickets,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,
                    'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,
                    'cartTotal' => $cart->total,'notifications'=>$notifications,'companies'=>$companies,
                    'descriptions'=>$descriptions,'menus'=>$menus,'wallet'=>$wallet]);
            }
            else if($subject !== 'All' && $status !== 'All')
            {
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where('subject',$subject)->where('status',$status)
                ->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();
                return Inertia::render('Users/Buyer/Support/support-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
                'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=> $tickets,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,
                    'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,
                    'cartTotal' => $cart->total,'notifications'=>$notifications,'companies'=>$companies,
                    'descriptions'=>$descriptions,'menus'=>$menus,'wallet'=>$wallet]);
            }
            else if($subject !== 'All' && $time !== 'All')
            {
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where('subject',$subject)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();
                return Inertia::render('Users/Buyer/Support/support-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=> $tickets,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,
                    'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,
                    'cartTotal' => $cart->total,'notifications'=>$notifications,'companies'=>$companies,
                    'descriptions'=>$descriptions,'menus'=>$menus,'wallet'=>$wallet]);
            }
            else if($status !== 'All' && $time !== 'All')
            {
                $tickets = $support->with('subject')->with('recepiant')->where('parent_id',null)->where('status',$status)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();
                return Inertia::render('Users/Buyer/Support/support-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=> $tickets,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,
                    'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,
                    'cartTotal' => $cart->total,'notifications'=>$notifications,'companies'=>$companies,
                    'descriptions'=>$descriptions,'menus'=>$menus,'wallet'=>$wallet]);
            }
            else
            {
                return Inertia::render('Users/Buyer/Support/support-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=> $tickets,
                    'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'users'=>$users,
                    'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,
                    'cartTotal' => $cart->total,'notifications'=>$notifications,'companies'=>$companies,
                    'descriptions'=>$descriptions,'menus'=>$menus,'wallet'=>$wallet]);
            }


        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,Menu $menu,Order $order,User $user,File $file,Support $support,
    Company $company,Description $description,Tarahi $tarahi,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->with('orders')->with('notifications')->find(auth()->user()->id);
        // $menus = $menu->where('section','supports')->where('parent_id',null)->where('status',4)->with('children')->with('descriptions')
        // ->where('status',4)->get();
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus ?
            $route->where('name',$request->path())->first()->menus:null;
        $orders = $order->with('products')->with('subOrder')->where('user_id',auth()->user()->id)->get();
        $notifications = $users->notifications;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
         $route->where('name',$request->path())->first()->descriptions->first():null;
        $tarahis = $tarahi->with('registerDesigner')->with('user')->where('status','>',3)->where('user_id',auth()->user()->id)->get();

        $wallet = Wallet::all($users);

        return Inertia::render('Users/Buyer/Support/support-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'menus' => $menus,'orders'=> $orders,
        'users'=>$users,'notifications'=>$notifications,'companies'=>$companies,'descriptions'=>$descriptions,
        'tarahis'=>$tarahis,'path'=>$request->path(),'alert'=> $alert,'wallet'=>$wallet]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $user,Supportable $supportable,Support $support,Role $role,Company $company)
    {
        // dd($request);
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
            $users = $user->findOrfail($request->destination);
            $supports = $support->findOrfail($request->parent_id);
            if($supports->status  !== 3 )
            {
                if($supports->user_id  ==  auth()->user()->id)
                {
                    $supports = SupportCreate::all($request);

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

                    }
                    else
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'!پیام',
                                'text'=> '.ارسال نشد،مشکلی پیش آمده با پشتیبانی تماس حاصل فرمایید',
                                'icon'=> 'error',
                                'button' => 'ok']
                        );

                    }
                    return redirect()->back();
                }
                else
                {
                    $supports = SupportCreate::all($request);
                    // dd($supports);
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
                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!پیام',
                        'text'=> '.بسته شده و دیگر امکان ارسال پیام در این گفت و گو وجود ندارد',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }

        }
        else
        {

            if($request->product && $request->destination == null)
            {
                $product = $request->product['category'] == null ? Tarahi::class : Product::class;
                $supports = $support->create([
                    'user_id' => auth()->user()->id,
                    'recepiant' => $request->recepiant['id'],
                    'subject' => $request->subject['id'],
                    'text' => $request->text,
                    'status' => 0,
                ]);

                if($supports)
                {
                    $supportable->create([
                        'support_id' => $supports->id,
                        'supportable_type'=> $product,
                        'supportable_id' => $request->product['id'],
                    ]);

                    if($file)
                    {
                        File::create([
                            'url'=>$file,
                            'fileable_type'=>Support::class,
                            'fileable_id'=>$supports->id
                        ]);
                    }

                    SupportJob::dispatch($supports)->delay(now()->addMinute(5));
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
            else if($request->product && $request->destination)
            {
                $users = $request->product['category'] == null? $request->product['register_designer']['user_id']:$request->product['user_id'];
                $supports = $support->create([
                    'user_id' => auth()->user()->id,
                    // 'parent_id' => 0,
                    'destination'=> $users,
                    // 'menu' => $request->menu,
                    'recepiant' => $request->recepiant['id'],
                    'subject' => $request->subject['id'],
                    'text' => $request->text,
                    'status' => 0,
                ]);

                if($supports)
                {

                    SupportJob::dispatch($supports)->delay(now()->addMinute(5));
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
            else
            {

              $supports = $support->create([
                    'user_id' => auth()->user()->id,
                    'recepiant' => $request->recepiant['id'],
                    'subject' => $request->subject['id'],
                    'text' => $request->text,
                    'status' => 0,
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

                    SupportJob::dispatch($supports)->delay(now()->addMinute(5));

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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function show(User $user,Request $request,Product $product,File $file,Support $support
    ,Company $company,Route $route)
    {
        // Gate::authorize('viewAny', $support);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $time = new Carbon();
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->notifications;
        $tickets = Support::with('user')->with('file')->find($support->id);
        $replies = Support::with('user')->with('file')->where('parent_id',$support->id)->where('status',4)->get();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(support.show)')->first() && $route->where('name','route(support.show)')->first()->descriptions?
            $route->where('name','route(support.show)')->first()->descriptions->first():null;
        // dd($tickets->status == 1 && $tickets->user_id !== $users->id);
        if($tickets->status == 1 && $tickets->user_id !== $users->id)
        {
            $tickets->update([
                'status' => 2
            ]);
        }

        $wallet = Wallet::all($users);

        return $support->user_id == auth()->user()->id ||
        $support->destination == auth()->user()->id ?
         Inertia::render('Users/Buyer/Support/support-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tickets'=> $tickets,'product'=>$product,'cartCount'=> $cart->count ,'time'=> $time,'replies'=>$replies,
        'alert'=>$alert,'flash'=>$flash,'users'=>$users,'notifications'=>$notifications,'companies'=>$companies,'descriptions'=>$descriptions,'wallet'=>$wallet]): abort(401);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function edit(Support $support)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user ,Support $support,$id)
    {
        // Gate::authorize('update',$support);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $supports = $support->with('user')->with('userimage')->find($id);

        if($supports->status == 0)
        {
            $support = $supports->update([
                'status' => 1,
            ]);

            if($support)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!پیام',
                        'text'=> '.باموفقیت تایید شد',
                        'icon'=> 'success',
                        'button' => 'ok']
                    );
                return redirect()->route('dashboard.index');
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!پیام',
                        'text'=> '.تایید نشد،مشکلی پیش آمده',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );
                return redirect()->route('dashboard.index');
            }

        }
        else if($supports->status == 4 && $supports->parent_id > 0)
        {

            $support = $support->find($supports->parent_id);

            $support->update([
                'status' => 1,
            ]);

            $supports->update([
                'status' => 1,
            ]);

            $message = 'یک پیام ارسال شده است.';
            $route = 'support.index';
            $user = $user->find($supports->destination);
            Notification::send($user , new SupportNotification($supports,$message,$route,$user));

            $request->session()->flash(
                'alert' ,[
                    'title'=>'!پیام',
                    'text'=> '.باموفقیت تایید شد',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->route('dashboard.index');
        }
        else if($supports->status == 4)
        {

            $supports->update([
                'status' => 0,
            ]);


            $message = 'یک پیام ارسال شده است.';
            $route = 'support.index';
            $user = $user->find($supports->destination);
            Notification::send($user , new SupportNotification($supports,$message,$route,$user));

            $request->session()->flash(
                'alert' ,[
                    'title'=>'!پیام',
                    'text'=> '.باموفقیت تایید شد',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->route('dashboard.index');
        }
        else
        {

            $request->session()->flash(
                'alert' ,[
                    'title'=>'!پیام',
                    'text'=> '.قبلا تایید شده است',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function destroy(Support $support)
    {
        return abort(404);
    }

}
