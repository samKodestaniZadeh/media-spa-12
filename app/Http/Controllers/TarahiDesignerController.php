<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\File;
use App\Models\Menu;
use App\Models\Rate;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Route;
use App\Jobs\OrderJob;
use App\Models\Tarahi;
use App\Jobs\TarahiJob;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Support;
use App\Jobs\DepositJob;
use App\Jobs\PaymentJob;
use App\Jobs\SupportJob;
use App\Models\Financial;
use App\Models\Orderable;
use App\Jobs\OrderableJob;
use App\Jobs\OrderModirJob;
use App\Models\Description;
use App\Models\ReqDesigner;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Jobs\OrderDesignerJob;
use App\Jobs\SupportStatusJob;
use App\Http\Utilities\FileCreate;
use App\Http\Utilities\RateCreate;
use App\Http\Utilities\OrderCreate;
use Illuminate\Support\Facades\Gate;
use App\Http\Utilities\DepositCreate;
use App\Http\Utilities\PaymentCreate;
use App\Http\Utilities\OrderableCreate;
use Illuminate\Support\Facades\Storage;
use App\Notifications\OrderNotification;
use App\Notifications\TarahiNotification;
use App\Notifications\CommentNotification;
use App\Notifications\PaymentNotification;
use App\Notifications\SupportNotification;
use Illuminate\Support\Facades\Notification;

class TarahiDesignerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Tarahi $tarahi,User $user,Company $company,Route $route,Order $order,
    ReqDesigner $reqDesigner)
    {
        Gate::authorize('view',$reqDesigner);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $time = Carbon::now('+3:30');
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $names = $request->session()->has('names') ? $request->session()->get('names'):null;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $users = $user->with('image')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $reqDesigners = $reqDesigner->with('tarahiRegister')->with('file')->where('user_id',auth()->user()->id)
        ->where('status','<>',3)->orderBy('created_at','desc')->paginate(9);
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        // $menus = $menu->where('parent_id',null)->where('status',4)->with('children')
        // ->where('status',4)->get();
        $menus = Route::with('menus')->where('name',$request->path())->first()? Route::with('menus')->where('name',$request->path())->first()->menus:null;
        $orders = $order->with('products')->where('user_id',auth()->user()->id)->get();
        $token = $request->session()->token();
        $wallet = Wallet::all($users);
        // dd(new Carbon,$time);
        return Inertia::render('Users/Designer/Tarahi/Tarahi-index',['tarahis'=> $reqDesigners,
        'users' => $users,'names'=> $names,'ids'=> $ids,'statuses'=> $statuses,'menus' => $menus,
        'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,
        'orders'=> $orders,'time'=>$time,'token'=>$token,'alert'=> $alert,'wallet'=>$wallet,
        'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
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
    public function store(Request $request,Tarahi $tarahi,ReqDesigner $reqdesigner,User $user,Role $role,
    Rate $rate,Comment $comment,File $file, Support $support)
    {
        Gate::authorize('view', $reqdesigner);
        $time = Carbon::now();
        $companies = Company::with('user')->with('image')->first();
        // dd($request->rate);
        if($request->rate)
        {

            if($request->name == 'designer')
            {
                $tarahis = $tarahi->with('discount')->find($request->designer['id']);
                $reqdesigners = $reqdesigner->find($request->id['id']);
                // dd($request);
                if($request->price == $tarahis->total)
                {
                    $request->validate([
                        'name' => 'required',
                        'id' => 'required',
                        'text' => 'required',
                        'price' => 'required|numeric',
                        'rate' => 'required|numeric|between:1,7',
                    ]);

                    if($tarahis->status == 7)
                    {

                        if($request->discount && $request->discount < 11 && $request->discount > 0)
                        {
                            $discounts = $tarahis->discount()->create([
                                'user_id' => auth()->user()->id,
                                'expired' => $time->addDays(1),
                                'percent'=> $request->discount,
                                'discountable_type'=> Tarahi::class,
                                'discountable_id'=>$tarahis->id,
                            ]);
                        }
                        else if($request->discount !== null)
                        {

                            $request->session()->flash(
                                'alert',[
                                    'title'=>'تخفیف پروژه!',
                                    'text'=> 'نباید بیشتر از 11 و کمتر از 1 درصد باشد.',
                                    'icon'=> 'error',
                                    'button' => 'ok'
                                ]
                            );

                            return redirect()->back();
                        }
                        else
                        {
                            $discounts = null;
                        }


                            $tarahi = $tarahis->update([
                                'status' => 6,
                                'total' => $discounts ? $tarahis->total - ($tarahis->total * $discounts->percent / 100) : $tarahis->total
                            ]);

                            if($tarahi)
                            {

                                $rates = RateCreate::create(auth()->user()->id,$request->rate,$request->text,Tarahi::class,$tarahis->id);

                                if($rates)
                                {

                                    $comison = round($tarahis->total * $companies->comison);
                                    $tax =  $comison * $companies->tax ;


                                    $orders = OrderCreate::create($tarahis->user_id,$tarahis->price_block,1,$discounts ? $tarahis->price_block * ($discounts->percent / 100) : 0,0,
                                        $discounts ? $tarahis->price_block -($tarahis->price_block * $discounts->percent / 100) : $tarahis->total, 0,
                                         $discounts ? $tarahis->price_block -($tarahis->price_block * $discounts->percent / 100) : $tarahis->total,
                                         $discounts ? $tarahis->price_block -($tarahis->price_block * $discounts->percent / 100) : $tarahis->total,0);

                                    if($orders)
                                    {
                                        $message = 'خدمات فروش رفته است.';
                                        OrderModirJob::dispatch($orders,$message)->delay(now()->addMinute($companies->job));

                                        $orderables = OrderableCreate::create($orders->id,$reqdesigners->user_id,$tarahis->price_block,1,$discounts ? $tarahis->price_block * $discounts->percent / 100 : 0,
                                            0,$discounts ? $tarahis->price_block-($tarahis->price_block * $discounts->percent / 100)-$comison :$tarahis->total-$comison,$comison,$tax,
                                            $discounts ? $tarahis->price_block-($tarahis->price_block * $discounts->percent / 100)-$comison :$tarahis->total-$comison-$tax,Tarahi::class,$tarahis->id,);

                                        if($orderables)
                                        {

                                            $deposets = DepositCreate::create($orderables->user_id, ' واریز بابت فروش خدمات پروژه '.$tarahis->title,(($orderables->price*$orderables->count)-($orderables->discount+$orderables->coupon))
                                                ,0,0,$time,4,Tarahi::class,$tarahis->id,0,0,0,null,null);

                                            if($deposets )
                                            {

                                                $message = 'افزایش موجودی کیف پول بابت فروش خدمات.';

                                                DepositJob::dispatch($deposets,$message)->delay(now()->addMinute($companies->job));


                                            }


                                            $deposets = DepositCreate::create($orderables->user_id, '  واریز بابت عودت ضمانت خدمات پروژه '.$tarahis->title,$reqdesigners->block_price,0,0,$time,4,Tarahi::class,$tarahis->id,0,0,0,null,null);
                                            if($deposets )
                                            {
                                                $message = 'افزایش موجودی کیف پول بابت ضمانت.';

                                                DepositJob::dispatch($deposets,$message)->delay(now()->addMinute($companies->job));
                                            }


                                            $payments = PaymentCreate::create($orderables->user_id,' برداشت بابت کارمزد و مالیات فروش خدمات پروژه '.$tarahis->title,($orderables->comison+$orderables->tax),0,0,$time,4,
                                                Tarahi::class,$tarahis->id,0,0,0,null,null);


                                            if($payments)
                                            {
                                                $message = ' پروژه کاهش موجودی کیف پول بابت کارمزد و مالیات فروش خدمات.';
                                                $route = 'payment.index';
                                                $user = $user->find($orderables->user_id);

                                                OrderableJob::dispatch($orderables,$message,$route,$user)->delay(now()->addMinute($companies->job));

                                            }


                                            if($discounts)
                                            {

                                                $payments = DepositCreate::create($tarahis->user_id,' واریز بابت تخفیف خرید خدمات پروژه '.$tarahis->title,($tarahis->price_block-$tarahis->total),0,0,$time,4,
                                                    Tarahi::class,$tarahis->id,0,0,0,null,null);
                                                if($payments)
                                                {
                                                    $message = 'افزایش موجودی کیف پول بابت تخفیف خرید خدمات پروژه.';
                                                    $route = 'payment.index';
                                                    $user = $user->find($tarahis->user_id);

                                                    OrderableJob::dispatch($orderables,$message,$route,$user)->delay(now()->addMinute($companies->job));
                                                }

                                            }

                                            $request->session()->flash(
                                                'alert',[
                                                    'title'=>'رای و نظر!',
                                                    'text'=> 'شما با موفقیت ثبت شد.',
                                                    'icon'=> 'success',
                                                    'button' => 'ok'
                                                ]
                                            );

                                            return redirect()->back();

                                        }
                                        else
                                        {
                                            $request->session()->flash(
                                                'alert',[
                                                    'title'=>'مورد فروش!',
                                                    'text'=> 'شما ثبت نشد، مشکلی پیش آمده لطفا مجدد تلاش فرمایید.',
                                                    'icon'=> 'error',
                                                    'button' => 'ok'
                                                ]
                                            );

                                            return redirect()->back();
                                        }
                                    }
                                    else
                                    {
                                        $request->session()->flash(
                                            'alert',[
                                                'title'=>'فروش!',
                                                'text'=> 'شما ثبت نشد، مشکلی پیش آمده لطفا مجدد تلاش فرمایید.',
                                                'icon'=> 'error',
                                                'button' => 'ok'
                                            ]
                                        );

                                        return redirect()->back();
                                    }


                                }
                                else
                                {
                                    $request->session()->flash(
                                        'alert',[
                                            'title'=>'رای و نظر!',
                                            'text'=> 'شما ثبت نشد، مشکلی پیش آمده لطفا مجدد تلاش نمایید.',
                                            'icon'=> 'error',
                                            'button' => 'ok'
                                        ]
                                    );

                                    return redirect()->back();
                                }
                            }
                            else
                            {
                                $request->session()->flash(
                                    'alert',[
                                        'title'=>'رای و نظر!',
                                        'text'=> 'شما ثبت نشد، مشکلی پیش آمده لطفا مجدد تلاش فرمایید.',
                                        'icon'=> 'error',
                                        'button' => 'ok'
                                    ]
                                );

                                return redirect()->back();
                            }


                    }
                    else
                    {
                        $request->session()->flash(
                            'alert',[
                                'title'=>'رای و نظر!',
                                'text'=> 'در این مرحله امکان ثبت وجود ندارد.',
                                'icon'=> 'error',
                                'button' => 'ok'
                            ]
                        );

                        return redirect()->back();
                    }

                }
                else
                {
                    $request->session()->flash(
                        'alert',[
                            'title'=>'پروژه!',
                            'text'=> 'مبلغ پروژه با مبلغ ثبتی توسط شما مطابقت ندارد.چنانچه با کارفرما به اختلاف و یا مشکلی برخورد کردید،گزارش دهید.',
                            'icon'=> 'error',
                            'button' => 'ok'
                        ]
                    );

                    return redirect()->back();
                }
            }
            else
            {
                $request->session()->flash(
                    'alert',[
                        'title'=>'پروژه!',
                        'text'=> 'رای شما ثبت نشد.',
                        'icon'=> 'error',
                        'button' => 'ok'
                    ]
                );

                return redirect()->back();
            }

        }
        else if($request->tarahi && $request->id)
        {
            $request->validate([
                'id' => 'required',
                'tarahi'=> 'required',
                'file'=> 'required|mimes:zip,rar|max:500000',
            ]);

            $reqdesigners = $reqdesigner->find($request->id);
            $tarahis = $tarahi->find($request->tarahi);
            // dd($reqdesigners,$tarahis);
            if($tarahis->status == 5  && $tarahis->date > $time || $tarahis->status == 8 && $tarahis->date > $time)
            {
                $files = $request->file('file')?$request->file('file')->store('files'):null;
                // dd($tarahis->fileDesigner && $files && $files !== $tarahis->fileDesigner->url);
                if($tarahis->fileDesigner && $files && $files !== $tarahis->fileDesigner->url)
                {
                    $store = Storage::delete( $tarahis->fileDesigner->url);

                    if($store)
                    {
                        $reqdesigner = $file->find($tarahis->fileDesigner->id)->update([
                            'url'=>$files,
                            'fileable_type'=>ReqDesigner::class,
                            'fileable_id'=>$tarahis->reqdesigner_id,
                            'status'=> 4,
                        ]);

                        if($reqdesigner)
                        {
                            $tarahi = $tarahis->update([
                                'status' => 8
                            ]);

                            TarahiJob::dispatch($tarahis)->delay(now()->addMinute($companies->job));

                            $request->session()->flash(
                                'alert' ,[
                                    'title'=>'فایل!',
                                    'text'=> 'باموفقیت بروزرسانی شد.',
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
                                    'text'=> 'انجام نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                                    'icon'=> 'error',
                                    'button' => 'ok']
                                );
                            return redirect()->back();
                        }
                    }
                    else
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'درخواست!',
                                'text'=> 'انجام نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                                'icon'=> 'error',
                                'button' => 'ok']
                            );
                        return redirect()->back();
                    }

                }
                else
                {

                    $files = FileCreate::create($request,$reqdesigners,ReqDesigner::class);

                    if($files)
                    {
                        $tarahi = $tarahis->update([
                            'status' => 8
                        ]);

                        if ($tarahi)
                        {
                            $message = 'فایل پروژه شما توسط طراح بارگزاری شده است.';
                            TarahiJob::dispatch($tarahis,$message)->delay(now()->addMinute($companies->job));

                            $request->session()->flash(
                                'alert' ,[
                                    'title'=>'فایل!',
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
                                    'title'=>'درخواست!',
                                    'text'=> 'انجام نشد، مشکلی پیش آمده با پشتیبانی تماس تیکت  بزنید.',
                                    'icon'=> 'error',
                                    'button' => 'ok']
                            );
                            return redirect()->back();
                        }


                    }
                    else
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'درخواست!',
                                'text'=> 'انجام نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
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
                        'title'=>'پروژه!',
                        'text'=> 'زمان باز گذاری تمام شده یا در این مرحله امکان بارگذاری فایل وجود ندارد.',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );
                return redirect()->back();
            }
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'درخواست!',
                    'text'=> 'انجام نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
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
    public function show(Request $request,User $user,Tarahi $tarahi,Company $company,Route $route,ReqDesigner $reqDesigner,$id)
    {
        Gate::authorize('view',$reqDesigner);

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->notifications;
        $tarahis = $tarahi->with('menus')->with('file')->with('user')->with('registerDesigner')->where('slug',$id)->first();
        $reqDesigners =  $tarahis->reqDesigner->first() ? $reqDesigner->with('file')->find($tarahis->reqDesigner->first()->id) : null;
        $registerDesigners =  $tarahis->registerDesigner->first() ? $reqDesigner->with('file')->find($tarahis->registerDesigner->first()->id) : null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(tarahiDesigner.show)')->first() && $route->where('name','route(tarahiDesigner.show)')->first()->descriptions?
            $route->where('name','route(tarahiDesigner.show)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);
        // dd($tarahis);
        return Inertia::render('Users/Designer/Tarahi/Tarahi-show',['tarahis'=> $tarahis,'cartCount'=> $cart->count,
        'alert'=>$alert,'flash'=>$flash,'users'=>$users,'notifications'=>$notifications,'companies'=>$companies,
        'descriptions'=>$descriptions,'reqDesigner'=>$tarahis->reqDesigner,'registerDesigner'=>$tarahis->registerDesigner,
        'wallet'=>$wallet
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,User $user,Tarahi $tarahi,Company $company,Route $route,ReqDesigner $reqDesigner,Orderable $orderable,$id)
    {
        Gate::authorize('view',$reqDesigner);

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->notifications;

        $companies = $company->with('image')->first();
        $descriptions = $route->where('name','route(tarahiDesigner.show)')->first() && $route->where('name','route(tarahiDesigner.show)')->first()->descriptions?
            $route->where('name','route(tarahiDesigner.show)')->first()->descriptions->first():null;
        $orders = $orderable->where([['user_id',auth()->user()->id],['orderable_type','App\Models\Tarahi'],['orderable_id',$id]])
            ->with('user')->with('product')->orderBy('created_at','desc')->paginate(9)->WithQueryString();
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Designer/Tarahi/Tarahi-edit',['orders'=> $orders,'cartCount'=> $cart->count,
        'alert'=>$alert,'flash'=>$flash,'users'=>$users,'notifications'=>$notifications,'companies'=>$companies,
        'descriptions'=>$descriptions,'wallet'=>$wallet]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Tarahi $tarahi ,$id)
    {
        Gate::authorize('view', $tarahi);

        $request->validate([
            'id' => 'required',
            'status'=> 'required',
        ]);

        $tarahi->find($id)->update([
            'status'=> $request->status,
            'designer_id'=> $request->user,
            'date'=>$request->date,
            'price'=> $request->price,
            'discount'=> $request->discount,
            'comison'=> $request->price * auth()->user()->comison/100,
            'total'=> $request->total,
        ]);

        $request->session()->flash(
            'alert' ,[
                'title'=>'درخواست!',
                'text'=> 'با موفقیت انجام شد.',
                'icon'=> 'success',
                'button' => 'ok']
        );

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Tarahi $tarahi,User $user,Support $support,Role $role,ReqDesigner $reqDesigner,Company $company,$id)
    {
        Gate::authorize('view', $tarahi);
        $time = new Carbon;
        $companies = $company->first();
        if($tarahi->find($id)->registerdesigner->user_id == auth()->user()->id)
        {
            $tarahi = $tarahi->find($id);

            if($tarahi->status !== 3)
            {

                if($tarahi->reqdesigner_id)
                {
                    $reqDesigners = $tarahi->find($id)->registerdesigner;

                    if($tarahi->registerdesigner->block_price > 0 && $tarahi->registerdesigner->user_id == auth()->user()->id && $tarahi->registerdesigner->canceller_id == null)
                    {
                        $tarahis = $tarahi->update([
                            'status'=>3,
                            'canceller_id'=>auth()->user()->id,
                        ]);

                        if($tarahis)
                        {
                            $deposets = DepositCreate::create($tarahi->registerDesigner->user_id, ' واریز بابت رفع مسدودی مبلغ ضمانت پروژه '. $tarahi->title,$tarahi->registerDesigner->block_price,0,0,$time,4,Tarahi::class,$tarahi->id,0,0,0,null,null);

                            if($deposets )
                            {

                                $message = 'افزایش موجودی کیف پول بابت رفع مسدودی مبلغ ضمانت پروژه.';

                                DepositJob::dispatch($deposets,$message)->delay(now()->addMinute($companies->job));
                            }

                            $payments = PaymentCreate::create($tarahi->registerDesigner->user_id,'برداشت بابت جریمه لغو پروژه '. $tarahi->title.' توسط شما ',$tarahi->registerdesigner->block_price,0,0,$time,4,Tarahi::class,$tarahi->id,0,0,0,null,null);

                            if($payments)
                            {
                                $message = ' کاهش موجودی کیف پول بابت جریمه لغو پروژه توسط شما.';

                                PaymentJob::dispatch($payments,$message)->delay(now()->addMinute($companies->job));

                            }

                            $deposets = DepositCreate::create($tarahi->user_id, ' واریز بابت جریمه لغو پروژه '. $tarahi->title.' توسط طراح ',$tarahi->registerdesigner->block_price,0,0,$time,4,Tarahi::class,$tarahi->id,0,0,0,null,null);

                            if($deposets )
                            {

                                $message = 'افزایش موجودی کیف پول بابت جریمه لغو پروژه توسط طراح.';

                                DepositJob::dispatch($deposets,$message)->delay(now()->addMinute($companies->job));
                            }

                            $request->session()->flash(
                                'alert' ,[
                                    'title'=>'پروژه!',
                                    'text'=> 'با موفقیت لغو شد.',
                                    'icon'=> 'success',
                                    'button' => 'ok']
                            );

                            return redirect()->back();
                        }
                        else
                        {
                            $request->session()->flash(
                                'alert' ,[
                                    'title'=>'پروژه!',
                                    'text'=> 'لغو نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                                    'icon'=> 'error',
                                    'button' => 'ok']
                            );

                            return redirect()->back();
                        }

                    }
                    else
                    {
                        $tarahis = $tarahi->update([
                            'comison' => null,
                            'status'=>4
                        ]);
                        if($tarahis)
                        {
                            $request->session()->flash(
                                'alert' ,[
                                    'title'=>'پروژه!',
                                    'text'=> 'با موفقیت به وضیعت منتشر تغیر یافت.',
                                    'icon'=> 'success',
                                    'button' => 'ok']
                            );

                            return redirect()->back();
                        }
                        else
                        {
                            $request->session()->flash(
                                'alert' ,[
                                    'title'=>'پروژه!',
                                    'text'=> 'لغو نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                                    'icon'=> 'error',
                                    'button' => 'ok']
                            );

                            return redirect()->back();
                        }
                    }
                }
                else
                {

                    $tarahis = $tarahi->update([
                        'comison' => null,
                        'status'=>3
                    ]);
                    if($tarahis)
                    {

                        if($tarahi->comison !== null && $tarahi->tax > 0 && $tarahi->complications > 0)
                        {
                            $deposets = DepositCreate::create(auth()->user()->id, ' واریز بابت عودت مبلغ ضمانت پروژه '. $tarahi->title,$tarahi->registerDesigner->block_price,0,0,$time,4,Tarahi::class,$tarahi->id,0,0,0,null,null);

                            if($deposets )
                            {

                                $message = 'افزایش موجودی کیف پول بابت عودت مبلغ ضمانت پروژه.';

                                DepositJob::dispatch($deposets,$message)->delay(now()->addMinute($companies->job));
                            }

                        }

                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'پروژه!',
                                'text'=> 'با موفقیت لغو شد.',
                                'icon'=> 'success',
                                'button' => 'ok']
                        );

                        return redirect()->back();
                    }
                    else
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'پروژه!',
                                'text'=> 'لغو نشد،مشکلی پیش آمده با پشتیبانی تماس حاصل فرمایید.',
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
                        'title'=>'پروژه!',
                        'text'=> 'پروژه قبلا لغو شده است.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
        }
        else
        {
            return abort(404);
        }

    }
}
