<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\File;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Tarahi;
use App\Models\Comment;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestTarahiCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Tarahi $tarahi,User $user,Comment $comment,File $file,
    Company $company,$id)
    {

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $tarahis =  $tarahi->with('comments')->with('user')->
        with('favorite')->where('slug',$id)->first();

        $tarahi_count = $tarahis->views()->count();
        $tarahi_order = '';//$tarahis->orders->count()
        $time = new Carbon;
        views($tarahis)->cooldown($time->addDays(1))->record();

        $user_id = $tarahis->user->id;
        $count = $tarahi->where('user_id',$user_id)->get()->count();

        $comments = $comment->with('user')->where('commentable_id',$tarahis->id)->where('commentable_type',Tarahi::class)->where('parent_id',null)->
            where('status',4)->get();

        $replies = $comment->with('user')->where('commentable_id',$tarahis->id)->where('commentable_type',Tarahi::class)->where('parent_id','>',0)->
            where('status',4)->with('user')->get();

        $companies = $user->with('image')->first();

        if(Auth::check())
        {
            $id=$request->query();
            $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
            $users->notifications->find($id)->MarkAsRead();
        }

        return Inertia::render('Guest/guest-tarahi-comment-show',['tarahi'=>$tarahis,'count'=>$count,
        'cartCount'=> $cart->count ,'time'=> $time,'comments' => $comments,'replies'=>$replies,
        'alert'=>$alert,'flash'=>$flash,'tarahi_count'=>$tarahi_count, 'tarahi_order'=> $tarahi_order
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
