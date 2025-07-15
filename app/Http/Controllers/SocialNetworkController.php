<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Description;
use Illuminate\Http\Request;
use App\Http\Utilities\Ostan;
use App\Http\Utilities\Shahr;

class SocialNetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,File $file,Company $company,
    Description $description)
    {

        $users = $user->with('image')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $ostans = Ostan::all();
        $shahrs = Shahr::all();
        $companies = $user->with('image')->first();
        $descriptions = $description->where('route',$request->path())->first();
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();

        return Inertia::render('Users/Buyer/Profile/socialNetwork-index',['users'=> $users,'ostans'=>$ostans,'shahrs'=>$shahrs,
        'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions]);

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
    public function show($id)
    {
        //
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
