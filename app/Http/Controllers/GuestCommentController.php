<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\File;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestCommentController extends Controller
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
    public function show(Request $request,Product $product,User $user,Comment $comment,File $file,Company $company,$id)
    {
        // dd($request,$id);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $products =  $product->with('image')->with('discount')->with('user')->with('favorite')->where('slug',$id)->first();
        // dd($products);
        $product_count = $products->views()->count();
        $product_order = $products->orders->count();
        $time = new Carbon;
        views($products)->cooldown($time->addDays(1))->record();

        $user_id = $products->user->id;
        $count = $product->where('user_id',$user_id)->get()->count();

        $comments = $comment->with('user')->where('commentable_id',$products->id)
        ->where('commentable_type',Product::class)->where('parent_id',null)->where('status',4)->get();
        // dd($products);
        $replies = $comment->with('user')->where('commentable_id',$products->id)
        ->where('commentable_type',Product::class)->where('parent_id','>',0)->where('status',4)->get();
        $companies = $user->with('image')->first();

        if(Auth::check())
        {
            $id=$request->query();
            $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
            $users->notifications->find($id)->MarkAsRead();
        }

        return Inertia::render('Guest/guest-comment-show',['product'=>$products,'count'=>$count,
        'cartCount'=> $cart->count ,'time'=> $time,'comments' => $comments,'replies'=>$replies,
        'alert'=>$alert,'flash'=>$flash,'product_count'=>$product_count, 'product_order'=> $product_order
        ,'companies'=>$companies]);
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
