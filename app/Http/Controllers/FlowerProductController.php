<?php

namespace App\Http\Controllers;

use App\Models\Flower;
use App\Models\Product;
use App\Models\FlowerProduct;
use Illuminate\Http\Request;

class FlowerProductController extends Controller
{
    /**
     * Store flower composition for a product.
     */
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'flower_id' => 'required|exists:flowers,id',
            'quantity'  => 'required|integer|min:1',
        ]);

        $flower = Flower::findOrFail($data['flower_id']);

        $flowerProduct = FlowerProduct::firstOrNew([
            'product_id' => $product->id,
            'flower_id'  => $data['flower_id'],
        ]);

        $flowerProduct->quantity = $data['quantity'];
        $flowerProduct->save();

        return back()->with('success', 'Flower added to product composition.');
    }

    /**
     * Update quantity of a flower in a product.
     */
    public function update(Request $request, Product $product, FlowerProduct $flowerProduct)
    {
        if ($flowerProduct->product_id !== $product->id) {
            abort(404);
        }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $flowerProduct->update(['quantity' => $data['quantity']]);

        return back()->with('success', 'Flower quantity in product updated.');
    }

    /**
     * Remove a flower from a product's composition.
     */
    public function destroy(Product $product, FlowerProduct $flowerProduct)
    {
        if ($flowerProduct->product_id !== $product->id) {
            abort(404);
        }

        $flowerProduct->delete();

        return back()->with('success', 'Flower removed from product composition.');
    }
}
