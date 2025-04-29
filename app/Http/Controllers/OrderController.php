<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 1. List all orders (with associated discounts)
    public function index()
    {
        $orders = Order::with(['discount','orderProducts.product'])->get();
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

    // 3. Save a new order
    public function store(Request $request)
    {
            $validInput = $request->validate([
            'sender_email'        => 'required|email',
            'sender_phone'        => 'required|string',
            'sender_note'         => 'nullable|string',
            'recipient_name'      => 'required|string',
            'recipient_phone'     => 'required|string',
            'recipient_address'   => 'required|string',
            'recipient_city'           => 'required|string',
            'delivery_time'       => 'required|date',
            'delivery_details'    => 'nullable|string',
            'progress'            => 'required|string',
            'discount_id'         => 'nullable|exists:discounts,id',
        ]);

        Order::create($validInput);

        return redirect()->route('orders.index')
                         ->with('success', 'Order created successfully.');
    }

    // 4. Show order details
    public function show(Order $order)
    {$orders = Order::with(['discount','orderProducts.product'])->get();
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
                'delivery_details'    => 'nullable|string',
                'progress'            => 'required|string',
                'discount_id'         => 'nullable|exists:discounts,id',
        ]);

        $order->update($validInput);

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
