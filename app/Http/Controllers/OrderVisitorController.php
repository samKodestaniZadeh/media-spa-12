<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Company;
use App\Models\Product;
use App\Models\Orderable;
use App\Models\Description;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderVisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Order $order,Orderable $orderable,User $user,Company $company,
    Description $description,Product $product)
    {
        Gate::authorize('update', $order);
        $time = new Carbon();
        $time2 = new Carbon();
        $users = $user->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;

        $orders = $order->with('user')->with('productimage')->with('products')->where('id','>',0)
        ->orderBy('created_at','desc')->paginate(9)->WithQueryString();
        $order_counts = $order->where('id','>',0)->pluck('comison');
        $product_counts = $product->where('id','>',0)->where('status',4)->get()->count();
        $order_product = $orderable->where('id','>',0)->where('orderable_type',Product::class)->get();
        $order_tarahi = $orderable->where('id','>',0)->where('orderable_type',Tarahi::class)->get();
        // dd($order_product,$order_tarahi);
        if($request->date)
        {
            $farvardin = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date.'-03-21 00:00:00')->where('created_at','<=',$request->date.'-04-20 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $ordibehesht = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date.'-04-21 00:00:00')->where('created_at','<=',$request->date.'-05-21 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $khordad = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date.'-05-22 00:00:00')->where('created_at','<=',$request->date.'-06-21 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $tir = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date.'-06-22 00:00:00')->where('created_at','<=',$request->date.'-07-22 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $mordad = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date.'-07-23 00:00:00')->where('created_at','<=',$request->date.'-08-22 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $shahriver = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date.'-08-23 00:00:00')->where('created_at','<=',$request->date.'-09-22 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $mehr = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date.'-09-23 00:00:00')->where('created_at','<=',$request->date.'-10-22 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $aban = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date.'-10-23 00:00:00')->where('created_at','<=',$request->date.'-11-21 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $azar = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date.'-11-22 00:00:00')->where('created_at','<=',$request->date.'-12-21 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $dey = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date.'-12-22 00:00:00')->where('created_at','<=',$request->date2.'-01-20 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $bahman = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date2.'-01-21 00:00:00')->where('created_at','<=',$request->date2.'-02-19 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $esfand = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$request->date2.'-02-20 00:00:00')->where('created_at','<=',$request->date2.'-03-20 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();
        }
        else
        {
            $farvardin = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-03-21 00:00:00')->where('created_at','<=',$time2->year.'-04-20 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $ordibehesht = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-04-21 00:00:00')->where('created_at','<=',$time2->year.'-05-21 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $khordad = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-05-22 00:00:00')->where('created_at','<=',$time2->year.'-06-21 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $tir = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-06-22 00:00:00')->where('created_at','<=',$time2->year.'-07-22 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $mordad = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-07-23 00:00:00')->where('created_at','<=',$time2->year.'-08-22 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $shahriver = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-08-23 00:00:00')->where('created_at','<=',$time2->year.'-09-22 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $mehr = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-09-23 00:00:00')->where('created_at','<=',$time2->year.'-10-22 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $aban = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-10-23 00:00:00')->where('created_at','<=',$time2->year.'-11-21 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $azar = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time2->year.'-11-22 00:00:00')->where('created_at','<=',$time2->year.'-12-21 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $dey = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time2->subYear().'-12-22 00:00:00')->where('created_at','<=',$time->year.'-01-20 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $bahman = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time->year.'-01-21 00:00:00')->where('created_at','<=',$time->year.'-02-19 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();

            $esfand = $orderable->with('user')->where('id','>',0)
            ->where('created_at','>=',$time->year.'-02-20 00:00:00')->where('created_at','<=',$time->year.'-03-20 00:00:00')
            ->orderBy('created_at','desc')->paginate(9)->WithQueryString();
        }


        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $description->where('route',$request->path())->first();


        return Inertia::render('Users/Seller/Order/order-index',['orders'=> $orders,'ids'=> $ids,'statuses'=> $statuses,'prices'=>$prices,
        'users' => $users,'notifications'=> $notifications,'companies' => $companies,'descriptions'=>$descriptions,
        'farvardin'=>$farvardin,'ordibehesht'=>$ordibehesht,'khordad'=>$khordad,'tir'=>$tir,'mordad'=>$mordad,'shahriver'=>$shahriver,
        'mehr'=>$mehr,'aban'=>$aban,'azar'=>$azar,'dey'=> $dey,'bahman'=>$bahman,'esfand'=>$esfand , 'order_counts'=> $order_counts,
        'product_counts' => $product_counts,'order_product'=>$order_product, 'order_tarahi'=>$order_tarahi]);
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
