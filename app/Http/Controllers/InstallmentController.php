<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Installment;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Models\Installmentable;
use Illuminate\Support\Facades\Gate;

class InstallmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Installment $installment,Company $company,Route $route)
    {
        // Gate::authorize('create',$installment);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $installments = $installment->with('user')->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/installment-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'installments'=> $installments,
            'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Company $company,Route $route)
    {
        // Gate::authorize('create',$installment);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/installment-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications
            ,'companies' => $companies,'descriptions'=>$descriptions,'path'=>$request->path(),'wallet'=>$wallet]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Installment $installment)
    {
        // Gate::authorize('create',$installment);
        // dd($request);
        $request->validate([
            'count' => 'required',
        ]);

        $menus = $installment->create([
            'user_id'=> auth()->user()->id,
            'count' => $request->count,
        ]);

        if($menus)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'قسط!',
                    'text'=> 'باموفقیت ثبت شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
                );

            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'قسط!',
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
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Installment $installment,Company $company,Route $route,Installmentable $sub)
    {
        // Gate::authorize('create',$installment);

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $installments = $installment->find($installment->id);
        $subs = $sub->where('installmentable_id',$installments->id)->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(menu.show)')->first() && $route->where('name','route(menu.show)')->first()->descriptions?
            $route->where('name','route(menu.show)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/installment-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications,
            'menus'=> $installments,'companies' => $companies,'descriptions'=>$descriptions,'subs'=>$subs,'wallet'=>$wallet]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function edit(Installment $installment)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Installment $installment,Installmentable $installmentable)
    {
        // Gate::authorize('create',$installment);
        if($request->del)
        {


            $installments = $installmentable->where('section_id',$request->id )->
            where('installmentable_type',$request->type)->
            where('installmentable_id',$request->del)->delete();

            if($installments)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'زیرمجموع!',
                        'text'=> 'باموفقیت حذف شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                    );
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'زیرمجموع!',
                        'text'=> 'حذف نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );
            }
            return redirect()->back();
        }
        else if($request->id && $request->subs == null && $request->installmentable_type == null && $request->installmentable_id == null)
        {
            // Gate::authorize('create',sectionable);
            $request->validate([
                'count' => 'required',
            ]);
            $installment = Installment::find($request->id);
            $menus = $installment->update([
                'user_id'=> auth()->user()->id,
                'count' => $request->count,
            ]);

            if($menus)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'قسط!',
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
                        'title'=>'قسط!',
                        'text'=> 'بروزرسانی نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );

                return redirect()->back();
            }

        }
        else
        {
            // dd($request);
            $request->validate([
                'id' => 'required|numeric',
                'installmentable_type' => 'required',
                'installmentable_id' => 'required|numeric',
            ]);

            $installments = $installmentable->updateorcreate([
                'installment_id'=> $request->id,
                'installmentable_type' => $request->installmentable_type ,
                'installmentable_id' => $request->installmentable_id,
            ]);

            if($installments)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'زیرمجموع!',
                        'text'=> 'باموفقیت ثبت شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                    );
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'زیرمجموع!',
                        'text'=> 'ثبت نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );
            }
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Installment $installment)
    {
        return abort(404);
    }
}
