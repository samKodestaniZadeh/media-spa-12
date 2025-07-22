<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Rate;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Tarahi;
use App\Models\Comment;
use App\Models\Company;
use App\Jobs\TarahiReqJob;
use App\Models\Description;
use App\Models\ReqDesigner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Notifications\TarahiNotification;
use App\Notifications\TarahiReqNotification;
use Illuminate\Support\Facades\Notification;

class ReqDesignerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ReqDesigner $reqDesigner,Request $request,User $user,Company $company,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $names = $request->session()->has('names') ? $request->session()->get('names'):null;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $users = $user->with('image')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;

        $reqDesigners = $reqDesigner->with('user')->with('reqDesigner')->with('file')
        ->where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(9);
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        // dd($reqDesigners);
        return Inertia::render('Users/Designer/Tarahi/reqDesignerTarahi-index',
        ['users' => $users,'names'=> $names,'ids'=> $ids,'statuses'=> $statuses,
        'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,
        'reqDesigners'=>$reqDesigners,'alert'=> $alert,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
        'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
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
    public function store(Request $request,Tarahi $tarahi,ReqDesigner $reqdesigner,User $user,Role $role,Rate $rate,Company $company)
    {

        $companies = $company->first();
        if($request->id)
        {
            Gate::authorize('viewAny', $reqdesigner->find($request->id));
            $request->validate([
                'id' => 'required',
                'expired'=> 'required|numeric',
                'price'=> 'required|numeric|integer',
            ]);

            if($request->expired  < 1)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پیشنهاد!',
                        'text'=> 'تعداد روز نباید کمتر از 1 باشد.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
                return redirect()->back();
            }
            else
            {

                $reqdesigners = $reqdesigner->findOrfail($request->id);
                $tarahis = $reqdesigners->reqDesigner;
                // dd($request->price > $tarahis->price);
                if($tarahis->reqdesigner_id == $reqdesigners->id)
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'درخواست!',
                            'text'=> 'امکان ویرایش وجود ندارد، کار فرما شما را به عنوان طراح پروژه خود انتخاب نموده است.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );

                    return redirect()->back();
                }
                else
                {
                    $reqdesigner = $reqdesigners->update([
                        'expired'=> $request->expired,
                        'price'=> $request->price > $tarahis->price ? $request->price:$tarahis->price,
                    ]);

                    if($reqdesigner)
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'درخواست!',
                                'text'=> 'با موفقیت بروز شد.',
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
            }
        }
        else if($request->tarahi_id)
        {
            // dd(auth()->user() && $user->find(auth()->user()->id)->roles->find(2));
            if(auth()->user() && $user->find(auth()->user()->id)->roles->find(2))
            {
                $tarahis = $tarahi->find($request->tarahi_id);
                // dd($tarahis->price || $tarahis->price == null);
                if($request->price || $tarahis->price == null)
                {
                    $request->validate([
                        'tarahi_id' => 'required',
                        'expired'=> 'required|numeric',
                        'price'=> 'required|numeric',
                    ]);
                }
                else
                {
                    $request->validate([
                        'tarahi_id' => 'required',
                        'expired'=> 'required|numeric',
                    ]);
                }

                // dd($tarahis->expired_at , now('+3:30') , $tarahis->expired_at < now('+3:30'));
                if($tarahis && $tarahis->expired_at < now() )
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'پیشنهاد!',
                            'text'=> 'زمان ارسال برای این پروژه به اتمام رسیده است.',
                            'icon'=> 'error',
                            'button' => 'ok']
                        );
                    return redirect()->back();
                }
                else
                {
                    // $tarahis = $tarahi->with('regdesigner')->where('status',5)->OrWhere('status',7)->OrWhere('status',8)->get();
                    $reqdesigners = $reqdesigner->with('tarahiRegisterStatus')->where('user_id',auth()->user()->id)->where('status',5)->get();
                    $a = [];
                    foreach($reqdesigners as $key => $reqdesigner)
                    {

                        if($reqdesigner->tarahiRegisterStatus)
                        {
                            array_push ( $a,$reqdesigner->tarahiRegisterStatus  );
                        }

                    }

                    if($a && $a[0])
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'پیشنهاد!',
                                'text'=> 'شما پروژه در دست اقدام دارید که اتمام نشده ، لطفا پس از اتمام آن مجدد در خواست ارسال نمایید.',
                                'icon'=> 'error',
                                'button' => 'ok']
                            );
                        return redirect()->back();
                    }
                    else
                    {

                        if($request->expired > 0)
                        {
                            $tarahis = $tarahi->find($request->tarahi_id);

                            if($tarahis)
                            {
                                if($tarahis->user_id == auth()->user()->id)
                                {
                                    $request->session()->flash(
                                        'alert' ,[
                                            'title'=>'پیشنهاد!',
                                            'text'=> 'ثبت نشد، شما نمی توانید برای پروژه خود پیشنهاد ارسال نمایید.',
                                            'icon'=> 'error',
                                            'button' => 'ok']
                                    );

                                    return redirect()->back();
                                }
                                else if($tarahis->status == 6)
                                {
                                    $request->session()->flash(
                                        'alert' ,[
                                            'title'=>'پروژه!',
                                            'text'=> 'انجام شده،دیگر امکان ارسال پیشنهاد وجود ندارد.',
                                            'icon'=> 'error',
                                            'button' => 'ok']
                                    );

                                    return redirect()->back();
                                }
                                else if($tarahis->status == 3)
                                {
                                    $request->session()->flash(
                                        'alert' ,[
                                            'title'=>'پروژه!',
                                            'text'=> 'منقضی شده،دیگر امکان ارسال پیشنهاد وجود ندارد.',
                                            'icon'=> 'error',
                                            'button' => 'ok']
                                    );

                                    return redirect()->back();
                                }
                                else
                                {

                                    $tarahis = $reqdesigner->where('user_id',auth()->user()->id)->where('tarahi_id',$request->tarahi_id)->where('status',4)->first();
                                    // dd($tarahis);
                                    if($tarahis)
                                    {
                                        $request->session()->flash(
                                            'alert' ,[
                                                'title'=>'پیشنهاد!',
                                                'text'=> 'شما قبلا برای این پروژه پیشنهاد فرستاده اید.',
                                                'icon'=> 'error',
                                                'button' => 'ok']
                                        );

                                        return redirect()->back();
                                    }
                                    else
                                    {
                                        $reqdesigners = $reqdesigner->create([
                                            'user_id'=> auth()->user()->id,
                                            'tarahi_id'=> $request->tarahi_id,
                                            'expired'=> $request->expired,
                                            'block_price'=> 0,
                                            'price'=>$request->price && $request->price > $tarahi->find($request->tarahi_id)->price ? $request->price:$tarahi->find($request->tarahi_id)->price,
                                        ]);


                                        $tarahis = $tarahi->find($request->tarahi_id);
                                        $message = 'یک پیشنهاد برای پروژه شما ارسال شده است.';

                                        TarahiReqJob::dispatch($tarahis,$message)->delay(now()->addMinute($companies->job));

                                        if($reqdesigners)
                                        {
                                            $request->session()->flash(
                                                'alert' ,[
                                                    'title'=>'پیشنهاد!',
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
                                                    'title'=>'پیشنهاد!',
                                                    'text'=> 'ثبت نشد، مجدد تلاش نمایید.',
                                                    'icon'=> 'error',
                                                    'button' => 'ok']
                                            );

                                            return redirect()->back();
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $request->session()->flash(
                                    'alert' ,[
                                        'title'=>'پیشنهاد!',
                                        'text'=> 'ثبت نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
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
                                    'title'=>'پیشنهاد!',
                                    'text'=> 'تعداد روز نباید کمتر از 1 باشد.',
                                    'icon'=> 'error',
                                    'button' => 'ok']
                                );
                            return redirect()->back();
                        }
                    }
                }
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پیشنهاد!',
                        'text'=> 'ثبت نشد،فقط طراحان مجوز ارسال پیشنهاد را دارند.',
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
                    'title'=>'پیشنهاد!',
                    'text'=> 'ثبت نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
                );
            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReqDesigner  $reqDesigner
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReqDesigner  $reqDesigner
     * @return \Illuminate\Http\Response
     */
    public function edit(ReqDesigner $reqDesigner,Request $request,User $user,Company $company,Route $route)
    {
        Gate::authorize('view', $reqDesigner);

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $names = $request->session()->has('names') ? $request->session()->get('names'):null;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $users = $user->with('image')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $reqDesigner = $reqDesigner->with('user')->with('tarahiRegister')->with('file')->findOrfail($reqDesigner->id);
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(reqDesigner.edit)')->first() && $route->where('name','route(reqDesigner.edit)')->first()->descriptions?
            $route->where('name','route(reqDesigner.edit)')->first()->descriptions->first():null;

        return Inertia::render('Users/Designer/Tarahi/reqDesignerTarahi-edit',['users' => $users,'names'=> $names,
        'ids'=> $ids,'statuses'=> $statuses,'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,
        'reqDesigner'=>$reqDesigner,'alert'=> $alert]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReqDesigner  $reqDesigner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReqDesigner $reqDesigner)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReqDesigner  $reqDesigner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,ReqDesigner $reqDesigner,Tarahi $tarahi,User $user)
    {
        Gate::authorize('viewAny', $reqDesigner);
        // $tarahi = $tarahi->find($id);
        // $users = $user->find($tarahi->designer_id);

        if($reqDesigner->id)
        {
            if($reqDesigner->reqDesigner->reqdesigner_id == $reqDesigner->id)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'درخواست!',
                        'text'=> 'امکان لغو وجود ندارد، کار فرما درخواست شما را به عنوان طراح پروژه خود پذیرفته است.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
            else
            {
                $reqDesigners = $reqDesigner->update([
                    'status'=> 3
                ]);

                if($reqDesigners)
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'پیشنهاد!',
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
                            'title'=>'پیشنهاد!',
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
                    'title'=>'درخواست!',
                    'text'=> 'لغو نشد،مشکلی پیش آمده با پشتیبانی تماس حاصل فرمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }

    }
}
