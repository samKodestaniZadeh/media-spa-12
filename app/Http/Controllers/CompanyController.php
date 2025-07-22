<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\File;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Image;
use App\Models\Route;
use App\Models\Company;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Ostan;
use App\Http\Utilities\Shahr;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Company $company,Route $route)
    {
        Gate::authorize('create', $company);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $dark = $request->session()->has('class') ? $request->session()->get('class') :null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $time = new Carbon();
        $companies = $company->with('user')->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $ostans = Ostan::all();
        $shahrs = Shahr::all();
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Company/company-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'companies'=> $companies,
        'notifications'=> $notifications,'dark'=>$dark,'descriptions'=>$descriptions,'ostans'=>$ostans,'shahrs'=>$shahrs,'wallet'=>$wallet,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Company $company,Route $route)
    {
        Gate::authorize('create', $company);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $dark = $request->session()->has('class') ? $request->session()->get('class') :null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $time = new Carbon();
        $ostans = Ostan::all();
        $shahrs = Shahr::all();
        $companies = $company->with('user')->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Company/company-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,
        'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'notifications'=> $notifications,
        'dark'=>$dark,'ostans'=>$ostans,'shahrs'=>$shahrs,'companies'=> $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,File $file,Company $company)
    {
        // dd($request);
        Gate::authorize('create', $company);
        $request->validate([
            'tax' => 'required',
            'complications' => 'required',
            'comison' => 'required',
            'comison_designer'=> 'required',
            'design_damage'=> 'required'
        ]);
        // dd($request);
        if($request->id)
        {
            $companys = $company->with('user')->with('image')->find($request->id);
             $companys->update([
                'user_id' => auth()->user()->id,
                'tax'=>$request->tax,
                'complications'=> $request->complications,
                'comison'=> $request->comison,
                'comison_designer'=> $request->comison_designer,
                'design_damage'=> $request->design_damage,
                'job' => $request->job || $companys->job,
            ]);

            $request->session()->flash(
                'alert' ,[
                    'title'=>'مشخصات!',
                    'text'=> 'با موفقیت ثبت شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->back();


        }
        else
        {
            $companys = $company->create([
                'user_id' => auth()->user()->id,
                'tax'=>$request->tax,
                'complications'=> $request->complications,
                'comison'=> $request->comison,
                'comison_designer'=> $request->comison_designer,
                'design_damage'=> $request->design_damage,
            ]);

            $request->session()->flash(
                'alert' ,[
                    'title'=>'مشخصات!',
                    'text'=> 'با موفقیت ثبت شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,User $user,Company $company,Description $description)
    {
        return abort(404);
        // Gate::authorize('create', $company);
        // $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        // $cart = new Cart($oldCart);
        // $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        // $dark = $request->session()->has('class') ? $request->session()->get('class') :null;
        // $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        // $notifications = $users->unreadnotifications;
        // $time = new Carbon();
        // $ostans = Ostan::all();
        // $shahrs = Shahr::all();
        // $companies = $company->with('user')->with('image')->find($company->id);
        // $descriptions = $description->where('route',$request->path())->first();
        // $wallet = Wallet::all($users);

        // return Inertia::render('Users/Admin/Company/company-edit',['cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,
        // 'cartCoupon' => $cart->coupon,'cartTotal' => $cart->total,'alert'=>$alert,'users' => $users,'notifications'=> $notifications,
        // 'dark'=>$dark,'ostans'=>$ostans,'shahrs'=>$shahrs,'companies'=>$companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        return abort(404);
    }
}
