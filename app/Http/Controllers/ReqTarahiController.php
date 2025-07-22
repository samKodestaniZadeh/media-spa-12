<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Tarahi;
use App\Models\Company;
use App\Jobs\DepositJob;
use App\Models\Financial;
use App\Models\Description;
use App\Models\ReqDesigner;
use Illuminate\Http\Request;
use App\Http\Utilities\Wallet;
use Illuminate\Support\Facades\Gate;
use App\Http\Utilities\DepositCreate;
use App\Notifications\PaymentNotification;
use Illuminate\Support\Facades\Notification;

class ReqTarahiController extends Controller
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
    public function show(ReqDesigner $reqDesigner,Request $request,Tarahi $tarahi,User $user,
    Company $company,Route $route,$id)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $names = $request->session()->has('names') ? $request->session()->get('names'):null;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $users = $user->with('image')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $tarahis = $tarahi->with('registerDesigner')->find($id);
        $reqDesigners = $reqDesigner->with('user')->with('tarahiRegister')->with('file')->where('status','>',1)
        ->where('tarahi_id',$tarahis->id)->orderBy('created_at','desc')->paginate(9);
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(reqTarahi.show)')->first() && $route->where('name','route(reqTarahi.show)')->first()->descriptions?
            $route->where('name','route(reqTarahi.show)')->first()->descriptions->first():null;
        $token = $request->session()->token();
        $wallet = Wallet::all($users);
        // dd($tarahis,$reqDesigners);
        return Inertia::render('Users/Buyer/Tarahi/Req-Tarahi-show',['tarahi'=> $tarahis,
        'users' => $users,'names'=> $names,'ids'=> $ids,'statuses'=> $statuses,'token'=>$token,
        'notifications'=> $notifications,'companies'=>$companies,'descriptions'=>$descriptions,
        'reqDesigners'=>$reqDesigners,'alert'=> $alert,'wallet'=>$wallet]);
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
    public function destroy(Request $request,Tarahi $tarahi,User $user,$id,Company $company)
    {
        $tarahi = $tarahi->findOrfail($id);
        Gate::authorize('viewAny', $tarahi);
        $time = new Carbon;
        $companies = $company->first();
        // dd($tarahi);
        if($tarahi->id)
        {
            // dd($tarahi->regdesigner->where('id',$tarahi->reqdesigner_id)->first() && $tarahi->regdesigner->where('id',$tarahi->reqdesigner_id)->first()->id == $tarahi->reqdesigner_id && $tarahi->price_block);
            if( $tarahi->reqDesigner->where('id',$tarahi->reqdesigner_id)->first() && $tarahi->reqDesigner->where('id',$tarahi->reqdesigner_id)->first()->id == $tarahi->reqdesigner_id && $tarahi->price_block)
            {

                $price_block = $tarahi->price_block;
                $users = $tarahi->reqDesigner->where('id',$tarahi->reqdesigner_id)->first()->user_id;
                $tarahis = $tarahi->update([
                    'reqdesigner_id'=>null,
                    'total'=> null,
                    'price_block'=> null,
                    'status'=> 4
                ]);

                if($tarahis)
                {
                    $users = $user->find($users);
                    $notification = $users->unreadNotifications;
                    $users->notifications->find($tarahi->notification_id)->MarkAsRead();

                    $deposets = DepositCreate::create(auth()->user()->id, 'واریز بابت لغو واگذاری پروژه '.$tarahi->title,$price_block,0,0,$time,4,Tarahi::class,$tarahi->id,0,0,0,null,null);

                    if($deposets )
                    {

                        $message = 'افزایش موجودی کیف پول بابت لغو واگذاری پروژه.';

                        DepositJob::dispatch($deposets,$message)->delay(now()->addMinute($companies->job));
                    }


                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'درخواست!',
                            'text'=> 'با موفقیت انجام شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );

                    return redirect()->back();
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'درخواست!',
                            'text'=> 'لغو نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );

                    return redirect()->back();
                }
            }
            else
            {
                 $request->session()->flash(
                        'alert' ,[
                            'title'=>'پروژه!',
                            'text'=> 'لغو نشد،طراح پروژه را پذیرفته است.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );

                    return redirect()->back();

            }

        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'درخواست!',
                    'text'=> 'لغو نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
    }
}
