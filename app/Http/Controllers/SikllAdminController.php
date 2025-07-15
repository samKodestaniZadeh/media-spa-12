<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Sikll;
use App\Jobs\SikllJob;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;

class SikllAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Company $company,Route $route)
    {
        return abort(404);
        // $users = $user->with('siklls')->with('profile')->with('roles')->find(auth()->user()->id);
        // $notifications = $users->unreadnotifications;
        // $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        // $companies = $user->with('image')->first();
        // $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
        //     $route->where('name',$request->path())->first()->descriptions->first():null;
        // $id=$request->query();
        // $users->notifications->find($id)->MarkAsRead();
        // // dd($users,$alert);
        // $wallet = Wallet::all($users);

        // return Inertia::render('Users/Admin/Profile/sikll-index',['users'=> $users,'notifications'=> $notifications,
        // 'companies'=>$companies,'descriptions'=>$descriptions,'alert'=>$alert,'wallet'=>$wallet]);

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
    public function store(Request $request,Sikll $sikll)
    {
        // dd($request->siklls);
        if ($request->siklls && $request->siklls[0])
        {
            foreach ($request->siklls as $key => $value)
            {
                if($value['subject'] !== $sikll->find($value['id'])->subject ||
                $value['number'] !== $sikll->find($value['id'])->number ||
                $value['status'] !== $sikll->find($value['id'])->status)
                {
                    $siklls = $sikll->find($value['id'])->update([
                        'subject'=> $value['subject'],
                        'number'=> $value['number'],
                        'status'=> $value['status'],
                    ]);

                    SikllJob::dispatch($sikll->find($value['id']))->delay(now()->addMinute(1));
                }

            }

            if($siklls)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'مهارت!',
                        'text'=> 'باموفقیت بروز رسانی شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );
                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'مهارت!',
                        'text'=> 'بروز رسانی نشد، مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
                return redirect()->back();
            }

        }
        else {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'مهارت!',
                    'text'=> 'بروز رسانی نشد، مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Company $company,Route $route,$id)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $user = $user->with('image')->with('siklls')->with('profile')->with('roles')->find($id);
        $users = $user->with('siklls')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Admin/Profile/sikll-show',['users'=> $users,'notifications'=> $notifications,
            'companies'=>$companies,'descriptions'=>$descriptions,'alert'=>$alert,'user' => $user,'wallet'=>$wallet,'cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance]]);

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
