<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Jobs\BankJob;
use App\Models\Image;
use App\Models\Route;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use App\Http\Utilities\BankCreate;
use App\Http\Utilities\ImageCreate;
use Illuminate\Support\Facades\Gate;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user,Bank $Bank,Company $company,Route $route)
    {
        // Gate::authorize('viewAny',$Bank);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $class = $request->session()->has('class') ? $request->session()->get('class') :null;
        $dark = $request->query->has('dark') ? $request->query->get('dark') :null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $banks = $Bank->with('image')->where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(9);
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
         $route->where('name',$request->path())->first()->descriptions->first():null;

         $wallet = Wallet::all($users);

        return Inertia::render('Users/Buyer/Bank/bank-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'banks' => $banks,'users'=> $users,'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,
            'alert'=>$alert,'dark'=>$dark,'wallet'=>$wallet
        ]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,User $user,Company $company,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $banknames = null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->menus ?
            $route->where('name',$request->path())->first()->menus:null;
        $wallet = Wallet::all($users);

        return Inertia::render('Users/Buyer/Bank/bank-create',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'users'=> $users,'banknames'=>$banknames,'alert'=>$alert
        ,'companies' => $companies,'descriptions'=>$descriptions,'menus' => $menus,'path'=> $request->path()
        ,'wallet'=>$wallet
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Bank $bank,Company $company)
    {
        // dd($request);
        $request->validate([
            'bankname'=> 'required','string',
            'accountname'=> 'required','string',
            'accountnumber'=> 'required','numeric',
            'cartnumber'=> 'required','numeric','min:16','max:16',
            'shabanumber'=> 'required','numeric','min:24','max:24',
            'image'=> 'required'
        ]);

        $companies = $company->first();
        $banks = BankCreate::all($request);
        $images = ImageCreate::all($request,$banks,Bank::class);

        if($banks && $images)
        {
            BankJob::dispatch($banks)->delay(now()->addMinute($companies->job));
            $request->session()->flash(
                'alert' ,[
                    'title'=>'حساب!',
                    'text'=> 'باموفقیت ایجاد شد.',
                    'icon'=> 'success',
                    'button' => 'ok']
                );
            return redirect()->back();
        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'حساب!',
                    'text'=> 'ایجاد نشد، مجدد تلاش نمایید.',
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
