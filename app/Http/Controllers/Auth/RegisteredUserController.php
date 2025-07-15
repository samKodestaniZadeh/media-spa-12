<?php

namespace App\Http\Controllers\Auth;

use App\Models\Cart;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Company;
use App\Models\Identity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Utilities\ProfileCreate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(Request $request,Company $company): Response
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart') : null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $companies = $company->with('image')->first();
        return Inertia::render('Auth/Register2',['cartCount' => $cart->count,'alert'=> $alert, 'companies'=>$companies]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request,User $user): RedirectResponse
    {
        $request->validate([
            'name_show' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'lasst_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'person' => 'required'
        ]);

        if($user->where('user_name',$request->name.'_'.$request->lasst_name)->first())
        {
            $request->session()->flash(
                'alert' ,[
                    'title'=>'ثبت نام!',
                    'text'=> 'انجام نشد، این کاربری وجود دارد لطفا نام دیگری وارد نمایید.',
                    'icon'=> 'error',
                    'button' => 'ok']
            );

            return redirect()->back();
        }
        else
        {
            $users = User::create([
                'user_name' => $request->name.'_'.$request->lasst_name,
                'name' => $request->name,
                'lasst_name' => $request->lasst_name,
                'name_show'=>$request->name_show,
                'national_code' => $request->national_code,
                'tel' => $request->tel,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 0,
                'person' => $request->person
            ]);

            if ($users)
            {
                $identitys = Identity::create([
                    'user_id'=>$users->id,
                ]);

                // $response = Http::asForm()->post(route('identity.store'), [
                //     'user_id'=>$users->id,
                // ]);

                    if ($users && $identitys) {

                        $profiles = ProfileCreate::all($request,$users);
                        if ($profiles) {

                            event(new Registered($users));

                            Auth::login($users);

                            return to_route('dashboard.index');
                        }
                        else
                        {
                            $request->session()->flash(
                                'alert' ,[
                                    'title'=>'پروفایل!',
                                    'text'=> 'ثبت نشد، مجدد تلاش نمایید.',
                                    'icon'=> 'error',
                                    'button' => 'ok']
                            );

                            return redirect()->back();
                        }

                    }
                    else
                    {
                        $user->find($users->id)->delete();
                        $request->session()->flash(
                            'alert' ,[
                                'title'=>'ثبت نام!',
                                'text'=> 'انجام نشد، مجدد تلاش نمایید.',
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
                        'title'=>'ثبت نام!',
                        'text'=> 'انجام نشد، مجدد تلاش نمایید.',
                        'icon'=> 'error',
                        'button' => 'ok']
                );

                return redirect()->back();
            }
        }

        // return to_route('dashboard');
    }
}
