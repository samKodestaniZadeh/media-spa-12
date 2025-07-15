<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Route;
use App\Models\Company;
use App\Models\Product;
use App\Models\Discount;
use App\Models\Description;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ProductNotification;
use Illuminate\Support\Facades\Notification;

class ProductModirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Product $product,Order $order,User $user,Company $company,
    Route $route)
    {
        Gate::authorize('update', $product);
        $names = $request->session()->has('names') ? $request->session()->get('names'):null;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $orders = $order->where('user_id',auth()->user()->id)->with('productimage')->paginate(9);
        $products = $product->with('user')->where('id','>',0)->orderBy('created_at','desc')->paginate(9);
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
        $route->where('name',$request->path())->first()->descriptions->first():null;//$description->where('route',$request->path())->first();

        return Inertia::render('Users/Modir/Product/Product-index',['orders'=> $orders,'users' => $users,
        'products'=> $products,'names'=> $names,'ids'=> $ids,'statuses'=> $statuses,'notifications'=> $notifications,
        'companies' => $companies,'descriptions'=>$descriptions]);
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
    public function store(Request $request,Product $product,File $file,User $user)
    {
        Gate::authorize('update', $product);
        $pro = $product->with('image')->with('file')->find($request->id);

        $request->validate([
            'name'=> 'required',
            'name_en'=> 'required',
            'text'=> 'required|min:100',
            'demo_link'=> 'required',
            'version'=> 'required',
            'file'=> 'required',
            'image'=> 'required',
        ]);

        $products = $product->find($request->id)->update([
            'name'=> $request->name,
            'name_en'=>$request->name_en,
            'text'=> $request->text,
            'demo_link'=>$request->demo_link,
            'price'=>$request->price,
            'version'=>$request->version,
            'status'=> $request->status,
            'user_id' => $request->user_id,
        ]);

        if($products)
        {
            $files = $request->file('file')?$request->file('file')->store('files'):null;

            if($files && $pro->file && $files !== $pro->file->url)
            {
                Storage::delete( $pro->file);
                $pro->file()->update([
                    'url'=>  $files,
                    'fileable_type'=>Product::class,
                    'fileable_id'=> $pro->id,
                    'status'=> $request->status,
                ]);
            }
            else
            {
                $pro->file()->update([
                    'status'=> $request->status,
                ]);
            }

            $images = $request->file('image')?$request->file('image')->store('files'):null;

            if($images && $pro->image && $images !== $pro->image->url)
            {
                Storage::delete($pro->image->url);
                $pro->image()->update([
                    'url'=>  $images,
                    'imageable_type'=>Product::class,
                    'imageable_id'=> $pro->id,
                    'status'=> $request->status,
                ]);
            }
            else
            {
                $pro->image()->update([
                    'status'=> $request->status,
                ]);
            }


            if($request->status == 4)
            {
                $products = $product->find($request->id);
                $massage = 'محصول شما توسط کارشناس تایید و در صفحه محصولات نمایش داده شد.';
                $route = 'product.index';
                $user = $user->findOrfail($request->user_id);
                Notification::send($user , new ProductNotification($products,$massage,$route,$user));

            }
            else if($request->status == 3)
            {
                $products = $product->find($request->id);
                $massage = 'محصول شما توسط کارشناس رد شد.';
                $route = 'product.index';
                $user = $user->findOrfail($request->user_id);
                Notification::send($user , new ProductNotification($products,$massage,$route,$user));
            }
            else if($request->status == 2)
            {
                $products = $product->find($request->id);
                $massage = 'محصول شما در حال بررسی توسط کارشناس قرار گرفت.';
                $route = 'product.index';
                $user = $user->findOrfail($request->user_id);
                Notification::send($user , new ProductNotification($products,$massage,$route,$user));
            }
            else if($request->status == 1)
            {
                $products = $product->find($request->id);
                $massage = 'محصول شما در انتظار بررسی توسط کارشناس قرار گرفت.';
                $route = 'product.index';
                $user = $user->findOrfail($request->user_id);
                Notification::send($user , new ProductNotification($products,$massage,$route,$user));
            }
            $request->session()->flash(
                'alert' ,[
                    'title'=>'!محصول',
                    'text'=> '.باموفقیت بروز رسانی شد',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->route('dashboard.index');
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'!محصول',
                    'text'=> '.بروز رسانی نشد،مشکلی پیش آمده با پشتیبانی تماس حاصل فرمایید',
                    'icon'=> 'success',
                    'button' => 'ok']
            );

            return redirect()->route('dashboard.index');
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
    public function edit(Request $request,Product $product,Discount $discount,User $user,Order $order,
    Company $company,Route $route,$productmodir)
    {
        Gate::authorize('update', $product);

        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $users = $user->with('file')->with('profile')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $products = $product->with('file')->with('image')->find($productmodir);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(productModir.edit)')->first() && $route->where('name','route(productModir.edit)')->first()->descriptions?
        $route->where('name','route(productModir.edit)')->first()->descriptions->first():null;// $description->where('route',$request->path())->first();

        return Inertia::render('Users/Modir/Product/Product-edit',['product'=> $products,'users' => $users,
        'flash'=>$flash,'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
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

    public function search(Product $product ,Request $request)
    {
        Gate::authorize('update', $product);

        $name = $request->query->get('name');
        $statu = $request->query->get('status');
        $id = $request->query->get('id');

       if($name == null && $statu == null && $id ==null )
       {
            $request->validate([
                'id'=> 'required',
                'name'=> 'required',
                'status' => 'required',
            ]);
        }
        elseif($id)
        {

            $ids = $product->where('id',$id)->paginate(9)->withQueryString();

            return back()->with('ids',$ids);
        }
        elseif($statu > -1)
        {

                $statuses = $product->where('status',$statu)->paginate(9)->withQueryString();

                return back()->with('statuses',$statuses);
        }
        elseif($name)
        {
            $names = $product->where('name',$name)->paginate(9)->withQueryString();

            return back()->with('names',$names);
        }

    }

}
