<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Route;
use App\Models\Company;
use App\Models\Product;
use App\Jobs\ProductJob;
use App\Models\Menuable;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Http\Utilities\FileCreate;
use App\Http\Utilities\ImageCreate;
use Illuminate\Support\Facades\Gate;
use App\Http\Utilities\ProductCreate;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ProductNotification;
use Illuminate\Support\Facades\Notification;

class ProductAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Product $product,Order $order,User $user,Company $company,Route $route)
    {
        // Gate::authorize('update', $product);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $times = Carbon::now();
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $orders = $order->where('user_id',auth()->user()->id)->with('productimage')->paginate(9);
        $products = $product->with('group')->with('type')->with('user')->where('id','>',0)->with('image')->orderBy('created_at','desc')->paginate(9);
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()?
            $route->where('name',$request->path())->first()->menus:null;
        $subject = $request->query->get('subject') == null?'All':$request->query->get('subject');
        $status = $request->query->get('status') == null? 'All' : $request->query->get('status');
        $time = $request->query->get('time') == null?'All':$request->query->get('time');
        $wallet = Wallet::all($users);

        // return Inertia::render('Users/Admin/Product/Product-index',['orders'=> $orders,'users' => $users,
        // 'products'=> $products,'names'=> $names,'ids'=> $ids,'statuses'=> $statuses,'notifications'=> $notifications,
        // 'companies' => $companies,'descriptions'=>$descriptions,'path'=>$request->path(),'wallet'=>$wallet]);
        // dd($status );
        if($time !== 'All' && $status == 'All' &&  $subject == 'All')
        {

            $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],
                ['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->with('user')->where('id','>',0)->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Product/Product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet,
                'notifications'=> $notifications,'path'=>$request->path()]);
        }
        elseif($status !== 'All' && $time == 'All' &&  $subject == 'All')
        {
                $products = $product->with('user')->with('group')->with('type')->withCount('orders')->with('image')->where('status',$status)->paginate(9)->withQueryString();


                return Inertia::render('Users/Admin/Product/Product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet,
                'notifications'=> $notifications,'path'=>$request->path()]);

        }
        elseif($subject !== 'All' && $time == 'All' && $status == 'All')
        {

                $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where('type',$subject)
                ->with('user')->where('id','>',0)->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Product/Product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet,
                'notifications'=> $notifications,'path'=>$request->path()]);

        }
        else
        {
            if($subject !== 'All' && $status !== 'All' && $time !== 'All')
            {
                $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where('type',$subject)->where('status',$status)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->with('user')->where('id','>',0)->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Product/Product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet,
                'notifications'=> $notifications,'path'=>$request->path()]);
            }
            else if($subject !== 'All' && $status !== 'All')
            {
                $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where('type',$subject)
                ->where('status',$status)->with('user')->where('id','>',0)->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Product/Product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet,
                'notifications'=> $notifications,'path'=>$request->path()]);
            }
            else if($subject !== 'All' && $time !== 'All')
            {
                $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where('type',$subject)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->with('user')->where('id','>',0)->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Product/Product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet,
                'notifications'=> $notifications,'path'=>$request->path()]);
            }
            else if($status !== 'All' && $time !== 'All')
            {
                $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where('status',$status)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->with('user')->where('id','>',0)->paginate(9)->withQueryString();

                return Inertia::render('Users/Admin/Product/Product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet,
                'notifications'=> $notifications,'path'=>$request->path()]);
            }
            else
            {
                return Inertia::render('Users/Admin/Product/Product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet,
                'notifications'=> $notifications,'path'=>$request->path()]);
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
    public function store(Request $request,Product $product,User $user,Company $company)
    {
        Gate::authorize('update', $product);
        $pro = $product->with('image')->with('file')->find($request->id);

        $products = ProductCreate::updateAdmin($request);

        if($products)
        {
            $products = $product->find($request->id);
            $categories = Menu::whereIn('id', [$request->group['id'], $request->type['id'], $request->category['id']])->get();
            $products->menus()->sync($categories);

            // dd($request->prerequisites,$request->prerequisite_version);
            if($request->prerequisites)
            {
                foreach ($request->prerequisites as $key => $value)
                {
                    $categories = Menu::whereIn('id',[$value['id']])->get();
                    $menuables = Menuable::where([['menu_id',$value['id']],['menuable_type','App\Models\Product'],['menuable_id',$request->id]])->get();

                    if ( count($menuables) > 0) {


                    }
                    else{
                        $products->menus()->attach($categories);
                    }

                }

            }

            if($request->prerequisite_version)
            {
                foreach ($request->prerequisite_version as $key => $value)
                {
                    // dd($value);
                    $categories = $value ? Menu::whereIn('id',[$value['id']])->get() : null;
                    $menuables = $value ? Menuable::where([['menu_id',$value['id']],['menuable_type','App\Models\Product'],['menuable_id',$request->id]])->get() : null;


                    if ($menuables && count($menuables) > 0) {


                    }
                    else if($value)
                    {


                        $products->menus()->attach($categories);
                    }
                }
            }

            if($request->additional_facilities)
            {
                foreach ($request->additional_facilities as $key => $value)
                {
                    $categories = Menu::whereIn('id',[$value['id']])->get();
                    $menuables = Menuable::where([['menu_id',$value['id']],['menuable_type','App\Models\Product'],['menuable_id',$request->id]])->get();

                    if (count($menuables) > 0) {


                    }
                    else
                    {
                        $products->menus()->attach($categories);
                    }

                }
            }

            if($request->browser_compatibility)
            {
                foreach ($request->browser_compatibility as $key => $value)
                {
                    $categories = Menu::whereIn('id',[$value['id']])->get();
                    $menuables = Menuable::where([['menu_id',$value['id']],['menuable_type','App\Models\Product'],['menuable_id',$request->id]])->get();

                    if (count($menuables) > 0) {


                    }
                    else
                    {
                        $products->menus()->attach($categories);
                    }
                }
            }

            if($request->test)
            {
                foreach ($request->test as $key => $value)
                {
                    $categories = Menu::whereIn('id',[$value['id']])->get();
                    $menuables = Menuable::where([['menu_id',$value['id']],['menuable_type','App\Models\Product'],['menuable_id',$request->id]])->get();

                    if (count($menuables) > 0) {


                    }
                    else
                    {
                     $products->menus()->attach($categories);
                    }
                }
            }

            if($request->sub_test)
            {
                foreach ($request->sub_test as $key => $value)
                {
                    $categories = $value ? Menu::whereIn('id',[$value['id']])->get() : null;
                    $menuables = $value ? Menuable::where([['menu_id',$value['id']],['menuable_type','App\Models\Product'],['menuable_id',$request->id]])->get() : null;

                    if ($menuables && count($menuables) > 0) {

                    }
                    else if($value)
                    {
                        $products->menus()->attach($categories);
                    }
                }
            }

            $files = FileCreate::all($request,$products,Product::class);

            $images = ImageCreate::all($request,$products,Product::class);
            $companies = $company->first();
            $pro = $product->find($request->id);

            ProductJob::dispatch($pro)->delay(now()->addMinute($companies->job));
            $request->session()->flash(
                'alert' ,[
                    'title'=>'محصول!',
                    'text'=> 'باموفقیت بروز رسانی شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'محصول!',
                    'text'=> 'بروز رسانی نشد،مشکلی پیش آمده با پشتیبانی تماس حاصل فرمایید.',
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
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Product $product,User $user,Company $company,Route $route,$id)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $products = $product->with('file')->with('image')->with('group')->with('type')->with('category')->with('menus')->find($id);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(productAdmin.edit)')->first() && $route->where('name','route(productAdmin.edit)')->first()->descriptions?
            $route->where('name','route(productAdmin.edit)')->first()->descriptions->first():null;
        $menus = $route->where('name','route(productAdmin.edit)')->first() && $route->where('name','route(productAdmin.edit)')->first()->menus?
            $route->where('name','route(productAdmin.edit)')->first()->menus:null;
        $wallet = Wallet::all($users);
        // dd($products);
        return Inertia::render('Users/Admin/Product/Product-edit',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'product'=> $products,'users' => $users,'menus' => $menus,
        'alert'=>$alert,'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,
        'path'=> 'route(productAdmin.edit)','wallet'=>$wallet]);
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
