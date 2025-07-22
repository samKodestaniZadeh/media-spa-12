<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Product;
use App\Models\Support;
use App\Models\Favorite;
use App\Models\Description;
use App\Models\Tarahi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Favorite $favorite,User $user,Company $company,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $products = $favorite->where('user_id',auth()->user()->id)->with('favoritable')->paginate(9);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $names = $request->session()->has('name') ? $request->session()->get('name'):null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
        $route->where('name',$request->path())->first()->descriptions->first():null; // $description->where('route','favorite')->first();

        if ($users && $users->deposits)
        {
            $userDeposit = 0;
            foreach ($users->deposits as $key => $value) {

                $userDeposit = $userDeposit + round($value->price) ;
            }
        }
        else
        {
            $userDeposit=null;
        }

        if ($users && $users->financials)
        {
            $userFinancial = 0 ;
            foreach ($users->financials as $key => $value) {

                $userFinancial = $userFinancial + round($value->price) ;
            }
        }
        else
        {
            $userFinancial=null;
        }

        $userPrice = $userDeposit - $userFinancial;
        return Inertia::render('Users/Buyer/Favorite/favorite-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'products'=>$products,'statuses'=> $statuses,'names'=>$names,
        'users'=> $users,'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'alert'=> $alert
        ,'userPrice'=>$userPrice]);

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
    public function store(Request $request,Favorite $favorite)
    {
        // dd($request);
        $request->validate([
            'id' => 'required',
            'type' => 'required'
        ]);


        if($request->type == 'App\Models\Product')
        {
            $searches = $favorite->where('user_id',auth()->user()->id)->where('favoritable_type',$request->type)
            ->where('favoritable_id',$request->id)->first();

            if($searches)
            {
                $favorite->find($searches->id)->delete();

                $request->session()->flash(
                    'alert' ,[
                        // 'title'=> '!',
                        'text'=> 'از لیست علاقه مندی حذف شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );
                // dd($request->session()->get('_previous')['url']);
                return redirect()->back();
            }
            else
            {
                $favorite->create([
                    'favoritable_type'=> Product::class,
                    'favoritable_id'=> $request->id,
                    'user_id' => auth()->user()->id
                ]);
                $request->session()->flash(
                    'alert' ,[
                        // 'title'=> '!',
                        'text'=> 'به لیست علاقه مندی افزوده شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );
                // dd($request->session()->get('_previous')['url']);
                return redirect()->back();
            }
        }
        else if ($request->type == 'App\Models\Tarahi')
        {
            $searches = $favorite->where('user_id',auth()->user()->id)->where('favoritable_type',$request->type)
            ->where('favoritable_id',$request->id)->first();
            if($searches)
            {
                $favorite->find($searches->id)->delete();

                $request->session()->flash(
                    'alert' ,[
                        // 'title'=> '!',
                        'text'=> 'از لیست علاقه مندی حذف شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );
                // dd($request->session()->get('_previous')['url']);

            }
            else
            {
                $favorite->create([
                    'favoritable_type'=> Tarahi::class,
                    'favoritable_id'=> $request->id,
                    'user_id' => auth()->user()->id
                ]);
                $request->session()->flash(
                    'alert' ,[
                        // 'title'=> '!',
                        'text'=> 'به لیست علاقه مندی افزوده شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );
                // dd($request->session()->get('_previous')['url']);

            }
            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function show(Favorite $favorite)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function edit(Favorite $favorite)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Favorite $favorite)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Favorite $favorite)
    {
        return abort(404);
    }

}
