<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Link;
use App\Models\User;
use Inertia\Inertia;
use App\Jobs\LinkJob;
use App\Models\Route;
use App\Models\Social;
use App\Jobs\SocialJob;
use App\Models\Company;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Social $social,Company $company,Route $route)
    {
        // Gate::authorize('create',$social);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $socials = $social->with('link')->where('status',4)->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()?
            $route->where('name',$request->path())->first()->menus:null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Buyer/Profile/social-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'alert'=>$alert,'users' => $users,'socials'=> $socials,
            'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,'menus'=>$menus,
            'path'=>$request->path(),'wallet'=>$wallet]);
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
    public function store(Request $request,Social $social,Link $link)
    {
        // dd($request);
        if($request->socials)
        {
            foreach ($request->socials as $key => $value)
            {

                if($value['link'] && $value['id'])
                {

                    $request->validate([
                        'link' => 'url',
                    ]);

                    $links = $link->find($value['id'])->update([
                        'user_id' => auth()->user()->id,
                        'link' => $value['link'],
                        'linkable_type' => Social::class,
                        'linkable_id' => $value['social_id'],
                    ]);

                    if ($link->find($value['id'])->link !== $value['link'])
                    {
                        LinkJob::dispatch($link->find($value['id']))->delay(now()->addMinute(5));
                    }

                }
                else if($value['link'] && $value['id'] == null)
                {
                    $request->validate([
                        'link' => 'url',
                    ]);

                    $links = $link->create([
                        'user_id' => auth()->user()->id,
                        // 'name'=>$social->find($value['social_id'])->name,
                        // 'tag'=>$social->find($value['social_id'])->tag,
                        'link' => $value['link'],
                        'linkable_type' => Social::class,
                        'linkable_id' => $value['social_id'],
                    ]);
                    LinkJob::dispatch($links)->delay(now()->addMinute(5));
                }

            }

            if($links && $links->id)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'شبکه اجتماعی!',
                        'text'=> 'با موفقیت ثبت شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );
            }
            else if($links == true)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'شبکه اجتماعی!',
                        'text'=> 'با موفقیت بروز رسانی شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'شبکه اجتماعی!',
                        'text'=> 'ثبت نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
            }
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function show(Social $social)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function edit(Social $social)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Social $social)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function destroy(Social $social,Request $request)
    {
        return abort(404);
    }
}
