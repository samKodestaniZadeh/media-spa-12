<?php
namespace App\Http\Utilities;

use App\Models\Product;

class ProductCreate
{
    public static function all($request)
    {
        $request->validate([
            'name'=> 'required',
            'name_en'=> 'required',
            'group' => 'required',
            'type' => 'required',
            'category' => 'required',
            'text'=> 'required|min:100',
            'demo_link'=> 'required|URL',
            'version'=> 'required',
            'file'=> 'required',
            'image'=> 'required',

        ]);

        if ($request->id) {

            $products = Product::find($request->id)->update([
                'slug'=>$request->group['name'] .'_'. $request->type['name'] .'_'. $request->category['name'] . '_' . $request->name,
                'group' => $request->group['id'],
                'type' => $request->type['id'],
                'category' => $request->category['id'],
                'name'=> $request->name,
                'name_en'=>$request->name_en,
                'text'=> $request->text,
                'demo_link'=>$request->demo_link,
                'price'=>$request->price,
                'version'=>$request->version,
                'status'=> $request->status,
                'user_id' => auth()->user()->id,
            ]);
        }
        else
        {

            $products = Product::create([
                'slug'=>$request->group['name'] .'_'. $request->type['name'] .'_'. $request->category['name'] . '_' . $request->name,
                'group' => $request->group['id'],
                'type' => $request->type['id'],
                'category' => $request->category['id'],
                'name'=> $request->name,
                'name_en'=>$request->name_en,
                'text'=> $request->text,
                'demo_link'=>$request->demo_link,
                'price'=>$request->price,
                'version'=>$request->version,
                'user_id' => auth()->user()->id,
            ]);

        }

        return $products;

    }

    public static function updateAdmin($request)
    {
        $request->validate([
            'name'=> 'required',
            'name_en'=> 'required',
            'group' => 'required',
            'type' => 'required',
            'category' => 'required',
            'text'=> 'required|min:100',
            'demo_link'=> 'required|URL',
            'version'=> 'required',
            'file'=> 'required',
            'image'=> 'required',

        ]);

        $products = Product::find($request->id)->update([
            'slug'=>$request->group['name'] .'_'. $request->type['name'] .'_'. $request->category['name'] . '_' . $request->name,
            'group' => $request->group['id'],
            'type' => $request->type['id'],
            'category' => $request->category['id'],
            'name'=> $request->name,
            'name_en'=>$request->name_en,
            'text'=> $request->text,
            'demo_link'=>$request->demo_link,
            'price'=>$request->price,
            'version'=>$request->version,
            'status'=> $request->status,
            'user_id' => $request->user_id,
        ]);
        return $products;
    }
}
