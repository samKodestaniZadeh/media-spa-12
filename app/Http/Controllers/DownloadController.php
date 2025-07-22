<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\File;
use App\Models\Link;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Image;
use App\Models\Order;
use App\Models\Route;
use App\Models\Company;
use App\Models\Product;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $filename = $request->session()->has('filename')? $request->session()->get('filename'):null;
        $newfilename = $request->session()->has('newfilename')? $request->session()->get('newfilename'):null;

        return Storage::disk('public')->download($filename,$newfilename);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product,URL $url)
    {

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);

        $time = new Carbon;

        $products = $product->find($request->id);
        // dd($products);
        $filename =  $products->file->url;
        $filename2 = pathinfo($products->file->url);
        $dirname = $filename2['dirname'];
        $name = $filename2['filename'];
        $ext = $filename2['extension'];
        $basename = $filename2['basename'];
        $newfilename = md5($name).'.'.$ext;

        $time=$request->session()->flash(
            'time' , $time,
        );

        $newfilenames=$request->session()->flash(
           'newfilename', $newfilename,
        );
        $filenames=$request->session()->flash(
           'filename' , $filename,
        );
        $id=$request->session()->flash(
            'id' , $request->id,
         );
         $expires=$request->query->get('expires');
         $signature=$request->query->get('signature');

        return redirect()->temporarySignedRoute('download.create' ,now()->addMinutes(5),['user' => auth()->user()->id,
        'newfilename'=>$newfilenames]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request ,Order $order,User $user,Link $link,
    Company $company,Route $route,$id)
    {
        Gate::allows('viewAny', $order->findOrfail($id));
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $dark = $request->session()->has('class') ? $request->session()->get('class') :null;
        // $orderable->with('orderable')->with('links')->where('order_id',$id)->get();
        $orders = $order->with('subOrder')->find($id);
        $time = new Carbon();

        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $link = $request->session()->get('link');
        $id = $request->session()->get('id');
        $newfilename = $request->session()->get('newfilename');
        $filename = $request->session()->get('filename');
        $users = $user->with('file')->with('profile')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $token = $request->session()->token();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(download.show)')->first() && $route->where('name','route(download.show)')->first()->descriptions?
        $route->where('name','route(download.show)')->first()->descriptions->first():null;// $description->where('route',$request->path())->first();
        $wallet = Wallet::all($users);
        // dd($orders);
        return Inertia::render('Users/Buyer/Download/download-show',['time'=>$time,'link'=> $link,'id'=>$id,
        'newfilename'=>$newfilename,'filename'=>$filename,'orders'=> $orders,'token'=>$token,'dark'=>$dark,
        'users' => $users,'notifications'=> $notifications,'alert'=>$alert,'companies'=>$companies,
        'descriptions'=>$descriptions,'wallet'=>$wallet,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {

        $url = File::find($id);
        // dd($request,$id,$url);
        return Storage::download($url->url)?Storage::download($url->url):false;
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
