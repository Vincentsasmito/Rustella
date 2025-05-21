<?php

namespace App\Http\Controllers;

use App\Models\Suggestions;
use Illuminate\Http\Request;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Suggestion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    // In Prod = This function gets the data needed for Homepage (Top 3 Products, Catalogue Products)
    public function index()
    {
        // 1) Load only in-stock products whose recipe is actually satisfiable
        $products = Product::with([
            'packaging',
            // make sure your relation is actually named flowerProducts
            'flowerProducts.flower',
            // only include orderProducts from non-cancelled orders
            'orderProducts' => function ($q) {
                $q->whereHas('order', function (Builder $q) {
                    $q->where('progress', '!=', 'Cancelled');
                });
            },
            'limitedReviews'
        ])
            ->where('in_stock', true)
            ->whereDoesntHave('flowerProducts.flower', function (Builder $q) {
                // kick out if recipe.qty > flower.stock
                $q->whereColumn('flower_products.quantity', '>', 'flowers.quantity');
            })
            ->get();

        // 2) Build a “sold quantities” array keyed by product_id,
        //    now only summing non-cancelled sales
        $quantities = $products->mapWithKeys(function (Product $p) {
            $sold = $p->orderProducts->sum('quantity');
            return [$p->id => $sold];
        });

        // 3) Take the top 3 by that sold value
        $top3product = $products
            ->sortByDesc(fn(Product $p) => $quantities[$p->id])
            ->take(3)
            ->values();  // re-index 0,1,2

        // Group up product by category (packaging type)
        $groupedProducts = $products->groupBy(fn($product) => strtolower($product->packaging->name));

        //get user site-reviews
        $testimonials = Suggestion::where('type', 'product')
            ->where('rating', '>=', 4)
            ->whereRaw('LENGTH(message) <= ?', [75])
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('customerviews.home', compact('products', 'top3product', 'quantities', 'groupedProducts', 'testimonials'));
    }

    public function storeSuggestion(Request $request)
    {
        // 1) Have they already left feedback this session?
        if ($request->session()->has('site_suggestion_sent')) {
            return redirect()->back()->with('error', 'You’ve already given feedback. Thanks!');
        }


        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'rating'  => 'required|integer|between:1,5',
        ]);

        // If user is logged in, assign their ID; otherwise null
        $userId = auth()->check() ? auth()->id() : null;

        // Merge in the extra fields
        $data = array_merge($validated, [
            'user_id' => $userId,
            'type'    => 'site',
        ]);

        try {
            Suggestion::create($data);
        } catch (\Exception $e) {
            // If something goes wrong here, we do NOT set the session flag
            return redirect()->back()
                ->withErrors(['message' => 'Could not save feedback. Please try again.']);
        }


        // 4) Mark the session so they can’t send again
        $request->session()->put('site_suggestion_sent', true);

        return redirect()->back()->with('success', 'Suggestion submitted successfully!');
    }
}
