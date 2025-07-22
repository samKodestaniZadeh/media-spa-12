<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Route;
use App\Models\Company;
use App\Models\Orderable;
use App\Models\Description;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderSellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Order $order,User $user,Company $company,Route $route,Orderable $orderable)
    {

        // Gate::authorize('viewAny', $order);
        $time = new Carbon();
        $time2 = new Carbon();
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        // $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        // $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        // $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;

        $orders = $orderable->with('order')->where('user_id',$users->id)->orderBy('created_at','desc')->paginate(9)->WithQueryString();
        if($request->date)
        {
            $farvardin = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date.'-03-21 00:00:00')->where('created_at','<=',$request->date.'-04-20 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $ordibehesht = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date.'-04-21 00:00:00')->where('created_at','<=',$request->date.'-05-21 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $khordad = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date.'-05-22 00:00:00')->where('created_at','<=',$request->date.'-06-21 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $tir = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date.'-06-22 00:00:00')->where('created_at','<=',$request->date.'-07-22 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $mordad = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date.'-07-23 00:00:00')->where('created_at','<=',$request->date.'-08-22 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $shahriver = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date.'-08-23 00:00:00')->where('created_at','<=',$request->date.'-09-22 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $mehr = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date.'-09-23 00:00:00')->where('created_at','<=',$request->date.'-10-22 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $aban = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date.'-10-23 00:00:00')->where('created_at','<=',$request->date.'-11-21 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $azar = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date.'-11-22 00:00:00')->where('created_at','<=',$request->date.'-12-21 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $dey = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date.'-12-22 00:00:00')->where('created_at','<=',$request->date2.'-01-20 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $bahman = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date2.'-01-21 00:00:00')->where('created_at','<=',$request->date2.'-02-19 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $esfand = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$request->date2.'-02-20 00:00:00')->where('created_at','<=',$request->date2.'-03-20 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();
        }
        else
        {
            $farvardin = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-03-21 00:00:00')->where('created_at','<=',$time2->year.'-04-20 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $ordibehesht = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-04-21 00:00:00')->where('created_at','<=',$time2->year.'-05-21 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $khordad = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-05-22 00:00:00')->where('created_at','<=',$time2->year.'-06-21 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $tir = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-06-22 00:00:00')->where('created_at','<=',$time2->year.'-07-22 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $mordad = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-07-23 00:00:00')->where('created_at','<=',$time2->year.'-08-22 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $shahriver = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-08-23 00:00:00')->where('created_at','<=',$time2->year.'-09-22 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $mehr = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-09-23 00:00:00')->where('created_at','<=',$time2->year.'-10-22 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $aban = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-10-23 00:00:00')->where('created_at','<=',$time2->year.'-11-21 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $azar = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-11-22 00:00:00')->where('created_at','<=',$time2->year.'-12-21 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $dey = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time2->subYear().'-12-22 00:00:00')->where('created_at','<=',$time->year.'-01-20 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $bahman = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time->year.'-01-21 00:00:00')->where('created_at','<=',$time->year.'-02-19 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $esfand = $orderable->with('order')->with('user')->where('user_id',$users->id)->where('id','>',0)
            ->where('created_at','>=',$time->year.'-02-20 00:00:00')->where('created_at','<=',$time->year.'-03-20 00:00:00')
            ->where('total','>',0)->orderBy('created_at','desc')->paginate(9)->WithQueryString();
        }

        $subject = $request->query->get('subject') == null?'All':$request->query->get('subject');
        $status = $request->query->get('status') == null? 'All' : $request->query->get('status');
        $times = $request->query->get('time') == null?'All':$request->query->get('time');
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
            $route->where('name',$request->path())->first()->descriptions->first():null;//$description->where('route',$request->path())->first();

        if($times !== 'All' &&  $subject == 'All')
        {

            $orders = $orderable->with('order')->where('user_id',$users->id)->where([
                    ['created_at','>',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],
                    ['created_at','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]
                    ])->orderBy('created_at','desc')->paginate(9)->WithQueryString();

                return Inertia::render('Users/Seller/Order/order-index',['orders'=> $orders,'subjects'=> $subject,'times'=> $times,
                'users' => $users,'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,
                'farvardin'=>$farvardin,'ordibehesht'=>$ordibehesht,'khordad'=>$khordad,'tir'=>$tir,'mordad'=>$mordad,'shahriver'=>$shahriver,
                'mehr'=>$mehr,'aban'=>$aban,'azar'=>$azar,'dey'=> $dey,'bahman'=>$bahman,'esfand'=>$esfand,'statuses'=> $status ]);
        }
        elseif($subject !== 'All' && $times == 'All')
        {


                $orders = $subject == 0 ? $orderable->with('order')->where('user_id',$users->id)->where('discount',$subject)->orderBy('created_at','desc')->paginate(9)->WithQueryString()
                :
                $orderable->with('order')->where('user_id',$users->id)->where('discount','>',$subject)->orderBy('created_at','desc')->paginate(9)->WithQueryString()
                ;


                return Inertia::render('Users/Seller/Order/order-index',['orders'=> $orders,'subjects'=> $subject,'times'=> $times,
                'users' => $users,'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,
                'farvardin'=>$farvardin,'ordibehesht'=>$ordibehesht,'khordad'=>$khordad,'tir'=>$tir,'mordad'=>$mordad,'shahriver'=>$shahriver,
                'mehr'=>$mehr,'aban'=>$aban,'azar'=>$azar,'dey'=> $dey,'bahman'=>$bahman,'esfand'=>$esfand,'statuses'=> $status ]);



        }
        else
        {

            if($subject !== 'All' && $times !== 'All')
            {


                $orders = $subject == 0 ? $orderable->with('order')->where('user_id',$users->id)->where('discount',$subject)->where([
                    ['created_at','>',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],['created_at','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]
                    ])->orderBy('created_at','desc')->paginate(9)->WithQueryString()
                :
                $orderable->with('order')->where('user_id',$users->id)->where('discount','>',$subject)->where([
                    ['created_at','>',$time->createFromFormat('Y-m-d H:i:s',$times.'00:00:00')],['created_at','<',$time->createFromFormat('Y-m-d H:i:s',$times.'23:59:60')]
                    ])->orderBy('created_at','desc')->paginate(9)->WithQueryString()
                ;


                return Inertia::render('Users/Seller/Order/order-index',['orders'=> $orders,'subjects'=> $subject,'times'=> $times,
                'users' => $users,'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,
                'farvardin'=>$farvardin,'ordibehesht'=>$ordibehesht,'khordad'=>$khordad,'tir'=>$tir,'mordad'=>$mordad,'shahriver'=>$shahriver,
                'mehr'=>$mehr,'aban'=>$aban,'azar'=>$azar,'dey'=> $dey,'bahman'=>$bahman,'esfand'=>$esfand,'statuses'=> $status ]);
            }
            else
            {
                return Inertia::render('Users/Seller/Order/order-index',['orders'=> $orders,'subjects'=> $subject,'times'=> $times,
                'users' => $users,'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,
                'farvardin'=>$farvardin,'ordibehesht'=>$ordibehesht,'khordad'=>$khordad,'tir'=>$tir,'mordad'=>$mordad,'shahriver'=>$shahriver,
                'mehr'=>$mehr,'aban'=>$aban,'azar'=>$azar,'dey'=> $dey,'bahman'=>$bahman,'esfand'=>$esfand,'statuses'=> $status ]);
            }


        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Order $order)
    {
        Gate::authorize('update', $order);
        return Excel::download(new OrdersExport, 'orders.xls');
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
    public function show(Request $request,Order $order,Orderable $orderable,User $user,Company $company,Description $description,
    $id)
    {
        Gate::authorize('update', $order);

        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;
        $orders = $orderable->with('orderable')->with('user')->where('order_id',$id)->paginate(9);
        $companies = $user->with('image')->first();
        $descriptions = $description->where('route',$request->path())->first();

        return Inertia::render('Users/Modir/Order/order-show',['orders'=> $orders,'ids'=> $ids,
        'statuses'=> $statuses,'prices'=>$prices,'users' => $users,'notifications'=> $notifications
        ,'companies' => $companies,'descriptions'=>$descriptions]);
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
