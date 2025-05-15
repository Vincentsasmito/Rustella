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
        // 1) figure out the new cart quantities, *including* this add
        $qty  = $request->input('qty', 1);
        $cart = session('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + $qty;

        // 2) eager‐load every product in that cart
        $products = Product::with('flowerProducts.flower')
            ->whereIn('id', array_keys($cart))
            ->get();

        // 3) build a usage map: flower_id => total stems required
        $usage = [];
        foreach ($products as $prod) {
            $count = $cart[$prod->id];
            foreach ($prod->flowerProducts as $fp) {
                $fid = $fp->flower->id;
                $usage[$fid] = ($usage[$fid] ?? 0) + ($fp->quantity * $count);
            }
        }

        // 4) check *every* flower’s total usage against its stock
        $shortages = [];
        foreach ($products->flatMap->flowerProducts as $fp) {
            $flower = $fp->flower;
            $needed = $usage[$flower->id] ?? 0;
            if ($needed > $flower->quantity) {
                $shortages[] = "{$flower->name}: needed {$needed}, available {$flower->quantity}";
            }
        }

        if (! empty($shortages)) {
            $msg = 'Insufficient stock for: ' . implode('; ', array_unique($shortages));
            if ($request->wantsJson()) {
                return response()->json(['error' => $msg], 400);
            }
            return back()->withErrors(['stock' => $msg]);
        }

        // 5) all good → write back the new cart & respond
        session(['cart' => $cart]);
        $totalCount = array_sum($cart);
        if ($request->wantsJson()) {
            return response()->json(['count' => $totalCount]);
        }
        return redirect()->back();
    }

    public function update(Request $request, Product $product)
    {
        // 1) validate incoming quantity
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $newQty = $data['quantity'];

        // 2) load current cart & apply this change
        $cart = session('cart', []);
        if (! array_key_exists($product->id, $cart)) {
            // nothing to update if product not in cart
            abort(404);
        }
        $cart[$product->id] = $newQty;

        // 3) eager-load all cart products with their flower recipes
        $products = Product::with('flowerProducts.flower')
            ->whereIn('id', array_keys($cart))
            ->get();

        // 4) build up total stems needed per flower across the entire cart
        $usage = [];  // flower_id => total required stems
        foreach ($products as $prod) {
            $count = $cart[$prod->id];
            foreach ($prod->flowerProducts as $fp) {
                $fid = $fp->flower->id;
                $usage[$fid] = ($usage[$fid] ?? 0) + ($fp->quantity * $count);
            }
        }

        // 5) check each flower’s total usage vs its stock
        foreach ($products->flatMap->flowerProducts as $fp) {
            $flower = $fp->flower;
            $needed = $usage[$flower->id];
            if ($needed > $flower->quantity) {
                $msg = "Not enough stock for “{$flower->name}.” You need {$needed} stems but only {$flower->quantity} available.";

                if ($request->wantsJson()) {
                    return response()->json(['error' => $msg], 400);
                }
                return back()->withErrors(['stock' => $msg]);
            }
        }

        // 6) all good → write back and calculate totals
        session(['cart' => $cart]);
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
