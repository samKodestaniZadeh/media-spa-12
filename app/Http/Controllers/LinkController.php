<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Link;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Social;
use App\Models\Product;
use App\Models\Orderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LinkController extends Controller
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
    public function create(Request $request,User $user)
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Link $link,Social $social)
    {
        // Gate::authorize('viewAny',$link);
        // dd($request);
        if($request->order)
        {
            $request->validate([
                'id'=> 'required',
                'link'=> 'required||url',
                'order'=> 'required',
            ]);
            if($link->where('linkable_id',$request->id)->where('linkable_type',Product::class)->where('user_id',auth()->user()->id)->first())
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'دامنه!',
                        'text'=> 'ثبت نشد.قبلا برای این محصول دامنه ثبت شده است.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );
            }
            else
            {
                $links = $link->create([
                    'user_id' => auth()->user()->id,
                    'link' => $request->link,
                    'linkable_type' => Orderable::class,
                    'linkable_id' => $request->id,
                ]);
                if($links)
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'دامنه!',
                            'text'=> 'ثبت شد.اکنون میتوانید محصول را دانلود نمایید.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'دامنه!',
                            'text'=> 'ثبت نشد.مجدد تلاش نمایید.',
                            'icon'=> 'error',
                            'button' => 'ok']
                    );
                }


                return redirect()->back();
            }

        }
        else
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'دامنه یا لینک!',
                    'text'=> 'ثبت نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function show(Link $link)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function edit(Link $link)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Link $link)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function destroy(Link $link,Request $request)
    {

        if ($link->user_id == auth()->user()->id) {
            if ($link->status !== 3)
            {

                $links = $link->destroy($link->id);

                if ($links)
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'شبکه اجتماعی!',
                            'text'=> 'باموفقیت حذف شد.',
                            'icon'=> 'success',
                            'button' => 'ok']
                    );
                }
                else
                {
                    $request->session()->flash(
                        'alert' ,[
                            'title'=>'شبکه اجتماعی!',
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
                        'title'=>'شبکه اجتماعی!',
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
                    'title'=>'شبکه اجتماعی!',
                    'text'=> 'حذف نشد، شما مجاز به حذف کردن نیستید لطفا مجدد تلاش نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );
        }
        return redirect()->back();
    }
}
