<?php
namespace App\Http\Utilities;

use App\Models\Bank;


class BankCreate
{

    public static function all($request)
    {

        $request->validate([
            'userid'=> 'required',
            'bankid'=> 'required','string',
            'accountname'=> 'required','string',
            'accountnumber'=> 'required','numeric',
            'cartnumber'=> 'required','numeric','min:16','max:16',
            'shabanumber'=> 'required','numeric','min:24','max:24',
            'status'=> 'required'
        ]);

        if ($request->id) {

            $banks = Bank::find($request->id)->update([
                'user_id' => $request->userid,
                'bank_id'=> $request->bankid,
                'account_name'=> $request->accountname,
                'account_number'=> $request->accountnumber,
                'cart_number'=> $request->cartnumber,
                'shaba_number'=> $request->shabanumber,
                'status'=> $request->status,
            ]);

        }
        else
        {

            $banks = Bank::create([
                'user_id' => auth()->user()->id,
                'bank_id'=> $request->bankname['id'],
                'account_name'=> $request->accountname,
                'account_number'=> $request->accountnumber,
                'cart_number'=> $request->cartnumber,
                'shaba_number'=> $request->shabanumber,
            ]);
        }
        return $banks;

    }
}
