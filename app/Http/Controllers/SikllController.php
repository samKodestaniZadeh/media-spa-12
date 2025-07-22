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

class SikllController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Company $company,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $users = $user->with('siklls')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Buyer/Profile/sikll-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'users'=> $users,'notifications'=> $notifications,
        'companies'=>$companies,'descriptions'=>$descriptions,'alert'=>$alert,'wallet'=>$wallet
        ]);

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
        // dd($request->subject , $request->number , $request->siklls);
        if ($request->subject && $request->number && $request->siklls == null)
        {

            $request->validate([
                'number'=> 'required|max:100|min:1',
                'subject' => 'required|string',
            ]);

            $siklls = $sikll->create([
                'user_id'=> auth()->user()->id,
                'subject'=> $request->subject,
                'number'=> $request->number,
            ]);

            if($siklls)
            {
                SikllJob::dispatch($siklls)->delay(now()->addMinute(5));
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'مهارت!',
                        'text'=> 'باموفقیت ثبت شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );


            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'مهارت!',
                        'text'=> 'ثبت نشد، مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );


            }
            return redirect()->back();
        }
        else if ($request->subject && $request->number && $request->siklls[0])
        {

            foreach ($request->siklls as $key => $value)
            {
                if($value['subject'] !== $sikll->find($value['id'])->subject ||
                $value['number'] !== $sikll->find($value['id'])->number ||
                $value['status'] !== $sikll->find($value['id'])->status)
                {
                    $siklls = $sikll->find($value['id'])->update([
                        'user_id'=> auth()->user()->id,
                        'subject'=> $value['subject'],
                        'number'=> $value['number'],
                        'status'=> 0,
                    ]);

                    SikllJob::dispatch($sikll->find($value['id']))->delay(now()->addMinute(5));
                }
            }

            $request->validate([
                'number'=> 'required|max:100|min:1',
                'subject' => 'required|string',
            ]);

            $siklls = $sikll->create([
                'user_id'=> auth()->user()->id,
                'subject'=> $request->subject,
                'number'=> $request->number,
            ]);

            if($siklls)
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'مهارت!',
                            'text'=> 'باموفقیت بروز رسانی شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );

                    SikllJob::dispatch($siklls)->delay(now()->addMinute(5));

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


                }
                return redirect()->back();
        }
        else
        {
            foreach ($request->siklls as $key => $value)
            {

                if($value['subject'] !== $sikll->find($value['id'])->subject ||
                $value['number'] !== $sikll->find($value['id'])->number ||
                $value['status'] !== $sikll->find($value['id'])->status)
                {

                    $siklls = $sikll->find($value['id'])->update([
                        'user_id'=> auth()->user()->id,
                        'subject'=> $value['subject'],
                        'number'=> $value['number'],
                        'status'=> 0,
                    ]);

                    if($siklls)
                    {
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'مهارت!',
                                'text'=> 'باموفقیت بروز رسانی شد.',
                                'icon'=> 'success',
                                'button' => 'ok']
                        );

                        SikllJob::dispatch($sikll->find($value['id']))->delay(now()->addMinute(5));
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


                    }
                }
                // else if($value['subject'] == $sikll->find($value['id'])->subject &&
                // $value['number'] == $sikll->find($value['id'])->number &&
                // $value['status'] == $sikll->find($value['id'])->status)
                // {

                //     $siklls = $sikll->find($value['id'])->update([
                //         'user_id'=> auth()->user()->id,
                //         'subject'=> $value['subject'],
                //         'number'=> $value['number'],
                //     ]);

                //     if($siklls)
                //     {
                //         $request->session()->flash(
                //             'alert' ,[
                //                 'title'=>'مهارت!',
                //                 'text'=> 'باموفقیت بروز رسانی شد.',
                //                 'icon'=> 'success',
                //                 'button' => 'ok']
                //         );

                //         SikllJob::dispatch($sikll->find($value['id']))->delay(now()->addMinute(5));
                //     }
                //     else
                //     {
                //         $request->session()->flash(
                //             'alert' ,[
                //                 'title'=>'مهارت!',
                //                 'text'=> 'بروز رسانی نشد، مجدد تلاش نمایید.',
                //                 'icon'=> 'error',
                //                 'button' => 'ok']
                //         );


                //     }
                // }

            }

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sikll  $sikll
     * @return \Illuminate\Http\Response
     */
    public function show(Sikll $sikll)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sikll  $sikll
     * @return \Illuminate\Http\Response
     */
    public function edit(Sikll $sikll)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sikll  $sikll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sikll $sikll)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sikll  $sikll
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sikll $sikll,Request $request)
    {
        if ($sikll->user_id == auth()->user()->id) {
            if ($sikll->status !== 3)
            {

                $siklls = $sikll->destroy($sikll->id);

                if ($siklls)
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'مهارت!',
                            'text'=> 'باموفقیت حذف شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'مهارت!',
                            'text'=> 'حذف نشد، مجدد تلاش نمایید.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );
                }
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'مهارت!',
                        'text'=> 'در این وضعیت،قابل حذف شدن نیست لطفا مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
            }
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'مهارت!',
                    'text'=> 'حذف نشد، شما مجاز به حذف کردن نیستید لطفا مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );
        }
        return redirect()->back();
    }
}
