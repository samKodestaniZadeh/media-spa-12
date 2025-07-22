<?php
namespace App\Http\Utilities;

use App\Models\User;

class Wallet
{

    public static function all(User $users)
    {

        if ($users && $users->deposits)
        {
            $userDeposit = 0;
            foreach ($users->deposits->where('status','=',4) as $key => $value) {

                $userDeposit = $userDeposit + round($value->price) ;
            }
        }
        else
        {
            $userDeposit=null;
        }

        if ($users && $users->payments)
        {
            $userFinancial = 0 ;
            foreach ($users->payments->where('status','=',4) as $key => $value) {

                $userFinancial = $userFinancial + round($value->price) ;
            }
        }
        else
        {
            $userFinancial=null;
        }

        $userPrice = $userDeposit - $userFinancial;

        return $userPrice;
    }
}
