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
use App\Models\Report;
use App\Models\Tarahi;
use GuzzleHttp\Client;
use App\Jobs\TarahiJob;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Product;
use App\Models\Support;
use App\Jobs\DepositJob;
use App\Jobs\PaymentJob;
use App\Jobs\SupportJob;
use App\Models\Financial;
use App\Models\Description;
use App\Models\ReqDesigner;
use App\Jobs\TarahiAdminJob;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Jobs\TarahiDesignerJob;
use App\Http\Utilities\FileCreate;
use App\Http\Utilities\RateCreate;
use App\Http\Utilities\TarahiCreate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use App\Http\Utilities\DepositCreate;
use App\Http\Utilities\PaymentCreate;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\RateController;
use App\Notifications\TarahiNotification;
use App\Notifications\CommentNotification;
use App\Notifications\PaymentNotification;
use App\Notifications\ProductNotification;
use App\Notifications\SupportNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TarahiRegisterNotification;

class TarahiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Tarahi $tarahi,User $user,Company $company,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $time = new Carbon;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $names = $request->session()->has('names') ? $request->session()->get('names'):null;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $tarahis = $tarahi->with('user')->with('type')->with('reqDesigner')->with('registerDesigner')
        ->where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(9);

        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()?
            $route->where('name',$request->path())->first()->menus:null;
        $subject = $request->query->get('subject') == null?'All':$request->query->get('subject');
        $status = $request->query->get('status') == null? 'All' : $request->query->get('status');
        $times = $request->query->get('time') == null?'All':$request->query->get('time');

        $wallet = Wallet::all($users);

        if($times !== 'All' && $status == 'All' &&  $subject == 'All')
        {
                $tarahis = $tarahi->with('user')->with('type')->with('reqDesigner')->with('registerDesigner')
                ->where('user_id',$users->id)->where([
                    ['created_at','>=',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                    ['created_at','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]
                    ])->orderBy('created_at','desc')->paginate(9);

                    return Inertia::render('Users/Buyer/Tarahi/Tarahi-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tarahis'=> $tarahis,'time'=>$time,
                    'users' => $users,'names'=> $names,'menus' => $menus,'notifications'=> $notifications,
                    'companies'=>$companies,'descriptions'=>$descriptions,'alert'=> $alert,'subjects'=> $subject,
                    'times'=> $times,'statuses'=> $status,'path'=>$request->path(),'wallet'=>$wallet]);
        }
        elseif($status !== 'All' && $times == 'All' &&  $subject == 'All')
        {

                $tarahis = $tarahi->with('user')->with('type')->with('reqDesigner')->with('registerDesigner')
                ->where('user_id',auth()->user()->id)->where('status',$status)->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Buyer/Tarahi/Tarahi-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tarahis'=> $tarahis,'time'=>$time,
                'users' => $users,'names'=> $names,'menus' => $menus,'notifications'=> $notifications,
                'companies'=>$companies,'descriptions'=>$descriptions,'alert'=> $alert,'subjects'=> $subject,
                'times'=> $times,'statuses'=> $status,'path'=>$request->path(),'wallet'=>$wallet]);
        }
        elseif($subject !== 'All' && $times == 'All' && $status == 'All')
        {

                $tarahis = $tarahi->with('user')->with('type')->with('reqDesigner')->with('registerDesigner')
                ->where('user_id',auth()->user()->id)->where('type',$subject)->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Buyer/Tarahi/Tarahi-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tarahis'=> $tarahis,'time'=>$time,
                'users' => $users,'names'=> $names,'menus' => $menus,'notifications'=> $notifications,
                'companies'=>$companies,'descriptions'=>$descriptions,'alert'=> $alert,'subjects'=> $subject,
                'times'=> $times,'statuses'=> $status,'path'=>$request->path(),'wallet'=>$wallet]);
        }
        else
        {
            if($subject !== 'All' && $status !== 'All' && $times !== 'All')
            {
                $tarahis = $tarahi->with('user')->with('type')->with('reqDesigner')->with('registerDesigner')
                ->where('user_id',$users->id)->where([
                    ['created_at','>=',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                    ['created_at','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]
                    ])->where('status',$status)->where('type',$subject)->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Buyer/Tarahi/Tarahi-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tarahis'=> $tarahis,'time'=>$time,
                'users' => $users,'names'=> $names,'menus' => $menus,'notifications'=> $notifications,
                'companies'=>$companies,'descriptions'=>$descriptions,'alert'=> $alert,'subjects'=> $subject,
                'times'=> $times,'statuses'=> $status,'path'=>$request->path(),'wallet'=>$wallet]);
            }
            else if($subject !== 'All' && $status !== 'All')
            {
                $tarahis = $tarahi->with('user')->with('type')->with('reqDesigner')->with('registerDesigner')
                ->where('user_id',$users->id)->where('status',$status)->where('type',$subject)->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Buyer/Tarahi/Tarahi-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tarahis'=> $tarahis,'time'=>$time,
                'users' => $users,'names'=> $names,'menus' => $menus,'notifications'=> $notifications,
                'companies'=>$companies,'descriptions'=>$descriptions,'alert'=> $alert,'subjects'=> $subject,
                'times'=> $times,'statuses'=> $status,'path'=>$request->path(),'wallet'=>$wallet]);
            }
            else if($subject !== 'All' && $times !== 'All')
            {

                $tarahis = $tarahi->with('user')->with('type')->with('reqDesigner')->with('registerDesigner')
                ->where('user_id',$users->id)->where([
                    ['created_at','>=',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                    ['created_at','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]
                    ])->where('type',$subject)->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Buyer/Tarahi/Tarahi-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tarahis'=> $tarahis,'time'=>$time,
                'users' => $users,'names'=> $names,'menus' => $menus,'notifications'=> $notifications,
                'companies'=>$companies,'descriptions'=>$descriptions,'alert'=> $alert,'subjects'=> $subject,
                'times'=> $times,'statuses'=> $status,'path'=>$request->path(),'wallet'=>$wallet]);
            }
            else if($status !== 'All' && $times !== 'All')
            {

                $tarahis = $tarahi->with('user')->with('type')->with('reqDesigner')->with('registerDesigner')
                ->where('user_id',$users->id)->where([
                    ['created_at','>=',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                    ['created_at','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]
                    ])->where('status',$status)->orderBy('created_at','desc')->paginate(9);

                return Inertia::render('Users/Buyer/Tarahi/Tarahi-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tarahis'=> $tarahis,'time'=>$time,
                'users' => $users,'names'=> $names,'menus' => $menus,'notifications'=> $notifications,
                'companies'=>$companies,'descriptions'=>$descriptions,'alert'=> $alert,'subjects'=> $subject,
                'times'=> $times,'statuses'=> $status,'path'=>$request->path(),'wallet'=>$wallet]);

            }
            else
            {
                return Inertia::render('Users/Buyer/Tarahi/Tarahi-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tarahis'=> $tarahis,'time'=>$time,
                'users' => $users,'names'=> $names,'menus' => $menus,'notifications'=> $notifications,
                'companies'=>$companies,'descriptions'=>$descriptions,'alert'=> $alert,'subjects'=> $subject,
                'times'=> $times,'statuses'=> $status,'path'=>$request->path(),'wallet'=>$wallet]);
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
    public function store(Request $request,Tarahi $tarahi,Role $role,User $user,File $file,Rate $rate,Company $company)
    {
        $time = Carbon::now();
        $companies = $company->first();
        // dd($request);
        if($request->rate)
        {
            $request->validate([
                'name' => 'required',
                'id' => 'required',
                'text' => 'required',
                'price' => 'required',
                'rate' => 'required',
            ]);

            $tarahis = $tarahi->find($request->id);

                if($tarahis->registerDesigner->file)
                {
                    // dd($request->name == 'karfarma' && $tarahis->user_id == auth()->user()->id);
                    if($request->name == 'karfarma' && $tarahis->user_id == auth()->user()->id)
                    {
                        if($request->price == $tarahis->total)
                        {
                            if($tarahis->status == 8)
                            {
                                $tarahi = $tarahis->update([
                                    'status' => 7
                                ]);

                                if($tarahi)
                                {
                                    $rates = RateCreate::create(auth()->user()->id,$request->rate,$request->text,User::class,$tarahis->registerDesigner->user_id);

                                    if($rates)
                                    {
                                        $reqDesigner = $tarahis->registerDesigner;
                                        $message = 'کارفرما اتمام پروژه را اعلام نموده است.';
                                        TarahiDesignerJob::dispatch($reqDesigner,$message)->delay(now()->addMinute($companies->job));

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
                                    'text'=> 'مبلغ پروژه با مبلغ ثبتی توسط شما مطابقت ندارد.چنانچه با طراح به اختلاف و یا مشکلی برخورد کردید، از منو پشتبانی اقدام نمایید. ',
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
                                'text'=> 'فقط کارفرما، امکان اعلام اتمام پروژه را دارد.',
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
                            'text'=> 'فایل پروژه بار گذاری نشده، امکان اعلام اتمام آن وجود ندارد.',
                            'icon'=> 'error',
                            'button' => 'ok'
                        ]
                    );

                    return redirect()->back();
                }



        }
        else if($request->expired)
        {
            $tarahi = $tarahi->find($request->id);

            if($request->name == 'karfarma' && auth()->user()->id == $tarahi->user_id)
            {
                $request->validate([
                    'id' => 'required',
                    'expired'=> 'required|numeric|integer',
                ]);
                if($tarahi->status == 8 || $tarahi->status == 5 )
                {

                    if($time->createFromFormat('Y-m-d H:i:s.u',$tarahi->find($request->id)->date) <
                    $time->createFromFormat('Y-m-d H:i:s.u',$tarahi->find($request->id)->date)->addDay($request->expired))
                    {
                        $tarahis = $tarahi->update([
                            'date'=> $time->createFromFormat('Y-m-d H:i:s.u',$tarahi->find($request->id)->date)->addDay($request->expired),
                        ]);

                        if($tarahis)
                        {
                            $tarahi = $tarahi->find($request->id)->registerDesigner;

                            $message = 'پروژه شما توسط کارفرما تمدید شد.';

                            TarahiDesignerJob::dispatch($tarahi,$message)->delay(now()->addMinute($companies->job));

                            $request->session()->flash(
                                'alert' ,[
                                    'title'=>'پروژه!',
                                    'text'=> 'زمان انقضا پروژه با موفقیت تمدید شد.',
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
                                    'text'=> 'انجام نشد ، مجدد تلاش نمایید.',
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
                                'title'=>'پروژه!',
                                'text'=> 'زمان انقضا پروژه نمی تواند کمتر از زمان قبلی پروژه باشد.',
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
                            'title'=>'پروژه!',
                            'text'=> 'در این مرحله امکان تمدید زمان وجود ندارد.',
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
                        'title'=>'پروژه!',
                        'text'=> 'امکان تمدید زمان پروژه توسط شما وجود ندارد.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
        }
        else if($request->id)
        {

            $tarahi = $tarahi->find($request->id);

            if($tarahi->status == 0)
            {
                $tarahis = TarahiCreate::all($request);

                if($tarahis)
                {
                    $tarahis = $tarahi->find($request->id);
                    $categories = Menu::whereIn('id', [$request->group['id'], $request->type['id'], $request->category['id']])->get();
                    $tarahis->menus()->sync($categories);

                    $files = $request->file('file')?$request->file('file')->store('files'):null;

                    if($files && $tarahi->find($request->id)->file && $files !== $tarahi->find($request->id)->file->url)
                    {
                        Storage::delete( $tarahi->find($request->id)->file);
                        $tarahi->find($request->id)->file()->update([
                            'url'=>  $files,
                            'fileable_type'=>Tarahi::class,
                            'fileable_id'=> $tarahi->find($request->id)->id,
                            'status'=> 0,
                        ]);
                    }

                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'پروژه!',
                            'text'=> 'با موفقیت بروز رسانی شد.',
                            'icon'=> 'success',
                            'button' => 'ok'
                            ]
                    );

                    return redirect()->back();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'پروژه!',
                            'text'=> 'بروز رسانی نشد،مشکلی پیش آمده با پشتیبانی تماس حاصل فرمایید.',
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
                        'title'=>'پروژه!',
                        'text'=> 'در این مرحله مجوز ویرایش را ندارید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
        }
        else
        {


            if(

                $tarahi->where('slug',$request->group['name'] .'_'. $request->type['name'] .'_'. $request->category['name'] .'_'. $request->title)->pluck('slug')->first() == $request->group['name'].'_'. $request->type['name'].'_'.$request->category['name'].'_'. $request->title
            )
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پروژه!',
                        'text'=> 'بااین مشخصات قبلا ثبت شده است و امکان ثبت مجدد وجود ندارد ، عنوان دیگری برای پروژه خود انتخاب کنید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
                return redirect()->back();
            }
            else
            {

                $tarahis = TarahiCreate::all($request);
                if($tarahis)
                {

                    if(count($tarahis->menus) > 0)
                    {
                        $categories = Menu::whereIn('id', [$request->group['id'], $request->type['id'], $request->category['id']])->get();
                        $tarahis->menus()->sync($categories);
                    }
                    else
                    {
                        $categories = Menu::whereIn('id', [$request->group['id'], $request->type['id'], $request->category['id']])->get();
                        $tarahis->menus()->attach($categories);
                    }


                    $message = ' پروژه شما در وضعیت انتظار قرار گرفت.';

                    TarahiJob::dispatch($tarahis,$message)->delay(now()->addMinute($companies->job));

                    $message = ' پروژه نیاز به تایید دارد.';

                    TarahiAdminJob::dispatch($tarahis,$message)->delay(now()->addMinute($companies->job));

                    $file = $request->file('file')?$request->file('file')->store('files'):null;
                    if($file)
                    {
                        $files = FileCreate::all($request,$tarahis,Tarahi::class);
                    }



                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'پروژه!',
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
                            'title'=>'پروژه!',
                            'text'=> 'ثبت نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
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
     * @param  \App\Models\Tarahi  $tarahi
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Tarahi $tarahi,Company $company,Description $description,
    ReqDesigner $reqDesigner,Route $route)
    {
        // Gate::authorize('viewAny', $tarahi);

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->notifications;
        $tarahis = $tarahi->with('file')->with('user')->find($tarahi->id);
        $reqDesigners = $tarahis->reqDesigner->first()? $reqDesigner->with('file')->find($tarahis->reqDesigner->first()->id):null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(tarahi.show)')->first() && $route->where('name','route(tarahi.show)')->first()->descriptions?
            $route->where('name','route(tarahi.show)')->first()->descriptions->first():null;
        $menus = $route->where('name','route(tarahi.show)')->first()? $route->where('name','route(tarahi.show)')->first()->menus:null;

        $wallet = Wallet::all($users);

        return Inertia::render('Users/Buyer/Tarahi/Tarahi-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tarahis'=> $tarahis,'cartCount'=> $cart->count,
        'alert'=>$alert,'flash'=>$flash,'users'=>$users,'notifications'=>$notifications,'companies'=>$companies,
        'descriptions'=>$descriptions,'menus' => $menus,'reqDesigner'=>$reqDesigners,'path'=> 'route(tarahi.show)'
        ,'wallet'=>$wallet]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarahi  $tarahi
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Tarahi $tarahi,$id,Order $order,User $user)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarahi  $tarahi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarahi $tarahi)
    {
        // Gate::authorize('viewAny', $tarahi);
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarahi  $tarahi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Tarahi $tarahi,User $user,Support $support,Role $role,ReqDesigner $reqDesigner,Company $company)
    {
        Gate::authorize('viewAny', $tarahi);
        $time = new Carbon;
        $companies = $company->first();

        // dd($tarahi);
        if($tarahi->status !== 3)
        {
            // dd($tarahi->reqdesigner_id,$tarahi);
            if($tarahi->reqdesigner_id)
            {
                // $reqDesigners = $reqDesigner->where('tarahi_id',$tarahi->id)->get();
                // dd($tarahi->registerdesigner->block_price > 0 && $tarahi->user_id == auth()->user()->id &&
                // $tarahi->registerdesigner->canceller_id == null);

                if($tarahi->registerDesigner->block_price > 0 && $tarahi->user_id == auth()->user()->id && $tarahi->registerDesigner->canceller_id == null)
                {
                    // dd($user->find($tarahi->user_id)->profile->wallet , $tarahi->total,$tarahi->registerdesigner->block_price);
                    $tarahis = $tarahi->update([
                        'status'=>3,
                        'canceller_id'=>auth()->user()->id,
                    ]);

                    if($tarahis)
                    {
                        $deposets = DepositCreate::create(auth()->user()->id, ' واریز بابت رفع مسدودی مبلغ پروژه '. $tarahi->title,$tarahi->price_block,0,0,$time,4,Tarahi::class,$tarahi->id,0,0,0,null,null);

                        if($deposets )
                        {

                            $message = 'افزایش موجودی کیف پول بابت رفع مسدودی مبلغ پروژه.';

                            DepositJob::dispatch($deposets,$message)->delay(now()->addMinute($companies->job));
                        }

                        $payments = PaymentCreate::create(auth()->user()->id,'برداشت بابت جریمه لغو پروژه '. $tarahi->title.' توسط شما ',$tarahi->price_block*$companies->design_damage,0,0,$time,4,Tarahi::class,$tarahi->id,0,0,0,null,null);

                        if($payments)
                        {
                            $message = ' کاهش موجودی کیف پول بابت جریمه لغو پروژه توسط شما.';

                            PaymentJob::dispatch($payments,$message)->delay(now()->addMinute($companies->job));

                        }

                        $deposets = DepositCreate::create($tarahi->registerDesigner->user_id, ' واریز بابت جریمه لغو پروژه '. $tarahi->title.' توسط کارفرما ',$tarahi->price_block*$companies->design_damage,0,0,$time,4,Tarahi::class,$tarahi->id,0,0,0,null,null);

                        if($deposets )
                        {

                            $message = 'افزایش موجودی کیف پول بابت جریمه لغو پروژه توسط کارفرما.';

                            DepositJob::dispatch($deposets,$message)->delay(now()->addMinute($companies->job));
                        }

                        $deposets = DepositCreate::create($tarahi->registerDesigner->user_id, ' واریز بابت رفع مسدودی مبلغ ضمانت پروژه '. $tarahi->title,$tarahi->registerDesigner->block_price,0,0,$time,4,Tarahi::class,$tarahi->id,0,0,0,null,null);

                        if($deposets )
                        {

                            $message = 'افزایش موجودی کیف پول بابت رفع مسدودی مبلغ ضمانت پروژه.';

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
                    'status'=>3
                ]);
                if($tarahis)
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'پروژه!',
                            'text'=> 'لغو شد.',
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

}
