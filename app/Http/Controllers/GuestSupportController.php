<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Support;
use App\Jobs\SupportJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\SupportNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserRegisterNotification;

class GuestSupportController extends Controller
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
    public function store(Request $request, Support $support, Role $role, User $user)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'lasst_name' => 'required|string|max:255',
            'email'=> 'required|string|email|max:255|unique:users',
            'recepiant'=> 'required',
            'subject'=>'required',
            'text'=>'required|string|max:255',

        ]);

        $password =  substr( str_shuffle(md5(time())), 6, 20 );
        $users = User::create([
            'user_name' => $request->name . ' '. $request->lasst_name,
            'name' => $request->name,
            'lasst_name' => $request->lasst_name,
            'name_show' => $request->name . ' '. $request->lasst_name,
            'email' => $request->email,
            'tel'=> $request->tel,
            'password' => Hash::make($password),
            'status' => 0
        ]);

        $message = 'حساب کاربری شما با موفقیت ساخته شد.';
        $route = 'login';
        Notification::send($users , new UserRegisterNotification($users,$message,$route,$password));

        Profile::create([
            'user_id'=>$users->id,
            'notification'=> 1,
        ]);

        $supports = $support->create([
            'user_id' => $users->id,
            'recepiant' => $request->recepiant['id'],
            'subject' => $request->subject['id'],
            'text' => $request->text,
            'status' => 0,
        ]);

        if($supports)
        {


            SupportJob::dispatch($supports)->delay(now()->addMinute(5));

            $request->session()->flash(
                'alert' ,[
                    'title'=>'پیام!',
                    'text'=> 'باموفقیت ارسال شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
                );
            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'پیام!',
                    'text'=> 'ارسال نشد،مشکلی پیش آمده با پشتیبانی تماس تلفنی برقرار نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Product $product,User $user,Company $company,$id)
    {

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $flash = $request->session()->has('message')?$request->session()->get('message'):null;
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $products = $product->with('image')->with('user')->with('discount')->with('favorite')->where('slug',$id)->first();
        $userid = $products->user_id;
        $count = $product->where('user_id',$userid)->count();
        $product_count = $products->views()->count();
        $product_order = $products->orders->count();
        $time = new Carbon;
        views($products)->cooldown($time->addDays(1))->record();
        $companies = $user->with('image')->first();

        return Inertia::render('Guest/guest-support-show',['cartCount'=>$cart->count,'product'=>$products,'count'=>$count,
        'time'=>$time,'alert'=>$alert,'flash'=>$flash,'product_count'=>$product_count,'product_order'=> $product_order
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
