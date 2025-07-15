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
use App\Models\Route;
use App\Models\Tarahi;
use App\Jobs\TarahiJob;
use App\Models\Company;
use App\Models\Product;
use App\Models\WebDesign;
use App\Jobs\WebDesignJob;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Http\Utilities\TarahiCreate;
use Illuminate\Support\Facades\Gate;
use App\Notifications\TarahiNotification;
use App\Notifications\ProductNotification;
use Illuminate\Support\Facades\Notification;

class TarahiAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Tarahi $tarahi,User $user,Company $company,Route $route)
    {
        Gate::authorize('create',$tarahi);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $names = $request->session()->has('names') ? $request->session()->get('names'):null;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $tarahis = $tarahi->with('user')->with('company')->with('reqDesigner')->where('id','>',0)->orderBy('created_at','desc')->paginate(9);
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);
        // dd($tarahis);
        return Inertia::render('Users/Admin/Tarahi/Tarahi-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'tarahis'=> $tarahis,'users' => $users,
        'names'=> $names,'ids'=> $ids,'statuses'=> $statuses,'notifications'=> $notifications,
        'companies'=>$companies,'descriptions'=>$descriptions,'wallet'=>$wallet]);
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
    public function store(Request $request,Tarahi $tarahi,Role $role,User $user,WebDesign $webDesign,Company $company)
    {
        Gate::authorize('create',$tarahi);
        $companies = $company->first();

        if($request->id)
        {

            // dd($request);
            $tarahis = TarahiCreate::updateAdmin($request);
            // dd($tarahis);
            if($tarahi->find($request->id)->file)
            {
                $tarahi->find($request->id)->file->update([
                    'status'=>$request->status,
                ]);
            }

            if($tarahis)
            {
                $tarahis = $tarahi->find($request->id);

                $message = ' پروژه شما در وضعیت انتشار قرار گرفت.';

                TarahiJob::dispatch($tarahis,$message)->delay(now()->addMinute($companies->job));

                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پروژه!',
                        'text'=> 'با موفقیت بروز رسانی شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );

            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'پروژه!',
                        'text'=> 'بروز رسانی نشد،مشکلی پیش آمده، مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

            }

        }
        else
        {


        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Tarahi $tarahi,Company $company,Route $route,$id)
    {
        Gate::authorize('create', $tarahi);

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->notifications;
        $tarahis = $tarahi->with('user')->with('registerDesigner')->with('company')->with('file')->with('menus')->with('group')->with('type')->with('category')->with('userCanceller')->find($id);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(tarahiAdmin.show)')->first() && $route->where('name','route(tarahiAdmin.show)')->first()->descriptions?
            $route->where('name','route(tarahiAdmin.show)')->first()->descriptions->first():null;
        $wallet = Wallet::all($users);
        // dd($tarahis);
        return Inertia::render('Users/Admin/Tarahi/Tarahi-show',['tarahis'=> $tarahis,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
        'alert'=>$alert,'flash'=>$flash,'users'=>$users,'notifications'=>$notifications,'companies'=>$companies,
        'descriptions'=>$descriptions,'wallet'=>$wallet]);
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
