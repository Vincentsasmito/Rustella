<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderProductController extends Controller
{
    /**
     * Store a new line‐item on the given order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order         $order
     */
    //not being used
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($data['product_id']);

        $orderProduct = OrderProduct::firstOrNew([
            'order_id'   => $order->id,
            'product_id' => $data['product_id'],
        ]);

        $orderProduct->quantity = ($orderProduct->exists ? $orderProduct->quantity : 0) + $data['quantity'];
        $orderProduct->price = $orderProduct->quantity * $product->price;
        $orderProduct->save();
        $order = Order::with('orderProducts.product.packaging')->find($orderProduct->order_id);
        $order->recalculateCost();

        return back()->with('success', 'Product added to order.');
    }
    /**
     * Update the quantity of a specific line‐item.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\Order          $order
     * @param  \App\Models\OrderProduct   $orderProduct
     */
    public function update(Request $request, Order $order, OrderProduct $orderProduct)
    {
        // Ensure the pivot belongs to this order
        if ($orderProduct->order_id !== $order->id) {
            abort(404);
        }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $orderProduct->update(['quantity' => $data['quantity']]);

        return back()->with('success', 'Order item quantity updated.');
    }

    /**
     * Remove a product from an order.
     *
     * @param  \App\Models\Order        $order
     * @param  \App\Models\OrderProduct $orderProduct
     */
    public function destroy(Order $order, OrderProduct $orderProduct)
    {
        if ($orderProduct->order_id !== $order->id) {
            abort(404);
        }

        $orderProduct->delete();

        return back()->with('success', 'Product removed from order.');
    }
}
