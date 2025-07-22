<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Image;
use App\Models\Route;
use App\Models\Company;
use App\Models\Session;
use App\Models\WebDesign;
use App\Jobs\WebDesignJob;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Http\Utilities\ImageCreate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class WebDesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Route $route,Company $company,User $user,Role $role,WebDesign $webDesign)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $time = new Carbon;
        $sessions = Session::updateCurrent();
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus?
            $route->where('name',$request->path())->first()->menus:null;
        $companies = $user->with('image')->first();
        $webDesigns = $webDesign->with('image')->with('type')->orderBy('created_at','desc')->paginate(9)->WithQueryString();
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Web/web-index',['menus' => $menus,'users'=> $users,
                'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance], 'path'=>$request->path(),'time'=>$time,
                'alert'=> $alert,'companies'=>$companies,'querystring' =>$request->getQueryString() == 'q=all'? '':$request->getQueryString(),
                'webDesigns' => $webDesigns,'wallet'=>$wallet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,WebDesign $webDesign,User $user,Company $company,Route $route)
    {
        Gate::authorize('create',$webDesign);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $time = new Carbon;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus?
            $route->where('name',$request->path())->first()->menus:null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Web/web-create',['alert' => $alert,
        'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
        'users' => $users,'notifications'=> $notifications,'menus'=>$menus,'time'=>$time,
        'companies'=>$companies,'descriptions'=>$descriptions, 'path'=>$request->path(),'wallet'=>$wallet
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Role $role,User $user,WebDesign $webDesign,Company $company)
    {
        Gate::authorize('create',$webDesign);
        $companies = $company->first();
        if($request->id)
        {
            $webDesigns = $webDesign->find($request->id)->update([
                'slug'=>$request->group['name'].'_'. $request->type['name'] .'_'.$request->category['name'] .'_'. $request->name,
                'name'=> $request->name,
                'name_en'=> $request->name_en,
                'text'=> $request->text,
                'price'=>$request->price,
                'user_id' => auth()->user()->id,
                'group'=>$request->group['id'],
                'type'=> $request->type['id'],
                'category'=> $request->category['id'],
                'status' =>  $request->status,
            ]);

            if($webDesigns)
            {
                $webDesigns = $webDesign->find($request->id);
                $categories = Menu::whereIn('id', [$request->group['id'], $request->type['id'], $request->category['id']])->get();
                $webDesigns->menus()->sync($categories);

                WebDesignJob::dispatch($webDesigns)->delay(now()->addMinute($companies->job));

                $images = $request->file('image')?$request->file('image')->store('images'):null;

                if($images && $webDesigns->image && $images !== $webDesigns->image->url)
                {
                    Storage::delete($webDesigns->image->url);
                    $webDesigns->image()->update([
                        'user_id' => auth()->user()->id,
                        'url'=>  $images,
                        'imageable_type'=>WebDesign::class,
                        'imageable_id'=> $webDesigns->id,
                        'status'=> 4,
                    ]);
                }


                $request->session()->flash(
                    'alert' ,[
                        'title'=>'وب سایت!',
                        'text'=> 'با موفقیت ثبت شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'وب سایت!',
                        'text'=> 'ثبت نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
        }
        else
        {

            $request->validate([
                'name' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'price' => 'required|numeric',
                'text'=> 'required|string|max:65535',
                'group'=> 'required|max:255',
                'type'=> 'required|max:255',
                'category'=> 'required|max:255',
                // 'price' => 'numeric',
            ]);

            if(

                $webDesign->where('slug',$request->group['name'] .'_'. $request->type['name'] .'_'. $request->category['name'] .'_'. $request->name)->pluck('slug')->first() ==
                $request->group['name'].'_'. $request->type['name'].'_'.$request->category['name'].'_'. $request->title
            )
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'وب سایت!',
                        'text'=> 'بااین مشخصات قبلا ثبت شده است و امکان ثبت مجدد وجود ندارد ، عنوان دیگری برای وب سایت خود انتخاب کنید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
                return redirect()->back();
            }
            else
            {


                $webDesigns = $webDesign->create([
                    'slug'=>$request->group['name'].'_'. $request->type['name'] .'_'.$request->category['name'] .'_'. $request->name,
                    'name'=> $request->name,
                    'name_en'=> $request->name_en,
                    'text'=> $request->text,
                    'price'=>$request->price,
                    'user_id' => auth()->user()->id,
                    'group'=>$request->group['id'],
                    'type'=> $request->type['id'],
                    'category'=> $request->category['id'],
                ]);

                if($webDesigns)
                {

                    $categories = Menu::whereIn('id', [$request->group['id'], $request->type['id'], $request->category['id']])->get();
                    $webDesigns->menus()->attach($categories);

                    WebDesignJob::dispatch($webDesigns)->delay(now()->addMinute($companies->job));

                    $images = ImageCreate::all($request,$webDesigns,WebDesign::class);

                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'وب سایت!',
                            'text'=> 'با موفقیت ثبت شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );

                    return redirect()->back();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'وب سایت!',
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
     * @param  \App\Models\WebDesign  $webDesign
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Company $company,Route $route,WebDesign $webDesign,$ids)
    {
        Gate::authorize('create',$webDesign);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $time = new Carbon;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(web_design.show)')->first() && $route->where('name','route(web_design.show)')->first()->descriptions?
            $route->where('name','route(web_design.show)')->first()->descriptions->first():null;
        $menus = $route->where('name','route(web_design.show)')->first() && $route->where('name','route(web_design.show)')->first()->menus?
            $route->where('name','route(web_design.show)')->first()->menus:null;
        $webDesigns = $webDesign->with('image')->with('group')->with('type')->with('category')->find($ids);
        $wallet = Wallet::all($users);
        // dd($webDesigns);
        return Inertia::render('Users/Admin/Web/web-show',['alert' => $alert,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],
            'users' => $users,'notifications'=> $notifications,'menus'=>$menus,'time'=>$time,'wallet'=>$wallet,
            'companies'=>$companies,'descriptions'=>$descriptions, 'path'=>'route(web_design.show)','webDesign' => $webDesigns
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebDesign  $webDesign
     * @return \Illuminate\Http\Response
     */
    public function edit(WebDesign $webDesign)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WebDesign  $webDesign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WebDesign $webDesign)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WebDesign  $webDesign
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebDesign $webDesign)
    {
        return abort(404);
    }
}
