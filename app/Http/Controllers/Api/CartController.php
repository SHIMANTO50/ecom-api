<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Retrieve session ID from the request headers
        $sessionId = $request->header('X-Session-ID');

        // If session ID is not provided, generate a new one
        if (!$sessionId) {
            $sessionId = bin2hex(random_bytes(10)); // You might want to use a more secure method for generating session IDs
        }

        // Get the product
        $product = Product::findOrFail($request->product_id);

        // Check if the cart item already exists for the provided product and session
        $cartItem = Cart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();

        //dd($cartItem);

        if ($cartItem) {
            // If the cart item already exists, update its quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Add item to cart
            $cartItem = new Cart([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);

            // Associate the cart item with the session ID
            $cartItem->session_id = $sessionId;
            $cartItem->save();
        }

        return response()->json([
            'message' => 'Item added to cart successfully',
            'data' => $cartItem,
        ]);
    }
}
