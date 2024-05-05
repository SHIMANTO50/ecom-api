<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request){

        $product=new Product();
        $product->item_id=$request->item_id;
        $product->name=$request->name;
        $product->image=$request->image;
        $product->price=$request->price;

        $product->save();
        return response()->json([
            'message' => 'Product saved successfully',
            'data' => $product,
        ]);

    }
}
