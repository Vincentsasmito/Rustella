<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Discount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    // Show the cart
    public function index()
    {
        // 1) Grab the raw session cart [ product_id => quantity ]
        $cart = session('cart', []);
        // 2) Eager‐load all Products (with packaging & flowerProducts→flower) in one go
        $products = Product::with(['packaging', 'flowerProducts.flower'])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        // 3) Build your detailedCart exactly as before
        $detailedCart = [];
        foreach ($cart as $productId => $quantity) {
            if (isset($products[$productId])) {
                $detailedCart[] = [
                    'product'  => $products[$productId],
                    'quantity' => $quantity,
                ];
            }
        }

        // 4) Pull in any discounts or delivery options
        $deliveries = Delivery::all();

        // 5) Pass to the view
        return view('customerviews.cart', [
            'cart'       => $detailedCart,
            'deliveries' => $deliveries,
        ]);
    }

    // Add an item (qty = 1 by default)
    public function add(Request $request, Product $product)
    {
        $qty = $request->input('qty', 1);

        // merge into session cart:
        $cart = session('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + $qty;
        session(['cart' => $cart]);

        $totalCount = array_sum($cart);

        // if the client expects JSON, send back the new count
        if ($request->wantsJson()) {
            return response()->json(['count' => $totalCount]);
        }

        // otherwise fallback to normal redirect
        return redirect()->back();
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',   // ← validate "quantity"
        ]);

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id] = $data['quantity'];  // ← read $request->quantity
            session(['cart' => $cart]);
        }

        $lineTotal  = $product->price * $cart[$product->id];
        $totalCount = array_sum($cart);

        if ($request->wantsJson()) {
            return response()->json([
                'count' => $totalCount,
                'line'  => $lineTotal,
            ]);
        }

        return back()->with('success', 'Cart updated');
    }

    public function remove(Request $request, Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        $totalCount = array_sum($cart);

        if ($request->wantsJson()) {
            return response()->json(['count' => $totalCount]);
        }

        return back()->with('success', 'Item removed');
    }

    public function discountIfExist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'     => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid input.',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $discount = Discount::where('code', $data['code'])->first();

        if (!$discount) {
            return response()->json(['message' => 'Invalid discount code.'], 422);
        }

        if ($data['subtotal'] < $discount->min_purchase) {
            return response()->json([
                'message' => 'Minimum purchase for this code is Rp ' . number_format($discount->min_purchase, 0, ',', '.')
            ], 422);
        }

        $discountAmt = $data['subtotal'] * $discount->percent / 100;

        if ($discount->max_value && $discountAmt > $discount->max_value) {
            $discountAmt = $discount->max_value;
        }

        return response()->json([
            'discount_amount' => (int) $discountAmt,
            'message' => 'Discount applied successfully.'
        ]);
    }
}
