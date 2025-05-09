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
        //Eager-load only in stock products (admin and system check)
        $products = Product::with('packaging')
            ->where('in_stock', true)
            ->whereDoesntHave('flowerProducts.flower', function (Builder $q) {
                // $q is a builder on the flowers table,
                // direct call because relationship defined in Product model
                $q->whereColumn('flower_products.quantity', '>', 'flowers.quantity');
            })
            ->get();
        $top3productData = DB::table('order_products')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(3)
            ->get();

        $productIDs = $top3productData->pluck('product_id');
        $quantities = $top3productData->pluck('total_quantity', 'product_id');

        // fn($p) is shorthand function to sort through the collection.
        $top3product = $products->whereIn('id', $productIDs)
            ->sortBy(fn($p) => $productIDs->search($p->id));

        // Group up product by category(Packaging type)
        $groupedProducts = $products->groupBy(fn($product) => strtolower($product->packaging->name));

        return view('customerviews.home', compact('products', 'top3product', 'quantities', 'groupedProducts'));
    }
}
