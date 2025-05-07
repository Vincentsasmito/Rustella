<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Discount;
use App\Models\Flower;
use App\Models\FlowerProduct;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // 1. List all orders (with associated discounts)
    public function index()
    {
        $orders = Order::with(['discount', 'orderProducts.product'])->get();
        return view('orders.index', compact('orders'));
    }

    // 2. Show the "create" form (with list of discounts)
    public function create()
    {
        $discounts = Discount::all();
        return view('orders.create', [
            'discounts' => Discount::all(),
            'products' => Product::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validInput = $request->validate([
            'sender_email'      => 'required|email',
            'sender_phone'      => 'required|string',
            'sender_note'       => 'nullable|string',
            'recipient_name'    => 'required|string',
            'recipient_phone'   => 'required|string',
            'recipient_address' => 'required|string',
            'recipient_city'    => 'required|string',
            'delivery_time'     => 'required|date',
            'progress'          => 'required|string',
            'discount_id'       => 'nullable|exists:discounts,id',
        ]);

        // Get the cart items
        $cart = session()->get('cart', []); // [ product_id => quantity, ... ]

        $flowerRequirements = []; // flower_id => total quantity needed

        // 🔍 FIRST LOOP: Check if all required flower stock is available
        foreach ($cart as $productId => $qty) {
            $flowerUsed = FlowerProduct::where('product_id', $productId)->get();

            foreach ($flowerUsed as $usage) {
                $totalUsed = $usage->quantity * $qty;
                $flowerRequirements[$usage->flower_id] =
                    ($flowerRequirements[$usage->flower_id] ?? 0) + $totalUsed;
            }
        }

        // Check if every flower has enough stock
        foreach ($flowerRequirements as $flowerId => $requiredQty) {
            $flower = Flower::find($flowerId);

            if (!$flower || $flower->quantity < $requiredQty) {
                return redirect()->back()
                    ->with('error', 'Not enough stock for flower: ' . ($flower->name ?? 'Unknown'));
            }
        }
        // ✅ SECOND LOOP: Safe to reduce flower stock now
        foreach ($cart as $productId => $qty) {
            $flowerUsed = FlowerProduct::where('product_id', $productId)->get();

            foreach ($flowerUsed as $usage) {
                $flower = Flower::find($usage->flower_id);
                $totalUsed = $usage->quantity * $qty;
                $flower->decrement('quantity', $totalUsed);
            }
        }

        // 1) Create the order with cost = 0 as a placeholder
        $validInput['user_id'] = Auth::id();
        $validInput['cost']    = 0;
        $order = Order::create($validInput);

        // 2) Create OrderProduct entries
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

        // 3) Recalculate full order cost by summing up each OrderProduct->price
        $order->recalculateCost();

        // Clear the cart
        session()->forget('cart');

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }




    // 4. Show order details
    public function show(Order $order)
    {
        $orders = Order::with(['discount', 'orderProducts.product'])->get();
        return view('orders.show', compact('order'));
    }

    // 5. Show the "edit" form
    public function edit(Order $order)
    {
        return view('orders.edit', [
            'order'     => $order,
            'discounts' => Discount::all(),
            'products'  => Product::all(),
        ]);
    }

    // 6. Update the order
    public function update(Request $request, Order $order)
    {
        $validInput = $request->validate([
            'sender_email'        => 'required|email',
            'sender_phone'        => 'required|string',
            'sender_note'         => 'nullable|string',
            'recipient_name'      => 'required|string',
            'recipient_phone'     => 'required|string',
            'recipient_address'   => 'required|string',
            'recipient_city'      => 'required|string',
            'delivery_time'       => 'required|date',
            'progress'            => 'required|string',
            'discount_id'         => 'nullable|exists:discounts,id',
        ]);
        $order->update($validInput);
        $order->recalculateCost();

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    // 7. Delete an order
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    //8. Get All Orders from User
    public function getTransactions()
    {
        //Get User ID
        $user_id = Auth::id();

        if ($user_id) {
            //Get Orders and its associated OP & Discount by this User.
            $orders = Order::with(['orderProducts', 'discount'])->where('user_id', $user_id)
                ->get(['id', 'sender_email', 'sender_phone', 'sender_note', 'recipient_name', 'recipient_phone', 'recipient_address', 'recipient_city', 'discount_id', 'delivery_time', 'progress', 'created_at']);

            // Define an empty array to store total values
            $totals = [];
            //loop thru Orders to calculate total
            foreach ($orders as $order) {
                $total = $order->orderProducts->sum('price'); // Calculate the sum of prices from OrderProducts
                if ($order->discount) {
                    $total -= min($order->discount->max_value, $total * ($order->discount->percent / 100)); // Apply discount
                }

                // Store the calculated total in the totals array, keyed by order ID
                $totals[$order->id] = $total;
            }

            return view('customerviews.transactions', compact('orders', 'totals'));
        }

        return redirect()->route('login')->with('error', 'You must be logged in to view transactions.');
    }
}
