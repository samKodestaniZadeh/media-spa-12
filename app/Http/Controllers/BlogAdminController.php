<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Blog;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Image;
use App\Models\Route;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BlogAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Blog $blog,Company $company,Route $route,Page $page)
    {
        Gate::authorize('create', $blog);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $time = new Carbon;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $blogs = $blog->with('user')->with('menus')->orderBy('created_at','desc')->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $wallet = Wallet::all($users);

        return  $blogs ? Inertia::render('Users/Admin/Public/Blog-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'blogs'=>$blogs,'alert' => $alert,'users'=>$users,
            'companies' => $companies,'descriptions'=>$descriptions,'wallet'=>$wallet]) : abort(404);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Blog $blog,Company $company,Route $route)
    {
        Gate::authorize('create', $blog);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus?
            $route->where('name',$request->path())->first()->menus:null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/Blog-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'users'=>$users,'companies' => $companies,'alert' => $alert,
        'descriptions'=>$descriptions,'menus'=>$menus,'path'=>$request->path(),'wallet'=>$wallet]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Blog $blog,Image $image)
    {
        Gate::authorize('create', $blog);
        // dd($request);
        $request->validate([
            'title_en'=>'required|max:255',
            'title' => 'required|max:255',
            'text'=>'required',
        ]);

        if ($request->id)
        {
            $blogs = $blog->find($request->id)->update([
                'user_id'=>auth()->user()->id,
                'title_en'=>$request->title_en,
                'slug'=>$request->title,
                'title' => $request->title,
                'text'=>$request->text,
                'group' => $request->group['id'],
                'type' => $request->type['id'],
            ]);

            if($blogs)
            {
                $categories = Menu::whereIn('id', [$request->group['id'], $request->type['id']])->get();
                $blog->find($request->id)->menus()->sync($categories);

                $blogs = $blog->with('image')->find($request->id);

                $images = $request->file('image')?$request->file('image')->store('images'):null;

                if($images && $blogs->image && $images !== $blogs->image->url)
                {
                    Storage::delete($blogs->image->url);
                    $blogs->image()->update([
                        'user_id' => auth()->user()->id,
                        'url'=>  $images,
                        'imageable_type'=>Blog::class,
                        'imageable_id'=> $blogs->id,
                        'status'=> 4,
                    ]);
                }
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'مقاله!',
                        'text'=> 'با موفقیت ویرایش شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'مقاله!',
                        'text'=> 'ویرایش نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }

        } else
        {
            if(
                $blog->pluck('title')->first() == $request->title
                ||
                $blog->pluck('title_en')->first() == $request->title_en
            )
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'مقاله!',
                        'text'=> 'بااین مشخصات قبلا ثبت شده است و امکان ثبت مجدد وجود ندارد.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
            }
            else
            {
                    $blogs = $blog->create([
                        'user_id'=>auth()->user()->id,
                        'title_en'=>$request->title_en,
                        'slug'=>$request->title,
                        'title' => $request->title,
                        'text'=>$request->text,
                        'group' => $request->group['id'],
                        'type' => $request->type['id'],
                    ]);

                    if($blogs)
                    {
                        $categories = Menu::whereIn('id', [$request->group['id'], $request->type['id']])->get();
                        $blogs->menus()->attach($categories);

                        $images = $request->file('image')?$request->file('image')->store('images'):null;
                        if($images)
                        {
                            $image->create([
                                'user_id' => auth()->user()->id,
                                'url'=>  $images,
                                'imageable_type'=>Blog::class,
                                'imageable_id'=> $blogs->id,
                                'status'=> 4,
                            ]);
                        }
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'مقاله!',
                                'text'=> 'با موفقیت ایجاد شد.',
                                'icon'=> 'success',
                                'button' => 'ok']
                        );

                        return redirect()->back();
                    }
                    else
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'مقاله!',
                                'text'=> 'ایجاد نشد، مشکلی پیش آمده مجدد تلاش نمایید.',
                                'icon'=> 'error',
                                'button' => 'ok']
                        );

                        return redirect()->back();
                    }
            }
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,$id,Blog $blog,Company $company,Route $route)
    {
        Gate::authorize('create', $blog);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $blogs = $blog->with('comments')->with('favorite')->with('image')->withCount('comments')->find($id);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(blogAdmin.show)')->first() && $route->where('name','route(blogAdmin.show)')->first()->descriptions?
            $route->where('name','route(blogAdmin.show)')->first()->descriptions->first():null;
        $menus = $route->where('name','route(blogAdmin.show)')->first() && $route->where('name','route(blogAdmin.show)')->first()->menus?
            $route->where('name','route(blogAdmin.show)')->first()->menus:null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Public/Blog-show',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'users'=>$users,'companies' => $companies,'alert' => $alert,
        'descriptions'=>$descriptions,'menus'=>$menus,'blog'=>$blogs ,'path'=>'route(blogAdmin.show)','wallet'=>$wallet]);
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
    public function destroy(Request $request,User $user,Blog $blog,Role $role,$id)
    {
        Gate::authorize('create', $blog);

        if($blog->status !== 3)
        {
                $blog = $blog->find($id)->update([
                    'status'=>3
                ]);
                if($blog)
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'مقاله!',
                            'text'=> 'لغو شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );

                    return redirect()->back();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'مقاله!',
                            'text'=> 'لغو نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );

                    return redirect()->back();
                }
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'مقاله!',
                    'text'=> ' قبلا لغو شده است.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
    }
}
