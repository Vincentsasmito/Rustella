<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;
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
        

        return view('customerviews.cart', ['cart' => $detailedCart]);
    }

    // Add an item (qty = 1 by default)
    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + 1;

        session(['cart' => $cart]);
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

    // Confirm the purchase
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors('Your cart is empty.');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'status'  => 'pending',
        ]);

        foreach ($cart as $productId => $qty) {
            $product = Product::find($productId);
            if ($product) {
                OrderProduct::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'unit_price' => $product->price,
                ]);
            }
        }

        session()->forget('cart');

        return redirect()->route('orders.show', $order)->with('success', 'Order placed!');
    }
}
