<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function show(Request $request)
    {
        // Retrieve session ID from the request headers
        $sessionId = $request->header('X-Session-ID');

        // Get cart items along with their associated products for the given session ID
        //$cartItems = Cart::with('products')->where('session_id', $sessionId)->get();
        $cartItems = DB::table('carts')->join('products', 'carts.product_id', '=', 'products.id')->select('carts.*', 'products.name', 'products.price')->where('carts.session_id',$sessionId)->get();

        return response()->json([
            'message' => 'Cart items retrieved successfully',
            'data' => $cartItems,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        // Retrieve session ID from the request headers
        $sessionId = $request->header('X-Session-ID');

        // Delete the cart item based on the provided session ID and item ID
        $deleted = DB::table('carts')
                    ->where('session_id', $sessionId)
                    ->where('id', $id)
                    ->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'Cart item deleted successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Cart item not found or could not be deleted',
            ], 404);
        }
    }
}
