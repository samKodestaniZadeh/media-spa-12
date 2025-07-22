<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\File;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Image;
use App\Models\Route;
use App\Models\Tarahi;
use App\Models\Company;
use App\Models\Product;
use App\Models\Profile;
use App\Jobs\ProfileJob;
use App\Models\Description;
use App\Models\ReqDesigner;
use App\Rules\Nationalcode;
use Illuminate\Http\Request;
use App\Http\Utilities\Ostan;
use App\Http\Utilities\Shahr;
use App\Http\Utilities\Wallet;
use App\Http\Utilities\ImageCreate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ProfileNotification;
use Illuminate\Support\Facades\Notification;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,File $file,Company $company,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('image')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $ostans = Ostan::all();
        $shahrs = Shahr::all();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $token = $request->session()->token();
        $wallet = Wallet::all($users);
        // dd($wallet);
        return Inertia::render('Users/Buyer/Profile/profile-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'users'=> $users,'ostans'=>$ostans,'shahrs'=>$shahrs,
        'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,'alert'=>$alert,'token'=>$token,
        'wallet'=>$wallet
        ]);

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
    public function store(Request $request,User $user,Profile $profile,Role $role,Company $company)
    {

         $image = $user->find(auth()->user()->id)->image  && $request->image === $user->find(auth()->user()->id)->image->url ? $user->find(auth()->user()->id)->image->url:null;

        if($request->tel)
        {
            $request->validate([
                'tel'=> 'required|digits:11',
            ]);
            $users = $user->find(auth()->user()->id)->update([
                'tel' => $request->tel,
            ]);

            if($users)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'شماره تلفن همراه!',
                        'text'=> 'باموفقیت تغییر یافت.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'شماره تلفن همراه!',
                        'text'=> 'تغییر نیافت، مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }

        }
        else if ($request->image !== $image )
        {

            $request->validate([
                'image' => 'required|max:5000|min:100',
            ]);
            // dd($request->id , $request->image );
            $users = $user->with('image')->with('profile')->find(auth()->user()->id);

            if($users->image)
            {

                $images = ImageCreate::all($request,$users,User::class);


                if($images)
                {
                    $profiles= $user->find(auth()->user()->id)->profile;
                    $profile->find($profiles->id)->update([
                        'status' => 0,
                    ]);

                    $companies = $company->first();
                    ProfileJob::dispatch($profiles)->delay(now()->addMinute($companies->job));

                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'تصویر!',
                            'text'=> 'باموفقیت بارگزاری شدپس از تایید نمایش داده خواهد شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );
                    return redirect()->back();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'تصویر!',
                            'text'=> 'بارگزاری نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );
                    return redirect()->back();
                }

            }
            else
            {
                $images = ImageCreate::all($request,$users,User::class);
                // dd($images);
                if($images)
                {
                    $profiles= $user->find(auth()->user()->id)->profile;
                    $profile->find($profiles->id)->update([
                        'status' => 0,
                    ]);
                    $companies = $company->first();
                    ProfileJob::dispatch($profiles)->delay(now()->addMinute($companies->job));

                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'تصویر!',
                            'text'=> 'باموفقیت بارگزاری شدپس از تایید نمایش داده خواهد شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );
                    return redirect()->back();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'تصویر!',
                            'text'=> 'بارگزاری نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );
                    return redirect()->back();
                }
            }
        }
        else
        {
            // dd($request);
            $request->validate([
                'name_show'=>'required',
                'phone'=>'numeric',
            ]);

            $user->find(auth()->user()->id)->update([

                'name_show' => $request->name_show,
                'phone' => $request->phone
            ]);

            $profiles = $user->find(auth()->user()->id)->profile;
            // dd($profiles);
            if($profiles)
            {

                $profile->find($profiles->id)->update([
                    'biography'=> $request->biography,
                    'status' => 0,
                ]);

                ProfileJob::dispatch($profiles)->delay(now()->addMinute($company->job));

                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پروفایل!',
                        'text'=> 'باموفقیت ثبت شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                    );
                return redirect()->back();
            }
            else
            {
                $profiles = $profile->Create([
                    'user_id' => auth()->user()->id,
                    'ostan' => $request->ostan,
                    'shahr' => $request->shahr,
                    'address' => $request->address,
                    'birth'=> $request->birth,
                    'gender'=> $request->gender,
                    'biography'=> $request->biography,
                    'wallet'=> 0,
                    'status' => 0,
                ]);

                if($profiles)
                {
                    ProfileJob::dispatch($profiles)->delay(now()->addMinute($company->job));
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'پروفایل!',
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
                            'title'=>'پروفایل!',
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
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Profile $profile,User $user,Product $product,Company $company,
    Route $route,Tarahi $tarahi,ReqDesigner $reqDesigner,$id)
    {
        if(Auth::check())
        {
            $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
            $cart = new Cart($oldCart);
            $users = $user->with('image')->with('profile')->with('roles')->findOrfail(auth()->user()->id);
            $notifications = $users->unreadnotifications;
            $user = $user->with('orders')->withCount('ordersProduct')->with('ordersProduct')->with('image')->with('profile')->where('user_name',$id)->firstOrFail();
            $products =  $product->with('image')->where('user_id',$user->id)->where('status',4)->paginate(9);
            $tarahis =  $tarahi->with('image')->where('user_id',$user->id)->paginate(9);
            $designers =  $reqDesigner->with('tarahiRegister')->where('user_id',$user->id)->paginate(9);
            $companies = $user->with('image')->first();
            $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
                $route->where('name',$request->path())->first()->descriptions->first():null;
            $wallet = Wallet::all($users);

            return Inertia::render('Users/Buyer/Profile/Profileuser-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'user'=> $user,'cartPrice'=> $cart->price,
                'cartCount'=> $cart->count,'cartDiscount'=> $cart->discount,'cartCoupon' => $cart->coupon,'user_products'=> $products,
                'cartTotal' => $cart->total,'products' => $cart->products,'tarahis' => $cart->tarahis,'users' => $users,
                'notifications'=> $notifications,'cartTax' => $cart->tax,'orders_count'=>$user->orders->count(),
                'cartComplications'=> $cart->complications,'companies' => $companies,'cartComison' => $cart->comison,
                'descriptions'=>$descriptions,'user_tarahis'=>$tarahis,'user_designers'=>$designers,'wallet'=>$wallet
            ]);
        }
        else
        {
            return redirect()->route('guest-profile.show',$id);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        return abort(404);
    }
}
