<?php
namespace App\Http\Utilities;

use App\Models\User;
use App\Models\Payment;

class PaymentCreate
{

    public static function all($request)
    {

        $request->validate([
            'transaction' => 'required',
            'price'=> 'required',
            'bank'=> 'required',
            'date'=> 'required',
        ]);

        if ($request->id)
        {

            $payments = Payment::find($request->id)->update([
                'user_id'=> auth()->user()->id,
                'transaction'=>$request->transaction['name'],
                'price'=>$request->price,
                'bank_name'=>$request->bank['menu']['name'],
                'account_name'=>$request->bank['account_name'],
                'cart_number'=>$request->bank['cart_number'],
                'code_p'=> 0,
                'code_e'=>0,
                'date'=>$request->date,
                'paymentable_type'=>User::class,
                'paymentable_id'=>auth()->user()->id,
            ]);

        }
        else
        {

            $payments = Payment::create([
                'user_id'=> auth()->user()->id,
                'transaction'=>$request->transaction['name'],
                'price'=>$request->price,
                'bank_name'=>$request->bank['menu']['name'],
                'account_name'=>$request->bank['account_name'],
                'cart_number'=>$request->bank['cart_number'],
                'code_p'=> 0,
                'code_e'=>0,
                'date'=>$request->date,
                'paymentable_type'=>User::class,
                'paymentable_id'=>auth()->user()->id,
            ]);
        }
        return $payments;

    }
    public static function create($user_id,$transaction,$price,$bank_name,$account_name,$date,$status,$paymentable_type,$paymentable_id,$cart_number,$code_p,$code_e,$uuid,$transactionId)
    {

        $payments = Payment::create([
            'user_id'=> $user_id,
            'transaction' => $transaction,
            'price'=>$price,
            'bank_name'=> $bank_name,
            'account_name'=>$account_name,
            'date'=> $date,
            'status'=> $status,
            'paymentable_type'=>$paymentable_type,
            'paymentable_id'=>$paymentable_id,
            'cart_number'=> $cart_number,
            'code_p'=> $code_p,
            'code_e'=> $code_e,
            'uuid'=> $uuid,
            'transactionId'=>$transactionId,
        ]);
        return $payments;
    }
}
