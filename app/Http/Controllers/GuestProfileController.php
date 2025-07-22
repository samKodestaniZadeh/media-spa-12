<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Tarahi;
use App\Models\Company;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Description;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestProfileController extends Controller
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
    public function show(Request $request,Profile $profile,User $user,Product $product,Tarahi $tarahi,
    Company $company,Role $role,$id)
    {
        if(Auth::check())
        {
            return redirect()->route('profile.show',$id);
        }
        else
        {

            $user = $user->with('siklls')->with('orders')->with('image')->with('profile')->with('socials')->where('user_name',$id)->first();
            // dd($user);
            $role = $user->roles->max();
            $products =  $product->with('image')->where('user_id',$id)->where('status',4)->paginate(9);
            $tarahis =  $tarahi->where('user_id',$id)->where('status',4)->paginate(9);
            $companies = $user->with('image')->first();
            // dd($user->roles);
            return Inertia::render('Guest/guest-Profile-show',['user'=> $user,'products'=> $products,
            'tarahis' => $tarahis,'orders_count'=> $user->orders->count(),'companies' => $companies,
            'role'=> $role]);
        }

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
