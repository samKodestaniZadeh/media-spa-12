<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Coupon;
use App\Models\Tarahi;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Product;
use App\Models\Session;
use App\Models\Discount;
use App\Models\Favorite;
use App\Models\Orderable;
use Illuminate\Http\Request;
use willvincent\Rateable\Rating;
use Illuminate\Support\Facades\Auth;

class WebsiteTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Product $product,User $user,Coupon $coupon,Company $company,Route $route ,Orderable $orderable,
    Comment $comment,Tarahi $tarahi,Discount $discount,Role $role,Favorite $favorite)
    {
        // $time = Carbon::now('+3:30');
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $results = null;
        $search =  $request->query->get('q');
        $type = $request->query->get('type');
        $category = $request->query->get('category');
        $sort = $request->query->get('sort');
        $updated = $request->query->get('updated');
        $time = new Carbon;
        $time2 = new Carbon;
        $sessions = Session::updateCurrent();
        $coupon_count = $coupon->where('user_id',null)->count();
        $companies = $user->with('image')->first();
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus ?
            $route->where('name',$request->path())->first()->menus:null;
        $menu = Menu::where('parent_id',null)->where('status',4)->with('children','sections','routes')->get();
        $discounts = $discount->where('expired','>',$time)->where('discountable_type' , 'App\\Models\\Product')->with('discountable')
            ->get();
        $orders = $orderable->with('product')->where('orderable_type','App\Models\Product')->whereYear('created_at', '=', Carbon::now()->year)
        ->whereMonth('created_at', '=', Carbon::now()->month)->select('orderable_id')
        ->selectRaw('count(`orderable_id`) as `occurences`')->groupBy('orderable_id')->having('occurences', '>', 0)->limit(3)->get();

        $usersOrders = $orderable->with('user')->where('orderable_type','App\Models\Product')->whereYear('created_at', '=', Carbon::now()->year)
        ->whereMonth('created_at', '=', Carbon::now()->month)->select('user_id')
        ->selectRaw('count(`user_id`) as `occurences`')->groupBy('user_id')->having('occurences', '>', 0)->paginate(9)->WithQueryString();
        $resultsNew = $product->with('image')->withAvg('ratings', 'rating')->where('status', 4)->latest()->limit(3)->get();
        $favorites = $favorite->with('favoritable')
        ->where('favoritable_type', 'App\Models\Product')
        ->select('favoritable_id', 'favoritable_type') // اگه نخوای error بگیری
        ->selectRaw('COUNT(*) as total_favorites')
        ->groupBy('favoritable_id', 'favoritable_type') // باید هردو گروپ بشن
        ->orderByDesc('total_favorites')->limit(3)
        ->get();
                // گرفتن محصولات بر اساس میانگین امتیاز
        $rated = Rating::select('rateable_id')
        ->where('rateable_type', 'App\Models\Product') // فقط محصولات
        ->selectRaw('AVG(rating) as avg_rating') // میانگین امتیاز
        ->groupBy('rateable_id')
        ->orderByDesc('avg_rating') // مرتب‌سازی بر اساس میانگین امتیاز
        ->limit(3)
        ->get();

        // گرفتن محصولات با استفاده از آیدی‌های `rateable_id` و مرتب‌سازی بر اساس امتیاز
        $productIds = $rated->pluck('rateable_id'); // آیدی‌های محصولات

        $topRated = Product::with('image')->with('discount')->whereIn('id', $productIds) // گرفتن محصولات با آیدی‌های بالا
        ->get()
        ->map(function ($product) use ($rated) {
            // پیدا کردن میانگین امتیاز برای هر محصول
            $product->avg_rating = $rated->firstWhere('rateable_id', $product->id)->avg_rating;
            return $product;
        })
        ->sortByDesc('avg_rating'); // مرتب‌سازی بر اساس میانگین امتیاز به صورت نزولی



        // dd($search);

        if($companies)
        {

            if(Auth::check())
            {
                $users = $user->with('image')->with('profile')->with('roles')->find(auth()->user()->id);
                $id=$request->query();
                $users->notifications->find($id)->MarkAsRead();

            }
            else
            {
                $users=null;
            }

            if($search == '' && $type == '' && $sort == ''&& $updated == '' && $category == '')
            {
                $request->validate([
                'search' => 'required',
                ]);
            }
            if($search == 'all')
            {
                $results = $product->with('discount')->with('image')->withAvg('ratings', 'rating')->with('file')->with('user')->where('status',4)->OrWhere('status',5)->with('menus')
                    ->withCount('orders')->withCount('comments')->paginate(9)->WithQueryString();

                return Inertia::render('Guest/website-templates-index',['results'=> $results,'cartCount'=> $cart->count,
                'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus ,
                'users_count'=>$user->all()->count(),'products_count'=>$product->all()->count(),'cartProduct'=>$cart->products,
                'comments_count'=>$comment->all()->count(),'tarahis_count'=>$tarahi->all()->count(),'cartTotal'=>$cart->total,
                'discounts'=>$discounts,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),
                'usersOrders'=> $usersOrders,'resultsNew' => $resultsNew,'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
                'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
            }

            else if($type)
            {
                $results = $product->with('discount')->with('image')->with('file')->with('user')->withAvg('ratings', 'rating')->with('menus')->orderBy('type','asc')
                ->where('type',$type)->withCount('orders')->withCount('comments')->where('status',4)->OrWhere('status',5)->paginate(9)->WithQueryString();
                // dd($results);
                return Inertia::render('Guest/website-templates-index',['results'=> $results,'cartCount'=> $cart->count,'discounts'=>$discounts,
                'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus,'orders'=>$orders
                ,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),'resultsNew' => $resultsNew,
                'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
                'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
            }
            else if($category)
            {
                $results = $product->with('discount')->with('image')->with('file')->with('user')->with('menus')->withAvg('ratings', 'rating')->orderBy('category','asc')
                ->where('category',$category)->withCount('orders')->withCount('comments')->where('status',4)->OrWhere('status',5)->paginate(9)->WithQueryString();
                // dd($results,$category);
                return Inertia::render('Guest/website-templates-index',['results'=> $results,'cartCount'=> $cart->count,'discounts'=>$discounts,
                'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus,'orders'=>$orders
                ,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),'resultsNew' => $resultsNew,
                'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
                'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
            }
            else if($sort == 'DESC')
            {
                $results = $product->with('discount')->with('image')->with('file')->with('user')->withAvg('ratings', 'rating')->orderBy('id','desc')
                ->withCount('orders')->withCount('comments')->where('status',4)->OrWhere('status',5)->paginate(9)->WithQueryString();

                return Inertia::render('Guest/website-templates-index',['results'=> $results,'cartCount'=> $cart->count,
                'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus,'discounts'=>$discounts,
                'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),'resultsNew' => $resultsNew
                ,'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
               'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
            }

            else if($sort == 'cheapest')
            {
                $results = $product->with('discount')->with('image')->with('file')->with('user')->withAvg('ratings', 'rating')->withCount('orders')
                ->withCount('comments')->orderBy('price','asc')->where('status',4)->OrWhere('status',5)->paginate(9)->WithQueryString();

                return Inertia::render('Guest/website-templates-index',['results'=> $results,'cartCount'=> $cart->count,
                'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus,'discounts'=>$discounts,
                'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),'resultsNew' => $resultsNew
                ,'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
                'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
            }

            else if($sort == 'expensive')
            {
                $results = $product->with('discount')->with('image')->with('file')->with('user')->withAvg('ratings', 'rating')->orderBy('price','desc')
                ->withCount('orders')->withCount('comments')->where('status',4)->OrWhere('status',5)->paginate(9)->WithQueryString();

                return Inertia::render('Guest/website-templates-index',['results'=> $results,'cartCount'=> $cart->count,
                'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus,'discounts'=>$discounts,
                'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),'resultsNew' => $resultsNew
                ,'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
                'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
            }

            else if($updated == 'updateDate')
            {
                $results = $product->with('discount')->with('image')->with('file')->with('user')->withAvg('ratings', 'rating')->orderBy('updated_at','desc')
                ->withCount('orders')->withCount('comments')->where('status',4)->OrWhere('status',5)->paginate(9)->WithQueryString();

                return Inertia::render('Guest/website-templates-index',['results'=> $results,'cartCount'=> $cart->count,
                'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus,'discounts'=>$discounts,
                'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString() ,'resultsNew' => $resultsNew
                ,'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
                 'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
            }

            else if($sort == 'Bestselling')
            {
                $results = $product->with('discount')->with('image')->with('file')->with('user')->withAvg('ratings', 'rating')->withCount('orders')
                ->withCount('comments')->orderBy('orders_count' ,'desc')->where('status',4)->OrWhere('status',5)->paginate(9)
                ->WithQueryString();
                    // dd($results);
                return Inertia::render('Guest/website-templates-index',['results'=> $results,'cartCount'=> $cart->count,
                'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus,'discounts'=>$discounts,
                'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),'resultsNew' => $resultsNew
                ,'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
                'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
            }
            else if($sort == 'Discount')
            {

                // $results = $product->with('discount')->with('image')->with('file')->with('user')->withAvg('ratings', 'rating')->where('status',4)->OrWhere('status',5)
                // ->withCount('orders')->withCount('comments')->paginate(9)->WithQueryString();
                // $results = $discount->where('expired','>',$time)->where('discountable_type' , 'App\\Models\\Product')->with('discountable')
                $results = $product->with(['discount', 'image', 'file', 'user'])
                    ->withAvg('ratings', 'rating')
                    ->whereIn('status', [4, 5])
                    ->whereHas('discount') // فقط محصولاتی که تخفیف دارند
                    ->withCount(['orders', 'comments'])
                    ->paginate(9)
                    ->withQueryString();

                // ->paginate(9)->WithQueryString();
                // dd($results);
                return Inertia::render('Guest/website-templates-index',['results'=> $results,'cartCount'=> $cart->count,
                'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus,'discounts'=>$discounts,
                'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),'resultsNew' => $resultsNew
                ,'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
                 'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
            }

            if($search !== '')
            {

                $searchResults = Product::search($search)->get();

                $results = Product::whereIn('id', $searchResults->pluck('id'))
                    ->with('discount', 'image', 'user')
                    ->withAvg('ratings', 'rating')
                    ->whereIn('status', ['4', '5'])
                    ->paginate(9)
                    ->withQueryString();
                // dd($results);
                if( $results->total() > 1)
                {

                    return Inertia::render('Guest/website-templates-index',['results'=> null,'cartCount'=> $cart->count,
                    'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus,'discounts'=>$discounts
                    ,'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),'resultsNew' => $resultsNew
                    ,'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
                     'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                    'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
                }
                elseif( $results->total() == 1)
                {
                    $results = $product->where('id',$results[0]->id)->with('discount')->with('image')->with('user')
                    ->withCount('orders')->withCount('comments')->with('file')->paginate(9)->WithQueryString();
                    // dd($results);
                    return Inertia::render('Guest/website-templates-index',['results'=> $results,'cartCount'=> $cart->count,
                    'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus,'discounts'=>$discounts,
                    'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),'resultsNew' => $resultsNew
                    ,'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
                        'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                        'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
                }
                else
                {
                    $results = null;
                    return Inertia::render('Guest/website-templates-index',['results'=> $results,'cartCount'=> $cart->count,
                    'alert'=>$alert,'time'=>$time,'coupon_count'=>$coupon_count,'companies'=>$companies,'menus'=>$menus,'discounts'=>$discounts,
                    'orders'=>$orders,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),'resultsNew' => $resultsNew
                    ,'favorites' => $favorites,'topRated' => $topRated,'menu' => $menu,
                    'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
                    'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
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
    public function create(Request $request,Product $product,Company $company,User $user)
    {
        // return abort(404);
        $products =  $product->with('image')->with('discount')->with('user')->with('favorite')->where('slug',$request->get('product'))->first();
        $companies = $user->with('image')->first();

        return Inertia::render('Guest/website-templates-create',['products'=>$products,'companies'=>$companies]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Product $product,$id,User $user,Company $company)
    {

        // Gate::allows('view',$product->findOrfail($id));
        // dd($request);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = auth()->user()?$user->with('image')->with('profile')->with('roles')->with('favorites')->findOrFail(auth()->user()->id):null;
        $products =  $product->with('image')->with('discount')->with('user')->with('favorite')->with('menus')->with('changes')
        ->withAvg('ratings', 'rating')->with('comments')->where('slug',$id)->first();
        $product_count = $products->views()->count();
        $product_order = $products->orders->count();
        $time = new Carbon;
        views($products)->cooldown($time->addDays(1))->record();
        $user_id = $products->user->id;
        $count = $product->where('user_id',$user_id)->get()->count();
        $companies = $user->with('image')->first();
        $carousels = $product->with('menus')->with('discount')->with('image')->with('file')->withCount('orders')
        ->withCount('comments')->orderBy('price','asc')->where('status',4)->where('id','<>',$products->id)->paginate(10)->WithQueryString();

        // dd($products);
        return Inertia::render('Guest/website-templates-show',['product'=>$products,'count' => $count,'users' => $users,
        'time'=>$time,'alert'=>$alert,'flash'=>$flash,'product_count'=>$product_count,
        'product_order'=> $product_order,'companies'=>$companies,'product_averageRating'=>$products->averageRating,
        'product_usersRated'=>$products->usersRated(),'carousels' => $carousels,'alert' => $alert,
        'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,
        'coupon' => $cart->coupon,'total' => $cart->total,'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
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
