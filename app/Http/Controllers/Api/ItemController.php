<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function store(Request $request)
    {
        $item = new Item();
        $item->name = $request->name;
        $item->save();
        return response()->json([
            'message' => 'Item saved successfully',
            'data' => $item,
        ]);
    }

    public function show($id)
    {
        // Find the item by ID
        $item = Item::with('products')->find($id);

        if (!$item) {

            return response()->json(['message' => 'Item not found'], 404);
        }


        return response()->json($item);
    }
}
