<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Jobs\LinkJob;
use App\Models\Social;
use Illuminate\Http\Request;

class LinkAdminController extends Controller
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
    public function store(Request $request,Link $link,Social $social)
    {

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
        else if($request->social)
        {
            foreach ($request->social as $key => $value)
            {
                // dd($link->find($value['id']));
                $request->validate([
                    'link' => 'url',
                ]);
                if($value['link'] && $value['id'])
                {
                    $links = $social->find($value['social_id'])->link->update([
                        // 'user_id' => auth()->user()->id,
                        // 'name'=>$social->find($value['social_id'])->name,
                        // 'tag'=>$social->find($value['social_id'])->tag,
                        'link' => $value['link'],
                        'linkable_type' => Social::class,
                        'linkable_id' => $value['social_id'],
                        'status' => $value['status'],
                    ]);

                    if ($link->find($value['id'])->link !== $value['link'] || $link->find($value['id'])->status !== $value['status'])
                    {
                        LinkJob::dispatch($link->find($value['id']))->delay(now()->addMinute(5));
                    }

                }
                else if($value['link'] && $value['id'] == null)
                {
                    // dd($value);
                    $links = $link->create([
                        'user_id' => $request->id,
                        // 'name'=>$social->find($value['social_id'])->name,
                        // 'tag'=>$social->find($value['social_id'])->tag,
                        'link' => $value['link'],
                        'linkable_type' => Social::class,
                        'linkable_id' => $value['social_id'],
                    ]);
                    LinkJob::dispatch($links)->delay(now()->addMinute(5));
                }

            }

            if($links)
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'لینک!',
                        'text'=> 'با موفقیت ثبت شد.',
                        'icon'=> 'success',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
            else
            {
                $request->session()->flash(
                    'alert' ,[
                        'title'=>'لینک!',
                        'text'=> 'ثبت نشد،مشکلی پیش آمده مجدد تلاش نمایید.',
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
