<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\File;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Company;
use App\Models\Identity;
use App\Jobs\IdentityJob;
use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Auth;
=======
use App\Http\Utilities\Ostan;
use App\Http\Utilities\Shahr;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
use App\Jobs\IdentityJob as JobsIdentityJob;

class IdentityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Company $company,Route $route)
    {
        if(Auth::check())
        {
            $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
            $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
            $cart = new Cart($oldCart);
<<<<<<< HEAD
            $time = new Carbon();
            $users = $user->with('image')->with('identity')->with('profile')->with('roles')->findOrfail(auth()->user()->id);
=======
            $ostans = Ostan::all();
            $shahrs = Shahr::all();
            $time = new Carbon();
            $users = $user->with('image')->with('file')->with('identity')->with('profile')->with('roles')->findOrfail(auth()->user()->id);
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
            $id=$request->query();
            $users->notifications->find($id)->MarkAsRead();
            $notifications = $users->unreadnotifications;
            $companies = $user->with('image')->first();
            $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
                $route->where('name',$request->path())->first()->descriptions->first():null;
            $wallet = Wallet::all($users);

            return Inertia::render('Users/Buyer/Profile/Identityuser-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'products' => $cart->products,'tarahis' => $cart->tarahis,'users' => $users,
<<<<<<< HEAD
                'notifications'=> $notifications,'cartTax' => $cart->tax,'orders_count'=>$user->orders->count(),
=======
                'notifications'=> $notifications,'cartTax' => $cart->tax,'orders_count'=>$user->orders->count(),'ostans'=>$ostans,'shahrs'=>$shahrs,
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
                'cartComplications'=> $cart->complications,'companies' => $companies,'cartComison' => $cart->comison,
                'descriptions'=>$descriptions,'alert' => $alert,'now'=>$time,'wallet'=>$wallet
            ]);
        }
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
<<<<<<< HEAD
    public function store(Request $request,Identity $identity,User $user , File $file)
    {


        if ($identity->find($request->identity)->status == 0 || $identity->find($request->identity)->status == 3)
        {
                if($request->national_cole)
=======
    public function store(Request $request,Identity $identity,User $user , File $file,Company $company)
    {

        $companies = $company->first();
        if ($identity->find($request->identity)->status == 0 || $identity->find($request->identity)->status == 3)
        {
            // dd($request->national_code);
                if($request->national_code)
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
                {

                    $request->validate([
                        'gender' => 'required',
                        'birth' => 'required',
                        'file' => 'required',
                        'identity' => 'required',
                        'id' => 'required',
                        'name' => 'required|string|max:255',
                        'lasst_name' => 'required|string|max:255',
                        'national_code' => 'required',
<<<<<<< HEAD
=======

>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
                    ]);

                }
                else
                {

                    $request->validate([
                        'birth' => 'required',
                        'file' => 'required|file',
                        'identity' => 'required',
                        'id' => 'required',
                        'name' => 'required|string|max:255',
                        'lasst_name' => 'required|string|max:255',
                        'national_id' => 'required',
                        'economical_number' => 'required',
                    ]);
                }

                $identitys = $identity->find($request->identity)->update([
                    'user_id' => auth()->user()->id,
                    'national_code' => $request->national_code,
                    'national_id' => $request->national_id,
                    'economical_number' => $request->economical_number,
<<<<<<< HEAD
=======
                    'status' => 0,
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
                ]);

                if ($identitys)
                {
<<<<<<< HEAD
                    IdentityJob::dispatch($identity->find($request->identity))->delay(now()->addMinute(5));
=======
                    IdentityJob::dispatch($identity->find($request->identity))->delay(now()->addMinute($companies->job));
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b

                    $user->find(auth()->user()->id)->update([
                        'birth' => $request->birth,
                        'name' => $request->name,
                        'lasst_name' => $request->lasst_name,
                    ]);

<<<<<<< HEAD
                    $user->find(auth()->user()->id)->prifile()->update([
                        'birth' => $request->birth,
                        'gender' => $request->gender,
                    ]);

                    $files = $request->file('file')?$request->file('file')->store('files'):null;

                    $file->create([
                        'user_id' => auth()->user()->id,
                        'url'=>  $files,
                        'fileable_type'=>Identity::class,
                        'fileable_id'=> $request->identity,
                    ]);
=======
                    $user->find(auth()->user()->id)->profile()->update([
                        'birth' => $request->birth,
                        'gender' => $request->gender,
                        'ostan'=>$request->ostan,
                        'shahr'=>$request->shahr,
                        'address'=>$request->address,
                    ]);


                                        // حذف فایل قبلی
                    $existingFile = $file
                                        ->where('fileable_type', Identity::class)
                                        ->where('fileable_id', $request->identity)
                                        ->first();
// dd(Storage::disk('public')->exists($existingFile->url), $existingFile->url);
                    if ($existingFile) {
                        // حذف از حافظه
                        if (Storage::disk('public')->exists($existingFile->url)) {
                            Storage::disk('public')->delete($existingFile->url);
                        }

                        // حذف از دیتابیس
                        $existingFile->delete();
                    }


                    $files = $request->file('file')?$request->file('file')->store('files'):null;

                    if ($files) {
                            $file->create([
                                'user_id' => auth()->user()->id,
                                'url' => $files,
                                'fileable_type' => Identity::class,
                                'fileable_id' => $request->identity,
                            ]);
                        }

>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b

                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'اطلاعات هویتی!',
                            'text'=> 'باموفقیت بارگزاری شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );

                    return redirect()->back();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'اطلاعات هویتی!',
                            'text'=> 'بارگزاری نشد ، مجدد تلاش نمایید.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );

                    return redirect()->back();
                }
        } else if($identity->find($request->identity)->status == 1)
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'اطلاعات هویتی!',
                    'text'=> 'در وضعیت انتظار قرار دارد ، امکان تغیرات مقدور نمی باشد.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'اطلاعات هویتی!',
                    'text'=> 'بار گزاری نشد ، امکان تغیرات مقدور نمی باشد.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Identity  $identity
     * @return \Illuminate\Http\Response
     */
    public function show(Identity $identity)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Identity  $identity
     * @return \Illuminate\Http\Response
     */
    public function edit(Identity $identity)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Identity  $identity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Identity $identity)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Identity  $identity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Identity $identity)
    {
        return abort(404);
    }
}
