<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Discount;
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

        // 1) Create order with cost = 0 placeholder
        $validInput['user_id'] = Auth::id();
        $validInput['cost']    = 0;
        $order = Order::create($validInput);

        // 2) Create OrderProduct entries
        $cart = session()->get('cart', []); // [ product_id => quantity, ... ]
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

        // (optional) clear cart
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
}

