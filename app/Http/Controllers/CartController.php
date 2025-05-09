<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Discount;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Show the cart
    public function index()
    {
        //Get session Cart, else return empty array
        $cart = session('cart', []);
        $detailedCart = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $detailedCart[] = [
                    'product'  => $product,
                    'quantity' => $quantity,
                ];
            }
        }
        $discounts = Discount::all();
        

        return view('customerviews.cart', ['cart' => $detailedCart, 'discounts' => $discounts]);
    }

    // Add an item (qty = 1 by default)
    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + 1;

        //stores cart to session.
        session(['cart' => $cart]);

        // If AJAX, return a JSON.
        if($request->ajax()){
            return response()->json([
                'product_id' => $product->id,
                'quantity'   => $cart[$product->id],
            ]);
        }
        return back()->with('success', 'Added to cart');
    }

    // Update quantity
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id] = (int) $request->quantity;
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Cart updated');
    }

    // Remove an item
    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Item removed');
    }

}
