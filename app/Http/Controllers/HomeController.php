<?php

namespace App\Http\Controllers;

use App\Models\Suggestions;
use Illuminate\Http\Request;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    //In Prod = This function gets the data needed for Homepage (Top 3 Products, Catalogue Products)
    public function index()
    {
        // 1) Load only in-stock products whose recipe is actually satisfiable
        $products = Product::with([
            'packaging',
            // make sure your relation is actually named flowerProducts
            'flowerProducts.flower',
            // likewise, your orderProducts relation
            'orderProducts',
        ])
            ->where('in_stock', true)
            ->whereDoesntHave('flowerProducts.flower', function (Builder $q) {
                // kick out if recipe.qty > flower.stock
                $q->whereColumn('flower_products.quantity', '>', 'flowers.quantity');
            })
            ->get();

        // 2) Build a “sold quantities” array keyed by product_id
        $quantities = $products->mapWithKeys(function (Product $p) {
            // sum up all the pivot-quantities from orderProducts
            $sold = $p->orderProducts->sum('quantity');
            return [$p->id => $sold];
        });

        // 3) Take the top 3 by that same sold value
        $top3product = $products
            ->sortByDesc(fn(Product $p) => $quantities[$p->id])
            ->take(3)
            ->values();  // re-index 0,1,2

        // Group up product by category(Packaging type)
        $groupedProducts = $products->groupBy(fn($product) => strtolower($product->packaging->name));

        return view('customerviews.home', compact('products', 'top3product', 'quantities', 'groupedProducts'));
    }
}
