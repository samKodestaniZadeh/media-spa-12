<?php
namespace App\Http\Utilities;

use App\Models\User;
use App\Models\Deposit;

class DepositCreate
{

    public static function all($request)
    {

        $request->validate([
            'transaction'=> 'required',
            'price'=> 'required|numeric',
            'date'=> 'required|date',
            'cart_number'=> 'required|numeric',
            'code_p'=> 'required|numeric',
            'user'=> 'required',
            'code_e'=> 'required|numeric',
        ]);

        if ($request->id)
        {

            $deposits = Deposit::find($request->id)->update([
                'user_id'=>$request->user,
                'transaction'=>$request->transaction,
                'price'=>$request->price,
                'bank_name'=>null,
                'account_name'=>null,
                'cart_number'=> $request->cart_number,
                'code_p'=> $request->code_p,
                'code_e'=> $request->code_e,
                'date'=>$request->date,
                'depositable_type'=>User::class,
                'depositable_id'=>auth()->user()->id,
                'status'=>4,
            ]);

        }
        else
        {

            $deposits = Deposit::create([
                'user_id'=>$request->user,
                'transaction'=>$request->transaction,
                'price'=>$request->price,
                'bank_name'=>null,
                'account_name'=>null,
                'cart_number'=> $request->cart_number,
                'code_p'=> $request->code_p,
                'code_e'=> $request->code_e,
                'date'=>$request->date,
                'depositable_type'=>User::class,
                'depositable_id'=>auth()->user()->id,
                'status'=>4,
            ]);
        }
        return $deposits;

    }
    public static function create($user_id,$transaction,$price,$bank_name,$account_name,$date,$status,$depositable_type,$depositable_id,$cart_number,$code_p,$code_e,$uuid,$transactionId)
    {

        $deposits = Deposit::create([
            'user_id'=> $user_id,
            'transaction' => $transaction,
            'price'=>$price,
            'bank_name'=> $bank_name,
            'account_name'=>$account_name,
            'date'=> $date,
            'status'=> $status,
            'depositable_type'=>$depositable_type,
            'depositable_id'=>$depositable_id,
            'cart_number'=> $cart_number,
            'code_p'=> $code_p,
            'code_e'=> $code_e,
            'uuid'=> $uuid,
            'transactionId'=>$transactionId,
        ]);

        return $deposits;
    }
}
