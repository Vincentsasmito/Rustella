<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Discount;
use App\Models\Flower;
use App\Models\FlowerProduct;
use App\Models\Packaging;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        // 3) Build detailedCart exactly as before
        $detailedCart = [];
        foreach ($cart as $productId => $quantity) {
            if (isset($products[$productId])) {
                $detailedCart[] = [
                    'product'  => $products[$productId],
                    'quantity' => $quantity,
                ];
            }
        }

        //Get Cart total items
        $cartCount = array_sum(array_column($detailedCart, 'quantity'));

        // 4) Pull in delivery options
        $deliveries = Delivery::all();

        $user = Auth::user();

        // 5) Pass to the view
        return view('customerviews.cart', [
            'cart'       => $detailedCart,
            'deliveries' => $deliveries,
            'user'       => $user,
            'cartCount'  => $cartCount,
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

        //Get the current date, check if the discount's start date and end date are valid
        $currentDate = now();
        if (
            // if we have a start_date and we’re before it…
            ($discount->start_date && $currentDate->lt($discount->start_date))
            // …or if we have an end_date and we’re after it
            || ($discount->end_date   && $currentDate->gt($discount->end_date))
        ) {
            return response()->json([
                'message' => 'Discount code period is not currently valid.'
            ], 422);
        }

        if ($discount->max_usage > 0 && $discount->usage_counter >= $discount->max_usage) {
            return response()->json([
                'message' => 'Sorry, the discount limit has been reached.'
            ], 422);
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
            'discount_id'   => $discount->id,
            'discount_amount' => (int) $discountAmt,
            'message' => 'Discount applied successfully.'
        ]);
    }

    public function storeOrder(Request $request)
    {
        // 1) Validate
        $validInput = $request->validate([
            'sender_email'      => 'required|email|max:255',
            'sender_phone'      => 'required|string|max:30',
            'sender_note'       => 'nullable|string|max:300',
            'recipient_name'    => 'required|string|max:60',
            'recipient_phone'   => 'required|string|max:30',
            'recipient_address' => 'required|string|max:100',
            'deliveries_id'     => 'required|exists:deliveries,id',
            'delivery_time'     => 'required|date',
            'discount_id'       => 'nullable|exists:discounts,id',
            'photo'             => 'mimes:jpg,bmp,png,jpeg|max:3072', // 3MB
        ]);




        $cart = session('cart', []);

        $flowerRequirements = []; // flower_id => total quantity

        //Get the flowers needed
        foreach ($cart as $productId => $qty) {
            $flowerUsed = FlowerProduct::where('product_id', $productId)->get();

            foreach ($flowerUsed as $usage) {
                $totalUsed = $usage->quantity * $qty;
                $flowerRequirements[$usage->flower_id] =
                    ($flowerRequirements[$usage->flower_id] ?? 0) + $totalUsed;
            }
        }

        //Check if stock is available
        foreach ($flowerRequirements as $flowerId => $requiredQty) {
            $flower = Flower::find($flowerId);

            if (!$flower || $flower->quantity < $requiredQty) {
                return redirect()->back()
                    ->with('error', 'Not enough stock for flower: ' . ($flower->name ?? 'Unknown'));
            }
        }

        $username = Str::slug(Auth::user()->name);
        //save photo, get url
        if ($file = $request->file('photo')) {
            $file_path = public_path('payment');
            $file_input = date('YmdHis') . '-' . $username . '-' . $file->getClientOriginalName();
            $file->move($file_path, $file_input);
            $validInput['payment_url'] = $file_input;
        }

        DB::transaction(function () use ($validInput, $cart) {
            //Create the order
            $validInput['user_id'] = Auth::id();
            $validInput['cost']    = 0;
            $validInput['progress'] = "Payment Pending";
            $validInput['delivery_fee'] = Delivery::where('id', $validInput['deliveries_id'])
                ->value('fee') ?? 0;
            $order = Order::create($validInput);

            //Increase Discount Counter
            if ($order->discount_id) {
                Discount::where('id', $order->discount_id)->increment('usage_counter');
            }

            //Create OrderProduct entries
            foreach ($cart as $productId => $qty) {
                $product = Product::find($productId);

                // OrderProduct entry with selling price
                OrderProduct::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $qty,
                    'price'      => $product->price * $qty, // ✅ customer pays this
                ]);
            }

            //Reduce Flowers
            foreach ($cart as $productId => $qty) {
                $flowerUsed = FlowerProduct::where('product_id', $productId)->get();
                $product = Product::find($productId);

                if ($product->packaging_id) {
                    $packaging = Packaging::find($product->packaging_id);
                    StockTransaction::create([
                        'order_id'    => $order->id,
                        'packaging_id'   => $packaging->id,
                        'packaging_name' => $packaging->name,
                        'type'        => 'PO',
                        'quantity'    => 1,
                        'price'       => $packaging->price,
                    ]);
                }

                foreach ($flowerUsed as $usage) {
                    $flower = Flower::find($usage->flower_id);
                    $totalUsed = $usage->quantity * $qty;
                    // log the transaction for flowers
                    StockTransaction::create([
                        'order_id'    => $order->id,
                        'flower_id'   => $flower->id,
                        'flower_name' => $flower->name,
                        'type'        => 'FO',
                        'quantity'    => $totalUsed,
                        'price'       => $flower->price,
                    ]);
                    $flower->decrement('quantity', $totalUsed);
                }
            }

            //Recalculate & save final cost
            $order->recalculateCost();
        });

        session()->forget('cart');


        return redirect()->route('profile.index')->with('success', 'Order created successfully!');
    }
}
