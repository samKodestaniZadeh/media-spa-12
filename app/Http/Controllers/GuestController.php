<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Blog;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Coupon;
use App\Models\Social;
use App\Models\Tarahi;
use App\Jobs\TarahiJob;
use App\Models\Company;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Session;
use App\Models\Support;
<<<<<<< HEAD
use App\Models\Discount;
use App\Models\Orderable;
use App\Models\WebDesign;
// use Illuminate\Support\Facades\Route;
use App\Models\RouteModel;
use Illuminate\Http\Request;
use App\Notifications\SupportNotif;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
=======
use App\Jobs\DepositJob;
use App\Models\Discount;
use App\Models\Orderable;
// use Illuminate\Support\Facades\Route;
use App\Models\WebDesign;
use App\Models\RouteModel;
use Illuminate\Http\Request;
use Shetabit\Multipay\Invoice;
use App\Notifications\SupportNotif;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Shetabit\Payment\Facade\Payment;
use App\Http\Utilities\DepositCreate;
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
use Illuminate\Foundation\Application;
use App\Notifications\SupportNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserRegisterNotification;


class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Coupon $coupon,User $user,Discount $discount,Social $social,Route $route,Product $product,Orderable $orderable,Tarahi $tarahi,Blog $blog)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart') : null;
        $cart = new Cart($oldCart);
        $time = Carbon::now();
        // $discounts = $discount->with('discountable')->where('expired','>',$time)->paginate(10)->WithQueryString();
        $alert = $request->session()->has('alert') ? $request->session()->get('alert') : null;
        // $menus = $menu->where('parent_id', null)->where('section', 'supports')->where('status', 4)->with('children')->where('status', 4)->get();
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()?
            $route->where('name',$request->path())->first()->menus:null;
        $menu = Menu::where('parent_id',null)->where('status',4)->with('children','sections','routes')->get();
        $sessions = Session::updateCurrent();
        $coupon_count = $coupon->where('user_id',null)->count();
        $companies = $user->with('image')->with('profile')->first();
        $socials = $social->where('status',4)->get();
        $users = auth() && auth()->user();
        $results = $product->with('discount')->with('image')->with('file')->with('user')->where('status',4)->OrWhere('status',5)->with('menus')
                    ->withCount('orders')->withCount('comments')->withAvg('ratings', 'rating')->limit(5)->get();
        $discounts = $discount->where('expired','>',$time)->where('discountable_type' , 'App\\Models\\Product')->with('discountable')
            ->paginate(9)->WithQueryString();
        $orders = $orders = $orderable->with('product')->where('orderable_type', 'App\Models\Product')->whereYear('created_at', '=', Carbon::now()->year)
            ->whereMonth('created_at', '=', Carbon::now()->month)->select('orderable_id')->selectRaw('count(`orderable_id`) as `occurences`')->groupBy('orderable_id')
            ->having('occurences', '>', 0)->orderByDesc('occurences')->limit(5)->get();
        $webDesigns = $tarahi->with('discount')->with('user')->with('menus')->where([['status',4],['company_id',null]])->withCount('offers')->withCount('comments')->limit(4)->get();
        $blogs = $blog->with('image')->with('group')->with('type')->with('category')->withCount('comments')->with('user')->with('menus')->withCount('views')->where('status',4)->limit(5)->get();
<<<<<<< HEAD
        if($companies)
        {
            // dd($menus);
=======

        if($companies)
        {
            // dd($results);
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
            return Inertia::render('Guest/index', ['canLogin' => 'Illuminate\Support\Facades\Route'::has('login'),
                'canRegister' => 'Illuminate\Support\Facades\Route'::has('register'),'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION, 'alert' => $alert,'discounts' => $discounts, 'time' => $time, 'menus' => $menus, 'menu' => $menu,
                'coupon_count'=>$coupon_count,'results'=> $results,'companies'=>$companies,'socials'=> $socials,'path' => $request->path(),
                'users' => $users,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,
                'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,
                'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=>$orders,'webDesigns' => $webDesigns,'blogs' => $blogs]);
        }
        else
        {
            return abort(503);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Support $support, Role $role, User $user)
    {
        if(Auth::check())
        {
            $request->validate([
                'recepiant'=> 'required',
                'subject'=>'required',
                'text'=>'required',
            ]);

            $supports = $support->create([
                'user_id' => auth()->user()->id,
                'parent_id' => 0,
                'destination'=> 0,
                'menu' => $request->menu,
                'recepiant' => $request->recepiant,
                'subject' => $request->subject,
                'text' => $request->text,
                'status' => 0,
            ]);

            if($supports)
            {


                $message = 'یک پیام ارسال شده است.';
                $route = 'supportAdmin.index';
                $users = $role->find(3);
                foreach ($users->users as $key => $user)
                {
                    Notification::send($user , new SupportNotification($supports,$message,$route,$user));
                }

                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!پیام',
                        'text'=> '.باموفقیت ارسال شد',
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
                        'text'=> '.ارسال نشد،مشکلی پیش آمده با پشتیبانی تماس تلفنی برقرار نمایید',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->route('dashboard.index');
            }
        }
        else
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'lasst_name' => 'required|string|max:255',
                'email'=> 'required|string|email|max:255|unique:users',
                'menu' => 'required',
                'recepiant'=> 'required',
                'subject'=>'required',
                'text'=>'required|string|max:255',

            ]);

            $password =  substr( str_shuffle(md5(time())), 6, 20 );
            $users = User::create([
                'user_name' => $request->name . ' '. $request->lasst_name,
                'name' => $request->name,
                'lasst_name' => $request->lasst_name,
                'email' => $request->email,
                'tel'=> $request->tel?$request->tel:null,
                'password' => Hash::make($password),
                'status' => 0
            ]);

            $message = 'حساب کاربری شما با موفقیت ساخته شد.';
            $route = 'login';
            Notification::send($users , new UserRegisterNotification($users,$message,$route,$password));

            Profile::create([
                'user_id'=>$users->id,
                'notification'=> 1,
            ]);

            $supports = $support->create([
                'user_id' => auth()->user()->id,
                'parent_id' => 0,
                'destination'=> 0,
                'menu' => $request->menu,
                'recepiant' => $request->recepiant,
                'subject' => $request->subject,
                'text' => $request->text,
                'status' => 0,
            ]);

            if($supports)
            {


                $message = 'یک پیام ارسال شده است.';
                $route = 'supportAdmin.index';
                $users = $role->find(3);
                foreach ($users->users as $key => $user)
                {
                    Notification::send($user , new SupportNotification($supports,$message,$route,$user));
                }

                $request->session()->flash(
                    'alert' ,[
                        'title'=>'!پیام',
                        'text'=> '.باموفقیت ارسال شد',
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
                        'text'=> '.ارسال نشد،مشکلی پیش آمده با پشتیبانی تماس تلفنی برقرار نمایید',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->route('dashboard.index');
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(404);
    }
}
