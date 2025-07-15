<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\File;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Tarahi;
use App\Jobs\TarahiJob;
use App\Models\Company;
use App\Models\Profile;
use App\Models\Session;
use App\Models\Orderable;
use App\Models\ReqDesigner;
use App\Models\WebDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\TarahiNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserRegisterNotification;

class WebsiteDesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Route $route,Tarahi $tarahi,Company $company,User $user,Role $role,WebDesign $webDesign)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $results = null;
        $search =  $request->query->get('q');
        $type = $request->get('type');
        $category = $request->get('category');
        $sort = $request->get('sort');
        $updated = $request->get('updated');
        $time = new Carbon;
        $sessions = Session::updateCurrent();
        $flash = $request->session()->get('message');
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus?
            $route->where('name',$request->path())->first()->menus:null;
        $menu = Menu::where('parent_id',null)->where('status',4)->with('children','sections','routes')->get();
        $companies = $user->with('image')->first();
        $orders = $role->where('id',2)->with('users')->first()? $role->where('id',2)->with('users')->first():null;
        $webDesigns = $webDesign->with('image')->where('status',4)->orderBy('created_at','asc')->paginate(9)->WithQueryString();
        // dd($search,$request,$menus);
        if($companies)
        {

            if ($orders)
            {
                $usersRating = [];
                foreach ($orders->users as $key => $value) {
                    array_push($usersRating,["user" => $value->id , "rate" => $value->averageRating ? round($value->averageRating):null  ,
                    "count" => $value->timesRated() > 0 ? $value->timesRated():null]) ;
                }
            }
            else
            {
                $usersRating=null;
            }

            // dd($usersRating);
            if(Auth::check())
            {
                $users = $user->with('image')->with('profile')->with('roles')->find(auth()->user()->id);
            }
            else
            {
                $users=null;
            }
            // dd($users);
            if($search == '' && $type == '' && $sort == ''&& $updated == '' && $category == ''){$request->validate([
                'search' => 'required',
                ]);
            }
            elseif($search == 'all')
            {
                $results = $tarahi->with('discount')->with('menus')->with('user')->where([['status',4],['company_id',null]])->OrWhere('status',6)
                ->OrWhere('status',3)->withCount('offers')->withCount('comments')->paginate(9)->WithQueryString();

                return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,'users'=> $users,
                'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price, 'path'=>$request->path(),
                'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,'orders'=>$orders,'time'=>$time,
                'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),
                'usersRating' => $usersRating,'webDesigns' => $webDesigns,'menu' => $menu]);
            }

            else if($type)
            {
                $results = $tarahi->with('discount')->with('user')->with('menus')->orderBy('type','asc')->withCount('offers')->withCount('comments')
                ->where('type',$type)->where([['status',4],['company_id',null]])->OrWhere('status',6)
                ->OrWhere('status',3)->paginate(9)->WithQueryString();
                // dd($results);
                return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,
                    'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price,
                    'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,
                    'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'orders'=>$orders,'menu' => $menu,
                    'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString()]);
            }
            else if($category)
            {
                $results = $tarahi->with('user')->with('menus')->orderBy('category','asc')->withCount('offers')->withCount('comments')
                ->where('category',$category)->where('status',4)->OrWhere('status',6)->paginate(9)->WithQueryString();

                return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,
                    'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price,
                    'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,'menu' => $menu,
                    'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString()]);
            }
            else if($sort == 'DESC')
            {
                $results = $tarahi->with('user')->with('menus')->withCount('offers')->withCount('comments')->orderBy('id','desc')
                    ->where('status',4)->OrWhere('status',6)->paginate(9)->WithQueryString();

                    return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,
                    'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price,
                    'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,'menu' => $menu,
                    'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString()]);
            }

            else if($sort == 'cheapest')
            {
                $results = $tarahi->with('user')->with('menus')->withCount('offers')->withCount('comments')->orderBy('price','asc')
                ->where('status',4)->OrWhere('status',6)->paginate(9)->WithQueryString();

                return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,
                    'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price,
                    'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,'menu' => $menu,
                    'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString()]);
            }

            else if($sort == 'expensive')
            {
                $results = $tarahi->with('user')->with('menus')->withCount('offers')->withCount('comments')->orderBy('price','desc')
                ->where('status',4)->OrWhere('status',6)->paginate(9)->WithQueryString();

                return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,
                    'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price,
                    'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,'menu' => $menu,
                    'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString()]);
            }

            else if($updated == 'updateDate')
            {
                $results = $tarahi->with('user')->with('menus')->withCount('offers')->withCount('comments')->orderBy('updated_at','desc')
                    ->where('status',4)->OrWhere('status',6)->paginate(9)->WithQueryString();

                return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,
                    'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price,
                    'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,'menu' => $menu,
                    'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString()]);
            }

            else if($sort == 'Bestselling')
            {

                $results = $tarahi->with('user')->with('menus')->where('status',4)->OrWhere('status',6)->withCount('offers')->withCount('comments')
                ->orderBy('offers_count' ,'desc')->paginate(9)->WithQueryString();
                // dd($results,$tarahi->withCount('offers')offers_count);
                return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,
                    'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price,
                    'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,'menu' => $menu,
                    'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString()]);
            }
            else if($sort == 'open')
            {

                $results = $tarahi->with('user')->with('menus')->withCount('offers')->withCount('comments')->where('status',4)->where('expired_at','>',$time)->paginate(9)->WithQueryString();
                // dd($results);
                return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,
                'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price,
                'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,'menu' => $menu,
                'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString()]);
            }

            if($search !== '')
            {
                $searchResults = Tarahi::search($search)->get();

                $ids = $searchResults->pluck('id')->toArray();

                $results = Tarahi::whereIn('id', $ids)
                    ->with('user', 'menus')
                    ->withCount('offers')
                    ->withCount('comments')
                    ->whereIn('status', ['4', '6'])
                    ->paginate(9)
                    ->withQueryString();
                // $results = $tarahi->search($search)->with('user')->with('menus')->withCount('offers')->withCount('comments')
                // ->whereIn('status', ['4', '6'])->paginate(9)->WithQueryString();
                // dd($results);
                if( $results->total() > 1)
                {

                    return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,
                    'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price,
                    'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,'menu' => $menu,
                    'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString()]);
                }
                elseif( $results->total() == 1)
                {
                    $results = $tarahi->with('user')->with('menus')->withCount('offers')->withCount('comments')
                    ->where('id',$results[0]->id)->with('file')->paginate(9)->WithQueryString();

                    return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,
                    'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price,
                    'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,'menu' => $menu,
                    'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString()]);
                }
                else
                {
                    $results = null;
                    return Inertia::render('Guest/website-design-index',['menus' => $menus,'results'=> $results,
                    'cartTarahis'=>$cart->tarahis,'cartCount' =>$cart->count,'cartPrice'=> $cart->price,
                    'cartDiscount'=>$cart->discount,'cartCoupon'=>$cart->coupon,'cartTotal'=>$cart->total,'menu' => $menu,
                    'alert'=> $alert,'flash'=> $flash,'companies'=>$companies,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString()]);
                }

            }
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
    public function store(Request $request,Tarahi $tarahi,Role $role,User $user,File $file)
    {
            // dd($request->price);
            $time = new Carbon;

            $request->validate([
                'entekhab' => 'required',
                'name' => 'required|string|max:255',
                'lasst_name' => 'required|string|max:255',
                'email'=> 'required|string|email|max:255|unique:users',
                'text'=> 'required|string|max:65535',
                'group'=> 'required|max:255',
                'type'=> 'required|max:255',
                'title'=> 'required|string|max:255',
                'category'=> 'required|max:255',
                // 'price' => 'numeric',

            ]);

            $password =  substr( str_shuffle(md5(time())), 6, 20 );

            $users = User::create([

                'user_name' => $request->name . '_'. $request->lasst_name,
                'name' => $request->name,
                'lasst_name' => $request->lasst_name,
                'name_show'=> $request->name . '_'. $request->lasst_name,
                'email' => $request->email,
                'tel'=> $request->tel?$request->tel:null,
                'password' => Hash::make($password),
                'status' => 0,
                'person' => 0,
            ]);

            if($users)
            {

                $message = 'حساب کاربری شما با موفقیت ساخته شد.';
                $route = 'login';
                Notification::send($users , new UserRegisterNotification($users,$message,$route,$password));

               $profiles = Profile::create([
                    'user_id'=>$users->id,
                    'notification'=> 1,
                ]);
                if($profiles)
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
                        $tarahis = $tarahi->create([

                            'slug'=>$request->group['name'].'_'. $request->type['name'] .'_'.$request->category['name'] .'_'. $request->title,
                            'title'=> $request->title,
                            'text'=> $request->text,
                            'price'=>$request->price,
                            'user_id' => $users->id,
                            'group'=>$request->group['id'],
                            'type'=> $request->type['id'],
                            'category'=> $request->category['id'],
                            'expired_at' => $time->addDay(15),
                            'company_id' => $request->entekhab >0 ? $request->entekhab : null,
                        ]);

                        if($tarahis)
                        {

                            $categories = Menu::whereIn('id', [$request->group['id'], $request->type['id'], $request->category['id']])->get();
                            $tarahis->menus()->sync($categories);

                            TarahiJob::dispatch($tarahis)->delay(now()->addMinute(5));

                            $files = $request->file('file')?$request->file('file')->store('files'):null;

                            if($files)
                            {

                                File::create([
                                    'url'=>  $files,
                                    'fileable_type'=>Tarahi::class,
                                    'fileable_id'=> $tarahis->id,
                                    'status'=> 0,
                                ]);
                            }

                            // $massage = 'مشاهده پروژه شما.';
                            // $route = 'tarahi.index';
                            // $user = $user->find($users->id);
                            // Notification::send($user , new TarahiNotification($tarahis,$massage,$route,$user));

                            // $roles = $role->findOrfail(3);

                            // foreach ($roles->users as $key => $value)
                            // {
                            //     $message = 'یک پروژه جدید ارسال شده است.';
                            //     $route = 'tarahiAdmin.index';
                            //     $user = $user->find($value->id);
                            //     Notification::send($user , new TarahiNotification($tarahis,$message,$route,$user));
                            // }
                            $files = $request->file('file')?$request->file('file')->store('files'):null;
                            if($files)
                            {
                                $file->create([
                                    'url'=>  $files,
                                    'fileable_type'=>Tarahi::class,
                                    'fileable_id'=> $tarahis->id
                                ]);
                            }

                            $request->session()->flash(
                                'alert' ,[
                                    'title'=>'پروژه!',
                                    'text'=> 'با موفقیت ثبت شد، جهت پی گیری پروژه وارد حساب کاربری خود شوید.',
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
                                    'text'=> 'با موفقیت ثبت نشد، حتما مشکلی پیش آمده باپشتیبانی حاصل نمایید.',
                                    'icon'=> 'success',
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
                            'title'=>'پروفایل کاربری!',
                            'text'=> 'با موفقیت ثبت نشد، حتما مشکلی پیش آمده باپشتیبانی حاصل نمایید.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );
                    return redirect()->back();
                }
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'حساب کاربری!',
                        'text'=> 'با موفقیت ثبت نشد، حتما مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'success',
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
    public function show(Request $request,Tarahi $tarahi,User $user,Company $company,Menu $menu,ReqDesigner $reqdesigner,$id)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = auth()->user()?$user->with('image')->with('profile')->with('roles')->with('favorites')->findOrFail(auth()->user()->id):null;
        $tarahis =  $tarahi->with('discount')->with('comments')->with('menus')->with('user')->with('file')->with('favorite')->with('registerdesigner')->where('slug',$id)->first();
        $tarahi_averageRating = $tarahis->averageRating ? round($tarahis->averageRating) : null;
        $tarahi_timesRated = $tarahis->timesRated() ? round($tarahis->timesRated()) : null;
        if($tarahis && $tarahis->status == 3 || $tarahis &&  $tarahis->status == 4 || $tarahis &&  $tarahis->status == 6)
        {
            $tarahi_count = $tarahis->views()->count();
            $tarahi_order = $tarahis->orders->count();
            $time = new Carbon;
            views($tarahis)->cooldown($time->addDays(1))->record();
            $user_id = $tarahis->user->id;
            $count = $tarahi->where('user_id',$user_id)->get()->count();
            $companies = $user->with('image')->first();
            $companies2 = Company::with('user')->with('image')->first();
            $reqdesigners =  $reqdesigner->with('user')->where('tarahi_id',$tarahis->id)->where('status','>',3)->paginate(9);
            $menus = $menu->where('parent_id',null)->where('status','>',3)->with('children')->get();
            $carousels = $tarahi->with('menus')->withCount('comments')->orderBy('price','asc')->where('status',4)->where('id','<>',$tarahis->id)->paginate(10)->WithQueryString();

            $averageRating = [];
            foreach ($reqdesigners as $key => $value) {
                array_push($averageRating,["user" => $value->user->id , "rate" => $value->user->averageRating ? round($value->user->averageRating):null  ,
                "count" => $value->user->timesRated() > 0 ? $value->user->timesRated():null]) ;
            }

            // dd($companies );
            return Inertia::render('Guest/website-design-show',['tarahis'=>$tarahis,'count' => $count,'menus'=>$menus,
            'cartCount'=> $cart->count,'time'=>$time,'alert'=>$alert,'flash'=>$flash,'tarahi_count'=>$tarahi_count,
            'tarahi_order'=> $tarahi_order,'companies'=>$companies,'reqdesigners'=>$reqdesigners,'carousels' => $carousels,
            'users' => $users,'tarahi_averageRating'=> $tarahi_averageRating , 'tarahi_timesRated' => $tarahi_timesRated ,
            'tarahi_usersRated'=> $averageRating,'companies2'=>$companies2]);
        }
        else
        {
            return abort(404);
        }

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
    public function update(Request $request, $id)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(404);
    }
}
