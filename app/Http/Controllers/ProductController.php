<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Cart;
use App\Models\File;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Image;
use App\Models\Order;
use App\Models\Route;
use App\Models\Company;
use App\Models\Product;
use App\Models\section;
use App\Jobs\ProductJob;
use App\Models\Discount;
use App\Models\Menuable;
use App\Models\Orderable;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Models\ChangeProduct;
use App\Http\Utilities\Wallet;
use App\Http\Utilities\FileCreate;
use App\Http\Utilities\ImageCreate;
use Illuminate\Support\Facades\Gate;
use App\Http\Utilities\ProductCreate;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ProductNotification;
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Product $product,Order $order,User $user,Company $company,
    Route $route,Menu $menu,section $section)
    {

        Gate::authorize('viewAny', $product);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $times = Carbon::now();
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $orders = $order->paginate(9);
        $products = $product->withCount('views')->withCount('orders')->with('image')->with('type')->where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(9);
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

        if($time !== 'All' && $status == 'All' &&  $subject == 'All')
        {

            $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],
                ['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();

                return Inertia::render('Users/Seller/Product/product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet]);
        }
        elseif($status !== 'All' && $time == 'All' &&  $subject == 'All')
        {
                $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where('status',$status)->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();


                return Inertia::render('Users/Seller/Product/product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet]);

        }
        elseif($subject !== 'All' && $time == 'All' && $status == 'All')
        {

                $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where('type',$subject)
                ->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();

                return Inertia::render('Users/Seller/Product/product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet]);

        }
        else
        {
            if($subject !== 'All' && $status !== 'All' && $time !== 'All')
            {
                $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where('type',$subject)->where('status',$status)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();

                return Inertia::render('Users/Seller/Product/product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet]);
            }
            else if($subject !== 'All' && $status !== 'All')
            {
                $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where('type',$subject)
                ->where('status',$status)->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();

                return Inertia::render('Users/Seller/Product/product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet]);
            }
            else if($subject !== 'All' && $time !== 'All')
            {
                $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where('type',$subject)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();

                return Inertia::render('Users/Seller/Product/product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet]);
            }
            else if($status !== 'All' && $time !== 'All')
            {
                $products = $product->with('group')->with('type')->withCount('orders')->with('image')->where('status',$status)->where([
                ['created_at','>',$times->createFromFormat('Y-m-d H:i:s',$time.'00:00:00')],['created_at','<',$times->createFromFormat('Y-m-d H:i:s',$time.'23:59:60')]
                ])->where('user_id',auth()->user()->id)->paginate(9)->withQueryString();

                return Inertia::render('Users/Seller/Product/product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet]);
            }
            else
            {
                return Inertia::render('Users/Seller/Product/product-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'alert' => $alert,
                'users' => $users,'products'=> $products,'companies' => $companies,'descriptions'=>$descriptions,
                'menus' => $menus,'subjects'=> $subject,'times'=> $time,'statuses'=> $status,'wallet'=>$wallet]);
            }


        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,Product $product,Discount $discount,User $user,Order $order,
    Company $company,Menu $menu,Route $route)
    {
        Gate::authorize('viewAny', $product);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->findOrfail(auth()->user()->id);
        $orders = $order->where('user_id',auth()->user()->id)->with('productimage')->paginate(9);
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus?
         $route->where('name',$request->path())->first()->menus:null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);
        // dd($request->path());
        return Inertia::render('Users/Seller/Product/product-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'menus' => $menus,'alert' => $alert,
        'users' => $users,'companies' => $companies,'descriptions'=>$descriptions,'children'=>null,'path'=>$request->path()
        ,'wallet'=>$wallet
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product,Company $company,ChangeProduct $changeproduct)
    {
        // dd($request);
        Gate::authorize('viewAny', $product);
        if($request->update)
        {
            $pro = $product->with('image')->find($request->update);

            $request->validate([
                'text'=> 'required|min:100',
                'version'=> 'required',
                'file'=> 'required',
            ]);

            $products = $product->find($request->update)->update([

                'version'=>$request->version,
                'status' => 0,
            ]);

            if($products)
            {
                $changeproduct->create([
                    'product_id'=>$pro->id,
                    'version'=>$request->version,
                    'text'=> $request->text,
                ]);

                $files = $request->file('file')?$request->file('file')->store('files'):null;

                if($files && $pro->file && $files !== $pro->file->url)
                {
                    Storage::delete( $pro->file);
                    $pro->file()->update([
                        'user_id' => auth()->user()->id,
                        'url'=>  $files,
                        'fileable_type'=>Product::class,
                        'fileable_id'=> $pro->id,
                        'status'=> 1,
                    ]);
                }

                $pro = $product->find($request->update);
                $companies = $company->first();
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
                        'text'=> 'بروز رسانی نشد، مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
                return redirect()->back();
            }
        }
        if($request->id)
        {
            $pro = $product->with('image')->find($request->id);

            $products = ProductCreate::all($request);

            if($products)
            {
                $products = $product->find($request->id);
                $categories = Menu::whereIn('id', [$request->group['id'], $request->type['id'], $request->category['id']])->get();
                $products->menus()->sync($categories);


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
                        $categories = Menu::whereIn('id',[$value['id']])->get();
                        $menuables = Menuable::where([['menu_id',$value['id']],['menuable_type','App\Models\Product'],['menuable_id',$request->id]])->get();

                        if (count($menuables) > 0) {


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
                        $categories = Menu::whereIn('id',[$value['id']])->get();
                        $menuables = Menuable::where([['menu_id',$value['id']],['menuable_type','App\Models\Product'],['menuable_id',$request->id]])->get();

                        if (count($menuables) > 0) {

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
                        'text'=> 'باموفقیت ویرایش شد.',
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
                        'text'=> 'ویرایش نشد.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
                return redirect()->back();
            }
        }
        else
        {


            if(
                $product->pluck('name')->first() == $request->name
                ||
                $product->pluck('name_en')->first() == $request->name_en
            )
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'محصول!',
                        'text'=> 'بااین مشخصات قبلا ثبت شده است و امکان ثبت مجدد وجود ندارد.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
            }
            else
            {

                $products = ProductCreate::all($request);

                if($products)
                {
                    $categories = Menu::whereIn('id', [$request->group['id'], $request->type['id'], $request->category['id']])->get();
                    $products->menus()->attach($categories);

                    if($request->prerequisites)
                    {
                        foreach ($request->prerequisites as $key => $value)
                        {
                            $categories = Menu::whereIn('id',[$value['id']])->get();

                            $products->menus()->attach($categories);
                        }

                    }

                    if($request->prerequisite_version)
                    {
                        foreach ($request->prerequisite_version as $key => $value)
                        {
                            if($value)
                            {
                                $categories = Menu::whereIn('id',[$value['id']])->get();

                                $products->menus()->attach($categories);
                            }
                        }
                    }

                    if($request->browser_compatibility)
                    {
                        foreach ($request->browser_compatibility as $key => $value) {
                            $categories = Menu::find($value['id']);
                            $products->menus()->attach($categories);
                        }
                    }

                    if($request->additional_facilities)
                    {
                        foreach ($request->additional_facilities as $key => $value)
                        {
                            $categories = Menu::find($value['id']);
                            $products->menus()->attach($categories);
                        }
                    }

                    if($request->test)
                    {
                        foreach ($request->test as $key => $value)
                        {
                            $categories = Menu::whereIn('id',[$value['id']])->get();
                            $products->menus()->attach($categories);
                        }
                    }

                    if($request->sub_test)
                    {
                        foreach ($request->sub_test as $key => $value)
                        {
                            if($value)
                            {
                                $categories = Menu::whereIn('id',[$value['id']])->get();

                                $products->menus()->attach($categories);
                            }
                        }
                    }

                    $files = FileCreate::all($request,$products,Product::class);

                    $images = ImageCreate::all($request,$products,Product::class);
                    $companies = $company->first();

                    ProductJob::dispatch($products)->delay(now()->addMinute($companies->job));
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'محصول!',
                            'text'=> 'باموفقیت بارگزاری شد.',
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
                            'text'=> 'بارگزاری نشد با پشتیبانی تماس حاصل فرمایید.',
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Orderable $orderable,User $user,Company $company,Route $route,$id)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $asidemini = $request->query->has('asidemini') ? $request->query->get('asidemini') :null;
        $dark = $request->query->has('dark') ? $request->query->get('dark') :null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;
        $orders = $orderable->where([['user_id',auth()->user()->id],['orderable_type','App\Models\Product'],['orderable_id',$id]])
            ->with('user')->with('product')->orderBy('created_at','desc')->paginate(9)->WithQueryString();
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
        $route->where('name',$request->path())->first()->descriptions->first():null;// $description->where('route',$request->path())->first();
        // dd($request->path());
        $products = $orderable->where('id',0)->orderBy('created_at','desc')->paginate(9);
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Seller/Product/product-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,
        'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
        'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'ids'=> $ids,
        'statuses'=> $statuses,'prices'=>$prices,'users' => $users,'notifications'=> $notifications,
        'companies'=>$companies,'descriptions'=>$descriptions,'products'=> $products,'dark'=>$dark,'asidemini'=>$asidemini,'path'=>$request->path(),
        'wallet'=>$wallet
        ]);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Product $product,Discount $discount,User $user,Order $order,
    Company $company,Route $route,Menu $menu)
    {
        Gate::authorize('viewAny', $product);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $products = $product->with('file')->with('image')->with('group')->with('type')->with('category')->with('menus')->findOrfail($product->id);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(product.edit)')->first() && $route->where('name','route(product.edit)')->first()->descriptions?
            $route->where('name','route(product.edit)')->first()->descriptions->first():null;
        $menus = $route->where('name','route(product.edit)')->first() && $route->where('name','route(product.edit)')->first()->menus?
            $route->where('name','route(product.edit)')->first()->menus:null;
        // dd($menus);
        // $children = $menu->where('parent_id',$value->id)->with('children')->get();
        if( $products && $products->user_id == auth()->user()->id)
        {
            $wallet = Wallet::all($users);
            return Inertia::render('Users/Seller/Product/product-edit',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'product'=> $products,
            'users' => $users,'companies' => $companies,'descriptions'=>$descriptions,
            'menus' => $menus,'children'=>null,'path'=> 'route(product.edit)','alert' => $alert,
            'wallet'=>$wallet]);
        }
        else
        {
            return abort(404);
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Product $product,User $user,Company $company,Route $route)
    {
        Gate::authorize('viewAny', $product);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $products = $product->with('file')->with('image')->with('menus')->findOrfail($product->id);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(product.update)')->first() && $route->where('name','route(product.update)')->first()->descriptions?
            $route->where('name','route(product.update)')->first()->descriptions->first():null; // $description->where('route',$request->path())->first();
        $menus = $route->where('name','route(product.update)')->first() && $route->where('name','route(product.update)')->first()->menus?
            $route->where('name','route(product.update)')->first()->menus:null;// $menu->where('route', 'users/product/create')->where('parent_id',null)->with('children')->get();
        // dd($menus);
        // $children = $menu->where('parent_id',$value->id)->with('children')->get();
        if( $products && $products->user_id == auth()->user()->id)
        {
            $wallet = Wallet::all($users);

            return Inertia::render('Users/Seller/Product/product-update',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'product'=> $products,
            'users' => $users,'companies' => $companies,'descriptions'=>$descriptions,
            'menus' => $menus,'children'=>null,'path'=> 'route(product.edit)','alert' => $alert,
            'wallet'=>$wallet
            ]);
        }
        else
        {
            return abort(404);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return abort(404);
    }

}
