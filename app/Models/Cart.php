<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;
    public $products =[];
    public $count = 0;
    public $price = 0;
    public $discount = 0;
    public $coupon = 0;
    public $total = 0;
    public $tax = 0;
    public $col = 0;
    public $payment = 0;
    public $balance = 0;
    // public $request = 0;
    public function __construct($cart = null)
    {
        if(!is_null($cart))
        {
            $this->products = $cart->products;
            $this->count = $cart->count;
            $this->price = $cart->price;
            $this->discount = $cart->discount;
            $this->coupon = $cart->coupon;
            $this->total = $cart->total;
            $this->tax = $cart->tax;
            $this->col = $cart->col;
            $this->payment = $cart->payment;
            $this->balance = $cart->balance;
            // $this->request = $cart->request;
        }
    }

    public function addToCart($product,$model,$request)
    {
        // dd($product,$model,$request);
        $companies = Company::with('user')->with('image')->first();
        if($model == 'App\\Models\\Tarahi')
        {
            $reqdesigner = ReqDesigner::find($request->reqDesigner_id);
            $price = $product->price == null ? $reqdesigner->price : $product->price;
        }
        else
        {
            $price = $model == 'App\\Models\\ReqDesigner' ?  ($product->tarahiRegister->total*$companies->design_damage) : $product->price;
        }

        $count = 1;
        $discount = $product->discount && $product->discount->expired > new Carbon ? ($product->price*$product->discount->percent/100) : 0 ;
        $total = ($price * $count)- $discount;
        $tax = $product->user->roles->max('id') > 2 ? $total * $companies->tax : 0 ;
        $now = [
            'product'=> $product,
            'count' => $count,
            'discount' => $discount,
            'total' =>$total,
            'tax'=>$tax,
            'col' => $total+$tax,
            'model'=> $model,
            'request'=> $request->request,
        ];
        array_push($this->products,$now);

        if($this->coupon > ($this->price + $price +$this->discount))
        {
            $this->price += $price;
            $this->count +=$count;
            $this->total += $total;

        }
        else
        {
            $this->price += $price;
            $this->count += $count;
            $this->discount += $discount;
            $this->total += $total;
            $this->tax += $tax;
            $this->col += $total+$tax;
            $this->payment += count($product->installments) > 0 ? ($total/$product->installments[0]->count)+($tax/$product->installments[0]->count) : $total+$tax;
            $this->balance += count($product->installments) > 0 ? ($total/$product->installments[0]->count)+($tax/$product->installments[0]->count) : 0;
            // $this->request = $request->request;
        }
    }

    public function removeFormCart($product,$oldCart,$cart)
    {

        $price = $this->products[$product->id]['total']/$this->products[$product->id]['count'];
        $count = $this->products[$product->id]['count'];
        $discount = $this->products[$product->id]['discount'];
        $total = (($price*$count)-$discount);
        $tax = $this->products[$product->id]['tax'] ;
        $installments = count($this->products[$product->id]['product']->installments);

        if(!array_key_exists($product->id,$this->products))
        return false;

        $this->price -= $this->products[$product->id]['total']/$this->products[$product->id]['count'];
        $this->count -= $this->products[$product->id]['count'];
        $this->discount -= $discount;
        $this->total -= $total;
        $this->tax -= $tax;
        $this->col -= $this->products[$product->id]['col'];
        $this->payment -=   $installments > 0 ? $this->products[$product->id]['col'] / $this->products[$product->id]['product']->installments[0]->count: $this->products[$product->id]['col'];
        $this->balance -=   $this->balance > 0 &&  $installments > 0 ? $this->products[$product->id]['col'] / $this->products[$product->id]['product']->installments[0]->count:0;
        unset($this->products[$product->id]);

    }
    public function removeFormAllCart($cart)
    {

        foreach ($this->products as $key => $value)
        {
            $product = $value['product'];
            $count = $value['count'];
            $discount = $value['discount'];
            $total = (($product->price*$count)-$discount);

            if($value['product']->discount)
            {
                $discount = ($value['product']->price*$value['product']->discount->percent/100);

                if(!array_key_exists($key,$this->products))
                    return false;
                unset($this->products[$key]);
            }
            else
            {
                $discount = 0;
                if(!array_key_exists($key,$this->products))
                    return false;
                unset($this->products[$key]);
            }

        }

        $this->price = 0;
        $this->count = 0;
        $this->discount = 0;
        $this->total = 0;
        $this->tax = 0;
        $this->col = 0;
        $this->payment = 0;
        $this->balance = 0;
    }
    public function updateCart($product,$count,$cart)
    {

        if($product->discount && $product->discount->expired > new Carbon)
        {

            if(!array_key_exists($product->id,$this->products))
            return false;
            $oldcount = $this->products[$product->id]['count'];
            $products = $this->products[$product->id];
            $discount = $products['discount'];

            $this->products[$product->id]=[
                'product'=> $product,
                'count' => $count,
                'discount' => ($product->price*$count*$product->discount->percent/100) ,
            ];

            if(($this->price-(($product->price*$oldcount)) + ($product->price*$count)-$this->discount) < $this->coupon)
            {
                $this->price -= $product->price*$oldcount;
                $this->count -= $oldcount;
                $this->discount -= ($product->price*$oldcount*$product->discount->percent/100);
                $this->total -= $product->price*$oldcount-($product->price*$oldcount*$product->discount->percent/100);

                $this->price += $product->price*$count;
                $this->count += $count;
                $this->discount += ($product->price*$count*$product->discount->percent/100);
                $this->total = 0;
            }
            else
            {
                $this->price -= $product->price*$oldcount;
                $this->count -= $oldcount;
                $this->discount -= $discount;
                $this->total -= $product->price*$oldcount-$discount;

                $this->price += $product->price*$count;
                $this->count += $count;
                $this->discount += ($product->price*$count*$product->discount->percent/100);
                $this->total +=  $product->price*$count-($product->price*$count*$product->discount->percent/100);
            }

        }
        else
        {
            if($this->products[$product->id]['discount'])
            {
                if(!array_key_exists($product->id,$this->products))
                return false;
                $oldcount = $this->products[$product->id]['count'];
                $products = $this->products[$product->id];
                $discount = $products['discount'];

                $this->products[$product->id]=[
                    'product'=> $product,
                    'count' => $count,
                    'discount' => $discount-$discount ,
                ];
                if(($this->price-($product->price*$oldcount) + ($product->price*$count)-$this->discount) < $this->coupon)
                {
                    $this->price -= $product->price*$oldcount;
                    $this->count -= $oldcount;
                    $this->discount -= 0;
                    $this->total -= $product->price*$oldcount;

                    $this->price += $product->price*$count;
                    $this->count += $count;
                    $this->discount += 0;
                    $this->total = 0;
                }
                else
                {
                    $this->price -= $product->price*$oldcount;
                    $this->count -= $oldcount;
                    $this->discount -= $discount;
                    $this->total -= $product->price*$oldcount-$discount;

                    $this->price += $product->price*$count;
                    $this->count += $count;
                    $this->discount += 0;
                    $this->total +=  $product->price*$count;
                }
            }
            else
            {
                if(!array_key_exists($product->id,$this->products))
                return false;
                $oldcount = $this->products[$product->id]['count'];
                $products = $this->products[$product->id];
                $discount = $products['discount'];

                $this->products[$product->id]=[
                    'product'=> $product,
                    'count' => $count,
                    'discount' => $discount ,//+ round($this->coupon/2), //$count,count($this->products)
                ];
                if(($this->price-($product->price*$oldcount) + ($product->price*$count)-$this->discount) < $this->coupon)
                {
                    $this->price -= $product->price*$oldcount;
                    $this->count -= $oldcount;
                    $this->discount -= 0;
                    $this->total -= $product->price*$oldcount;

                    $this->price += $product->price*$count;
                    $this->count += $count;
                    $this->discount += 0;
                    $this->total = 0;
                }
                else
                {
                    $this->price -= $product->price*$oldcount;
                    $this->count -= $oldcount;
                    $this->discount -= 0;
                    $this->total -= $product->price*$oldcount;

                    $this->price += $product->price*$count;
                    $this->count += $count;
                    $this->discount += 0;
                    $this->total +=  $product->price*$count;
                }
            }
        }
    }

    public function updateCoupon($cart,$codes)
    {

        if(is_null($codes))
        return false;
        if($codes->price > ($this->price+$this->discount))
        {
            $this->coupon = $codes->price;
            $this->total = 0;

        }
        else
        {
            $this->coupon = $codes->price;
            $this->total = ($this->price-$this->discount-$this->coupon)+$this->comison+$this->tax+$this->complications;

        }
    }
}
