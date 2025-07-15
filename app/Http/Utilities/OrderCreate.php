<?php
namespace App\Http\Utilities;

use App\Models\Order;


class OrderCreate
{

    public static function create($user_id,$price,$count,$discount,$coupon,$total,$tax,$col,$payment,$balance)
    {

        $orders = Order::create([
            'user_id' =>$user_id,
            'price'=> $price,
            'count' => $count,
            'discount' => $discount,
            'coupon' => $coupon,
            'total' => $total,
            'tax' => $tax,
            'col' => $col,
            'payment' => $payment,
            'balance' => $balance,

        ]);
        return $orders;
    }
}
