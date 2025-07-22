<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Models\Descriptionable;
use Illuminate\Support\Facades\Gate;

class DescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Company $company,Description $description,Route $route)
    {
        Gate::authorize('create', $description);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $description = $description->with('user')->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/description-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'description'=> $description,'descriptions'=>$descriptions,
            'users' => $users,'notifications'=> $notifications,'companies' => $companies,'alert'=>$alert,'wallet'=>$wallet
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Description $description,Company $company,Route $route)
    {
        Gate::authorize('create',$description);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/description-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications
            ,'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Description $description)
    {
        Gate::authorize('create',$description);

        $request->validate([
            'subject' => 'required',
            'text' => 'required',
        ]);

        if($request->id)
        {
            $request->validate([
                'id' => 'required',
                'subject' => 'required',
                'text' => 'required',
            ]);
            $descriptions = $description->find($request->id)->update([
                'user_id'=> auth()->user()->id,
                'subject' => $request->subject,
                'text' => $request->text,
                'status'=> $request->status,
            ]);

            if($descriptions)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'توضیحات!',
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
                        'title'=>'توضیحات!',
                        'text'=> 'بروزرسانی نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                    );

                return redirect()->back();
            }
        }
        else
        {
            $descriptions = $description->create([
                'user_id'=> auth()->user()->id,
                'subject' => $request->subject,
                'text' => $request->text,
                'status'=> 4,
            ]);
            if($descriptions)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'توضیحات!',
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
                        'title'=>'توضیحات!',
                        'text'=> 'ثبت نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
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
     * @param  \App\Models\Description  $description
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Description $description,Company $company,Route $route,Descriptionable $sub)
    {
        Gate::authorize('create',$description);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $description = $description;
        $subs = $sub->where('description_id',$description->id)->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(description.show)')->first() && $route->where('name','route(description.show)')->first()->descriptions?
            $route->where('name','route(description.show)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/description-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications,
            'descriptions'=> $descriptions,'companies' => $companies,'description'=> $description,'subs'=>$subs,'wallet'=>$wallet]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Description  $description
     * @return \Illuminate\Http\Response
     */
    public function edit(Description $description)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Description  $description
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Description $description,Descriptionable $descriptionable)
    {

            if($request->del)
            {

                // dd($request);
                $routes = $descriptionable->where('description_id',$request->id )->
                where('descriptionable_type',$request->type)->
                where('descriptionable_id',$request->del)->delete();
                // $routeables = $route->find($request->id)->sub()->detach($routes);
                // dd($routes,$routeables);
                if($routes)
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
            else
            {
                $request->validate([
                    'id' => 'required|numeric',
                    'descriptionable_type' => 'required',
                    'descriptionable_id' => 'required|numeric',
                ]);

                $routes = $descriptionable->updateorcreate([
                    'description_id'=> $request->id,
                    'descriptionable_type' => $request->descriptionable_type ,
                    'descriptionable_id' => $request->descriptionable_id,
                ]);

                if($routes)
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
     * @param  \App\Models\Description  $description
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Description $description)
    {
        Gate::authorize('create',$description);

        $descriptions = $description->delete();
        if($descriptions)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'!توضیحات',
                    'text'=> '.باموفقیت حذف شد',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->route('dashboard.index');
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'!توضیحات',
                    'text'=> '.باموفقیت حذف نشد',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->route('dashboard.index');
        }

    }
}
