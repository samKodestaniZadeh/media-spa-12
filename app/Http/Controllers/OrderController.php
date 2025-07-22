<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Route;
use App\Jobs\OrderJob;
use App\Models\Coupon;
use App\Models\Tarahi;
use App\Jobs\TarahiJob;
use App\Models\Company;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\Product;
use App\Jobs\DepositJob;
use App\Jobs\PaymentJob;
use App\Jobs\DesignerJob;
use App\Models\Orderable;

use App\Jobs\OrderAdminJob;
use App\Jobs\OrderBayerJob;
use App\Jobs\OrderModirJob;
use App\Models\ReqDesigner;
use App\Jobs\OrderSellerJob;
use Illuminate\Http\Request;
use App\Jobs\PaymentBayerJob;
use App\Http\Utilities\Wallet;
use Shetabit\Multipay\Invoice;
use App\Jobs\TarahiDesignerJob;
use App\Jobs\PaymentDesignerJob;
use App\Jobs\PaymentKarfarmaJob;
use App\Http\Utilities\OrderCreate;
use App\Http\Utilities\DepositCreate;
use App\Http\Utilities\PaymentCreate;
use Shetabit\Payment\Facade\Payment1;
use App\Http\Utilities\OrderableCreate;
use App\Notifications\TarahiNotification;
use App\Notifications\PaymentNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Order $order,User $user,Company $company,Route $route)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $asidemini = $request->query->has('asidemini') ? $request->query->get('asidemini') :null;
        $dark = $request->query->has('dark') ? $request->query->get('dark') :null;
        $users = $user->with('payments')->with('file')->with('profile')->with('roles')->find(auth()->user()->id);
        $notifications = $users->unreadnotifications;
        $statuses = $request->session()->has('statuses') ? $request->session()->get('statuses'):null;
        $ids = $request->session()->has('ids') ? $request->session()->get('ids'):null;
        $prices = $request->session()->has('prices') ? $request->session()->get('prices'):null;
        $orders = $order->where('user_id',auth()->user()->id)->with('products')->orderBy('created_at','desc')->paginate(9)
        ->WithQueryString(); //->WithQueryString()
        $id=$request->query();
        $users->notifications->find($id)->MarkAsRead();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()->descriptions?
        $route->where('name',$request->path())->first()->descriptions->first():null;

        $wallet = Wallet::all($users);

        return Inertia::render('Users/Buyer/Order/order-index',['cart'=>[ 'products' => $cart->products,'count' => $cart->count,'price' => $cart->price,'discount'=> $cart->discount,'coupon' => $cart->coupon,'total' => $cart->total,
            'tax'=> $cart->tax,'col'=>$cart->col,'payment'=>$cart->payment,'balance'=>$cart->balance],'orders'=> $orders,'ids'=> $ids,
        'statuses'=> $statuses,'prices'=>$prices,'users' => $users,'notifications'=> $notifications,
        'companies'=>$companies,'descriptions'=>$descriptions,'cartCount'=> $cart->count,
        'cartTotal' => $cart->total,'dark'=>$dark,'asidemini'=>$asidemini,'path'=>$request->path()
        ,'wallet'=>$wallet]);
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
    public function store(Request $request, Order $order,User $user,Payment $payment, Orderable $orderable,Coupon $coupon,Role $role,Company $company,Tarahi $tarahi,ReqDesigner $reqDesigner)
    {

        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $time = new Carbon;
        $users = $user->with('file')->with('profile')->find(auth()->user()->id);
        $wallet = Wallet::all($users);
        $companies = $company->first();

        $request->validate([
            'cartCount' => 'required',
            'cartPrice'=> 'required',
            'cartTotal'=> 'required',
        ]);
        // dd($request->request->get('wallet') == 'sepehr' && $cart->payment > $wallet);
        if($request->dargah == 'wallet')
        {

            if($cart->products)
            {
                $price1 =[];
                $count1 =[];
                $discount1 =[];
                $total1 =[];
                $tax1 =[];
                $col1 = [];

                foreach ($cart->products as $key => $value)
                {
                    if($value['model'] == 'App\Models\Tarahi')
                    {
                        // $b = (object)[$key =>$value] ;
                        +$price1 = ( $value['total'] + $value['discount'] ) / $value['count'];
                        +$count1 = $value['count'];
                        +$discount1 = $value['discount'];
                        +$total1 = $value['total'];
                        +$tax1 = $value['tax'];
                        +$col1 = $value['col'];
                    }
                    else if ($value['model'] == 'App\Models\ReqDesigner')
                    {
                        +$price1 = ( $value['total'] + $value['discount'] ) / $value['count'];
                        +$count1 = $value['count'];
                        +$discount1 = $value['discount'];
                        +$total1 = $value['total'];
                        +$tax1 = $value['tax'];
                        +$col1 = $value['col'];

                    }

                }

                if($wallet < $cart->payment)
                {

                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'خرید!',
                            'text'=> 'موجودی کیف پول کافی نمی باشد.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );

                }
                else
                {
                    // dd($cart,count($price1) > 0,count($count1) > 0,count($discount1) > 0,count($total1) > 0,count($tax1) > 0,count($col1) > 0,count($col1) > 0);

                    if($value['request']->get('name'))
                    {
                        $orders = null;
                    }
                    else
                    {
                         if($cart->coupon > ($cart->price-$cart->discount) )
                         {
                            $orders = OrderCreate::create(auth()->user()->id,$cart->price,$cart->count,$cart->discount,($cart->price-$cart->discount),0,0,0,0,0);
                         }
                        else if (count($price1) > 0 && count($count1) > 0 && count($discount1) > 0 && count($total1) > 0 && count($tax1) > 0 && count($col1) > 0 && count($col1) > 0)
                        {
                            $orders = OrderCreate::create(auth()->user()->id,$cart->price-$price1,$cart->count-$count1,$cart->discount-$discount1,$cart->coupon,$cart->total-$total1,
                            $cart->tax-$tax1,$cart->col-$col1,$cart->payment-$col1,$cart->balance);
                        }
                        else
                        {
                            $orders = OrderCreate::create(auth()->user()->id,$cart->price,$cart->count,$cart->discount,$cart->coupon,$cart->total,
                            $cart->tax,$cart->col,$cart->payment,$cart->balance);
                        };
                        // dd($orders);
                    }

                    // $orders = null;
                    foreach ($cart->products as $key => $value)
                    {

                        $users = $user->with('image')->with('profile')->with('payments')->with('deposits')->with('roles')->find($value['product']->user_id);
                        // dd($value,$wallet , $cart->payment,$wallet < $cart->payment);
                        $companies = Company::with('user')->with('image')->first();
                        $comison = round($cart->col * $companies->comison);
                        $tax =  $comison * $companies->tax ;
                        // dd($price1,$count1,$discount1,$total1,$tax1,$col1,$value['request']->get('name'),$cart,$orders);
                        if($value['request']->get('name'))
                        {

                            // dd($value,$tarahi);
                            if($value['request']->get('name') == 'designer' )
                            {
                                $tarahi = $tarahi->findOrfail($value['product']['tarahiRegister']['id']);
                                // dd($reqDesigner->with('tarahiRegisterStatus')->where('user_id',auth()->user()->id)->where('status',5)->get());
                                $reqdesigners = $reqDesigner->with('tarahiRegisterStatus')->where('user_id',auth()->user()->id)->where('status',5)->get();

                                $a = [];
                                foreach($reqdesigners as $key => $reqdesigner)
                                {

                                    if($reqdesigner->tarahiRegisterStatus)
                                    {
                                        array_push ( $a,$reqdesigner->tarahiRegisterStatus  );
                                    }

                                }
                                // dd(count($a) ,count($a) > 0);
                                if( count($a) > 0)
                                {
                                    $request->session()->flash(
                                        'alert' ,[
                                            'title'=>'پیشنهاد!',
                                            'text'=> 'شما پروژه در دست اقدام دارید که اتمام نشده ، لطفا پس از اتمام آن مجدد در خواست ارسال نمایید.',
                                            'icon'=> 'error',
                                            'button' => 'ok']
                                        );

                                }
                                else
                                {

                                    // $tarahi = $tarahi->findOrfail($request->id);
                                    // $users = $user->with('image')->with('profile')->find(auth()->user()->id);
                                    // $companies = $user->with('image')->first();
                                    $reqDesigners = $reqDesigner->where('user_id',auth()->user()->id)->where('tarahi_id',$tarahi->id)->where('status',4)->first();

                                    // dd($tarahi->reqdesigner_id == null && $tarahi->price_block == 0,$tarahi->reqdesigner_id == null || $tarahi->registerdesigner && $tarahi->registerdesigner->user_id == auth()->user()->id);
                                    if($tarahi->reqdesigner_id == null && $tarahi->price_block == 0)
                                    {
                                        $request->session()->flash(
                                            'alert' ,[
                                                'title'=>'پروژه!',
                                                'text'=> 'واگذاری توسط کارفرما لغو شده است.',
                                                'icon'=> 'error',
                                                'button' => 'ok']
                                        );

                                    }
                                    else if($tarahi->reqdesigner_id == null || $tarahi->registerdesigner && $tarahi->registerdesigner->user_id == auth()->user()->id)
                                    {
                                        // dd($request->dargah == 'behpardakht' , $users->profile->wallet < ($tarahi->total*$companies->design_damage));
                                        if($reqDesigners->block_price == 0)
                                        {


                                            $tarahis = $tarahi->update([
                                                'date'=> $time->addDays($reqDesigners->expired),
                                                'status'=> 5
                                            ]);

                                            $reqDesigne = $reqDesigners->update([
                                                'block_price'=> $tarahi->total*$companies->design_damage,
                                                'status'=> 5
                                            ]);

                                            if($reqDesigne && $tarahis)
                                            {

                                                $payments = PaymentCreate::create(auth()->user()->id,' برداشت بابت مسدودی ضمانت پروژه '.$tarahi->title,$tarahi->total*$companies->design_damage,0,0,$time,4,
                                                    Tarahi::class,$tarahi->id,0,0,0,null,null);

                                                if($payments)
                                                {
                                                    $message = 'برداشت بابت مسدودی ضمانت پروژه.';
                                                    PaymentJob::dispatch($payments,$message)->delay(now()->addMinute($companies->job));

                                                    // $times =  $time->createFromFormat('Y-m-d H:i:s.u',$time->addDay($reqDesigners->expired).'.0000');

                                                    $message = 'طراح مورد نظر پروژه شما را پذیرفت.';
                                                    TarahiJob::dispatch($tarahi,$message)->delay(now()->addMinute($companies->job));

                                                    // $request->session()->remove('cart');

                                                    $request->session()->flash(
                                                        'alert' ,[
                                                            'title'=>'پروژه!',
                                                            'text'=> 'پرداخت و باموفقیت پذیرفته شد.',
                                                            'icon'=> 'success',
                                                            'button' => 'ok']
                                                    );

                                                }
                                                else
                                                {
                                                    $request->session()->flash(
                                                        'alert' ,[
                                                            'title'=>'درخواست!',
                                                            'text'=> 'انجام نشد، مشکلی پیش آمده با پشتیبانی تماس تیکت  بزنید.',
                                                            'icon'=> 'error',
                                                            'button' => 'ok']
                                                    );

                                                }
                                            }
                                            else
                                            {
                                                $request->session()->flash(
                                                    'alert' ,[
                                                        'title'=>'درخواست!',
                                                        'text'=> 'انجام نشد، مشکلی پیش آمده با پشتیبانی تماس تیکت  بزنید.',
                                                        'icon'=> 'error',
                                                        'button' => 'ok']
                                                );

                                            }
                                        }
                                        else
                                        {
                                            $times =  $time->createFromFormat('Y-m-d H:i:s.u',$time->addDay($reqDesigners->expired).'.0000');

                                            $tarahi->update([
                                                'date'=> $times,
                                                'status'=> 5,
                                            ]);

                                            $reqDesigners->update([
                                                'block_price'=> $tarahi->price*$companies->design_damage,
                                                'status'=> 5
                                            ]);

                                                $message = 'طراح مورد نظر پروژه شما را پذیرفت.';

                                                TarahiJob::dispatch($tarahi,$message)->delay(now()->addMinute($companies->job));

                                                $message = 'زمان پروژه به اتمام رسیده است.';

                                                TarahiJob::dispatch($tarahi,$message)->delay($times);

                                                $request->session()->flash(
                                                    'alert' ,[
                                                        'title'=>'پروژه!',
                                                        'text'=> 'باموفقیت پذیرفته شد.',
                                                        'icon'=> 'success',
                                                        'button' => 'ok']
                                                );
                                        }
                                    }
                                    else
                                    {
                                        $request->session()->flash(
                                            'alert' ,[
                                                'title'=>'پروژه!',
                                                'text'=> 'به نام کاربر دیگری ثبت شده است.',
                                                'icon'=> 'error',
                                                'button' => 'ok']
                                        );

                                    }
                                }
                            }
                            else if ($value['request']->get('name') == 'karfarma')
                            {
                                $tarahi = $tarahi->findOrfail($value['product']->id);
                                $users = $user->with('file')->with('profile')->find(auth()->user()->id);
                                $companies = $user->with('image')->first();

                                $reqDesigners = $reqDesigner->findOrfail($value['request']->get('reqDesigner_id'));

                                // dd($cart,$value,$tarahi->price_block);
                                if($tarahi->price_block)
                                {
                                    if($tarahi->price == $reqDesigners->price)
                                    {
                                        if($tarahi->designer_id == null && $tarahi->date == null && $tarahi->status == 4)
                                        {
                                            $designers = $tarahi->update([
                                                'reqdesigner_id'=>$reqDesigners->id,
                                                'status'=> 2,
                                            ]);

                                            if($designers)
                                            {
                                                $message = 'پروژه به شما واگذار شد.';

                                                TarahiJob::dispatch($tarahi,$message)->delay(now()->addMinute($companies->job));
                                                $request->session()->flash(
                                                    'alert' ,[
                                                        'title'=>'پروژه!',
                                                        'text'=> 'باموفقیت واگذار شد،چنانچه طراح مورد نظر پس از مدتی پروژه نپذیرفت میتوانید واگذاری را لغو و پروژه را به شخص دیگری واگذار نمایید.',
                                                        'icon'=> 'success',
                                                        'button' => 'ok']
                                                );
                                            }
                                            else
                                            {
                                                $request->session()->flash(
                                                    'alert' ,[
                                                        'title'=>'پروژه!',
                                                        'text'=> 'واگذار نشد.حتما مشکلی پیش آمده، مجدد تلاش نمایید.',
                                                        'icon'=> 'success',
                                                        'button' => 'ok']
                                                );

                                            }
                                        }
                                        else
                                        {
                                            $request->session()->flash(
                                                'alert' ,[
                                                    'title'=>'پروژه!',
                                                    'text'=> 'ثبت نشد، به طور همزمان نمی توانید دو درخواست به چند طراح ارسال نماید.',
                                                    'icon'=> 'error',
                                                    'button' => 'ok']
                                            );

                                        }
                                    }
                                }
                                else
                                {
                                    // dd($tarahi->price == $reqDesigners->price && $reqDesigners->status == 4);
                                    if($tarahi->price == $reqDesigners->price && $reqDesigners->status == 4)
                                    {

                                        if ($wallet >= $tarahi->price )
                                        {
                                            $designers = $tarahi->update([
                                                'reqdesigner_id'=>$reqDesigners->id,
                                                'total'=> $tarahi->price+$tarahi->comison+$tarahi->tax+$tarahi->complications,
                                                'status'=> 2,
                                                'price_block' => $tarahi->price,
                                            ]);

                                            if($designers)
                                            {

                                                $payments = PaymentCreate::create(auth()->user()->id,' برداشت بابت مسدودی واگذاری پروژه '.$tarahi->title,$tarahi->price,0,0,$time,4,
                                                    Tarahi::class,$tarahi->id,0,0,0,null,null);

                                                if($payments)
                                                {
                                                    $message = 'کاهش موجودی کیف پول بابت مسدودی واگذاری پروژه.';
                                                    PaymentJob::dispatch($payments,$message)->delay(now()->addMinute($companies->job));

                                                    $message = 'پروژه به شما واگذار شد.';

                                                    TarahiDesignerJob::dispatch($reqDesigners,$message)->delay(now()->addMinute($companies->job));



                                                    $request->session()->flash(
                                                        'alert' ,[
                                                            'title'=>'پروژه!',
                                                            'text'=> 'باموفقیت واگذار شد،چنانچه طراح مورد نظر پس از مدتی پروژه نپذیرفت میتوانید واگذاری را لغو و پروژه را به شخص دیگری واگذار نمایید.',
                                                            'icon'=> 'success',
                                                            'button' => 'ok']
                                                    );

                                                }
                                                else
                                                {
                                                    $request->session()->flash(
                                                        'alert' ,[
                                                            'title'=>'درخواست!',
                                                            'text'=> 'انجام نشد، مشکلی پیش آمده با پشتیبانی تماس تیکت  بزنید.',
                                                            'icon'=> 'error',
                                                            'button' => 'ok']
                                                    );

                                                }

                                            }
                                            else
                                            {
                                                $request->session()->flash(
                                                    'alert' ,[
                                                        'title'=>'پروژه!',
                                                        'text'=> 'واگذار نشد.حتما مشکلی پیش آمده، مجدد تلاش نمایید.',
                                                        'icon'=> 'error',
                                                        'button' => 'ok']
                                                );

                                            }

                                        }
                                        else
                                        {
                                            $request->session()->flash(
                                                'alert' ,[
                                                    'title'=>'پروژه!',
                                                    'text'=> 'موجودی کیف پول کافی نمی باشد.',
                                                    'icon'=> 'error',
                                                    'button' => 'ok']
                                            );

                                        }
                                    }
                                    else
                                    {
                                        // dd($reqDesigners->status == 4);
                                        if($reqDesigners->status == 4)
                                        {

                                            // dd($wallet >= $reqDesigners->price  && $reqDesigners);
                                            if($wallet >= $reqDesigners->price  && $reqDesigners)
                                            {

                                                $designers = $tarahi->update([
                                                    'reqdesigner_id'=>$reqDesigners->id,
                                                    // 'price' =>$reqDesigners->price,
                                                    // 'date'=> $time->addDays($reqDesigners->expired),
                                                    'total'=> $reqDesigners->price+$tarahi->comison+$tarahi->tax+$tarahi->complications,
                                                    'status'=> 2,
                                                    'price_block' =>$reqDesigners->price,
                                                ]);


                                                if($designers)
                                                {

                                                    $payments = PaymentCreate::create(auth()->user()->id,' برداشت بابت واگذاری پروژه'.$tarahi->title,$value['col'],0,0,$time,4,
                                                        Tarahi::class,$tarahi->id,0,0,0,null,null);


                                                    if($payments)
                                                    {
                                                        $message = 'کاهش موجودی کیف پول بابت واگذاری پروژه.';

                                                        PaymentJob::dispatch($payments,$message)->delay(now()->addMinute($companies->job));

                                                        $message = 'پروژه به شما واگذار شد.';

                                                        TarahiDesignerJob::dispatch($reqDesigners,$message)->delay(now()->addMinute($companies->job));

                                                    }

                                                    $request->session()->flash(
                                                        'alert' ,[
                                                            'title'=>'پروژه!',
                                                            'text'=> 'باموفقیت واگذار شد،چنانچه طراح مورد نظر پس از مدتی پروژه نپذیرفت میتوانید واگذاری را لغو و پروژه را به شخص دیگری واگذار نمایید.',
                                                            'icon'=> 'success',
                                                            'button' => 'ok']
                                                    );

                                                }
                                                else
                                                {
                                                    $request->session()->flash(
                                                        'alert' ,[
                                                            'title'=>'پروژه!',
                                                            'text'=> 'واگذار نشد.حتما مشکلی پیش آمده، مجدد تلاش نمایید.',
                                                            'icon'=> 'success',
                                                            'button' => 'ok']
                                                    );

                                                }
                                            }
                                            else
                                            {
                                                $request->session()->flash(
                                                    'alert' ,[
                                                        'title'=>'پروژه!',
                                                        'text'=> 'موجودی کیف پول کافی نمی باشد.',
                                                        'icon'=> 'error',
                                                        'button' => 'ok']
                                                );

                                            }
                                        }
                                        else
                                        {
                                            $request->session()->flash(
                                                'alert' ,[
                                                    'title'=>'پروژه!',
                                                    'text'=> 'پیشنهاد طراح تغیرات داشته است.مجدد بررسی نمایید.',
                                                    'icon'=> 'error',
                                                    'button' => 'ok']
                                            );

                                        }
                                    }
                                }


                            }
                            else
                            {
                                $request->session()->flash(
                                    'alert' ,[
                                        'title'=>'درخواست!',
                                        'text'=> 'انجام نشد، تلاش نفرمایید.',
                                        'icon'=> 'error',
                                        'button' => 'ok']
                                );

                            }

                        }
                        else
                        {
                            // dd($cart->coupon > ($cart->price-$cart->discount));


                            if ($orders)
                            {
                                $message = 'محصول فروش رفته است.';
                                OrderModirJob::dispatch($orders,$message)->delay(now()->addMinute($companies->job));

                                $coupons = Coupon::where('user_id',auth()->user()->id)->where('order_id',null)->first();

                                if($coupons && $coupons->price == $cart->coupon)
                                {
                                    $coupons->update(['order_id'=>$orders->id]);
                                }

                                $product = $value['product'];
                                $count = $value['count'];
                                $discount = $value['discount'];
                                $coupon = $cart->coupon/count($cart->products);
                                $total = ($product->price*$count)-($discount+$coupon);
                                $comison = ($total*$companies->comison);
                                $tax = $comison * $companies->tax;
                                $col = $total-$comison-$tax;
                                $model = $value['model'];

                                $users = $user->with('roles')->find($product->user_id);

                                if ($users->roles->max('id') > 2)
                                {

                                    if($cart->coupon > ($cart->price-$cart->discount))
                                    {
                                        $orderables = OrderableCreate::create($orders->id,$product->user_id,$product->price,$count,$discount,(($product->price*$count)-$discount),
                                            0,0,0,0,$model,$product->id,);

                                    }
                                    else
                                    {
                                        $orderables = OrderableCreate::create($orders->id,$product->user_id,$product->price,$count,$discount,$coupon,
                                            $total,0,$value['tax'],$value['col'],$model,$product->id,);

                                    }
                                }
                                else
                                {

                                    if($cart->coupon > ($cart->price-$cart->discount))
                                    {
                                        $orderables = OrderableCreate::create($orders->id,$product->user_id,$product->price,$count,$discount,(($product->price*$count)-$discount),
                                            0,0,0,0,$model,$product->id,);

                                    }
                                    else
                                    {
                                        $orderables = OrderableCreate::create($orders->id,$product->user_id,$product->price,$count,$discount,$coupon,$total,$comison,$tax,$col,$model,$product->id);

                                        if($orderables)
                                        {

                                            $deposets = DepositCreate::create($orderables->user_id, ' واریز بابت فروش محصول '.$product->name,
                                                $orderables->total,0,0,$time,4,$model,$product->id,0,0,0,null,null);

                                            if($deposets )
                                            {

                                                $message = 'افزایش موجودی کیف پول بابت فروش محصول.';

                                                DepositJob::dispatch($deposets,$message)->delay(now()->addMinute($companies->job));
                                            }


                                            $payments = PaymentCreate::create($orderables->user_id,' برداشت بابت کارمزد و مالیات فروش محصول '.$product->name,($orderables->comison+$orderables->tax),0,0,$time,4,
                                                $model,$product->id,0,0,0,null,null);


                                            if($payments)
                                            {
                                                $message = ' کاهش موجودی کیف پول بابت کارمزد و مالیات فروش محصول.';

                                                PaymentJob::dispatch($payments,$message)->delay(now()->addMinute($companies->job));

                                            }

                                        }
                                    }
                                }

                                if($cart->coupon > ($cart->price-$cart->discount))
                                {
                                    $message = 'محصول خریداری شد.';
                                    OrderJob::dispatch($order,$message)->delay(now()->addMinute($companies->job));



                                    $request->session()->flash(
                                        'alert' ,[
                                            'title'=>'خرید!',
                                            'text'=> 'باموفقیت انجام شد.',
                                            'icon'=> 'success',
                                            'button' => 'ok']
                                    );

                                }
                                else
                                {

                                    if ($users->roles->max('id') > 2)
                                    {
                                        $payments = PaymentCreate::create(auth()->user()->id,' برداشت بابت خرید محصول '.$product->name ,$orderables->total+$orderables->comison+$orderables->tax,0,0,$time,4,
                                        $model,$product->id,0,0,0,null,null);

                                        if($payments)
                                        {
                                            $message = ' کاهش موجودی کیف پول بابت کارمزد و مالیات فروش محصول.';

                                            PaymentJob::dispatch($payments,$message)->delay(now()->addMinute($companies->job));

                                        }
                                    }
                                    else
                                    {

                                        $payments = PaymentCreate::create(auth()->user()->id,' برداشت بابت خرید محصول '.$product->name ,$orderables->total,0,0,$time,4,
                                                    $model,$product->id,0,0,0,null,null);

                                        if($payments)
                                        {
                                            $message = ' کاهش موجودی کیف پول بابت کارمزد و مالیات فروش محصول.';

                                            PaymentJob::dispatch($payments,$message)->delay(now()->addMinute($companies->job));

                                        }
                                    }

                                    $message = 'محصول خریداری شد.';
                                    OrderJob::dispatch($orders,$message)->delay(now()->addMinute($companies->job));
                                    // $request->session()->remove('cart');

                                    $request->session()->flash(
                                        'alert' ,[
                                            'title'=>'خرید!',
                                            'text'=> 'باموفقیت انجام شد.',
                                            'icon'=> 'success',
                                            'button' => 'ok']
                                    );

                                }

                            }
                            else
                            {
                                $request->session()->flash(
                                    'alert' ,[
                                        'title'=>'سفارش!',
                                        'text'=> 'انجام نشد،مجدد تلاش نمایید.',
                                        'icon'=> 'error',
                                        'button' => 'ok']
                                );
                            }
                        }

                    }
                }
                $request->session()->remove('cart');
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'سفارش!',
                        'text'=> 'انجام نشد،مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

            }

            // return redirect()->back();
        }
        elseif($request->request->get('wallet') == 'behpardakht' && $cart->total > $wallet)
        {
            $url = route('verify');
            $invoice = (new Invoice)->amount($cart->payment - $wallet);
            $invoice->detail(['user_id' => auth()->user()->id]);
            $payment = Payment::via('behpardakht')->callbackUrl($url)->purchase($invoice, function($driver, $transactionId){

            })->pay()->render();

            $deposets = DepositCreate::create(auth()->user()->id, ' واریز از درگاه ملت ',$cart->payment,0,0,$time,4,User::class,auth()->user()->id,0,0,0,$payment->uuid,$payment->transactionId);

            if($deposets )
            {

                $message = 'افزایش موجودی کیف پول.';

                DepositJob::dispatch($deposets,$message)->delay(now()->addMinute($companies->job));
            }



            return $deposets;

        }
        elseif($request->request->get('wallet') == 'sepehr' && $cart->payment > $wallet)
        {

            $url = route('verify');
            $invoice = (new Invoice)->amount($cart->total - $wallet);
            $invoice->detail(['user_id' => auth()->user()->id]);
            $payment = Payment::via('sepehr')->callbackUrl($url)->purchase($invoice, function($driver, $transactionId){

            })->pay()->render();

            $deposets = DepositCreate::create(auth()->user()->id, ' واریز از درگاه صادرات ',$cart->payment,0,0,$time,4,User::class,auth()->user()->id,0,0,0,$payment->uuid,$payment->transactionId);

            if($deposets )
            {

                $message = 'افزایش موجودی کیف پول.';

                DepositJob::dispatch($deposets,$message)->delay(now()->addMinute($companies->job));
            }



            return $deposets;
        }

        // $request->session()->remove('cart');
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        return abort(404);
    }

}
