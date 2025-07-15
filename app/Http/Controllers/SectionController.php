<?php

namespace App\Http\Controllers;

use App\Http\Utilities\SectionableCreate;
use App\Http\Utilities\SectionCreate;
use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Section;
use App\Models\Sectionable;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Section $section,Company $company,Route $route)
    {
        Gate::authorize('create',$section);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $sections = $section->with('user')->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/section-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'sections'=> $sections,
            'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Section $section,Company $company,Route $route)
    {
        Gate::authorize('create',$section);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/section-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications
            ,'companies' => $companies,'descriptions'=>$descriptions,'path'=>$request->path(),'wallet'=>$wallet
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $user,Section $section)
    {
        // dd($request);
        Gate::authorize('create',$section);

        $sections = SectionCreate::all($request);

        if($sections)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'قسمت!',
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
                    'title'=>'قسمت!',
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Section $section,Company $company,Route $route,Sectionable $sub)
    {
        Gate::authorize('create',$section);

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        // $sections = $section->with('menus')->find($section->id);
        $sections = $section->find($section->id);
        $subs = $sub->where('section_id',$section->id)->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(menu.show)')->first() && $route->where('name','route(menu.show)')->first()->descriptions?
            $route->where('name','route(menu.show)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/section-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications,
            'menus'=> $sections,'companies' => $companies,'descriptions'=>$descriptions,'subs'=>$subs,'wallet'=>$wallet]);
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
    public function update(Request $request,Sectionable $sectionable ,Section $section)
    {
        Gate::authorize('create',$section);
        if($request->del)
        {


            $sections = SectionableCreate::del($request);

            if($sections)
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


            $sections = SectionableCreate::all($request);

            if($sections)
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section,Request $request)
    {
        // dd($request,$section);
        Gate::authorize('create',$section);

        $sections = $section->delete();

        if($sections)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'!قسمت',
                    'text'=> '.باموفقیت حذف شد',
                    'icon'=> 'success',
                    'button' => 'ok']
            );
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'!قسمت',
                    'text'=> '.حذف نشد، مجدد تلاش نمایید',
                    'icon'=> 'error',
                    'button' => 'ok']
                );
        }
        return redirect()->back();
    }
}
