<?php

namespace App\Http\Controllers;

//Imports

use App\Models\Order;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::all();
        return view('suggestions.index', compact('suggestions'));
    }

    public function create()
    {
        return view('suggestions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:site,product',
            'product_id' => 'required_if:type,product|exists:products,id',
            'rating'     => 'required_if:type,product|integer|between:1,5',
            'message'    => 'required|string',
        ]);

        $data = $request->only(['type', 'message']);

        if ($request->type === 'product') {
            // 1) must be logged in
            if (! $user = Auth::user()) {
                return back()->withErrors(['You must be logged in to review a product.']);
            }

            $productId = $request->product_id;

            // 2) ensure they purchased it
            $bought = Order::where('user_id', $user->id)
                ->whereHas('orderProducts', fn($q) => $q->where('product_id', $productId))
                ->exists();

            if (! $bought) {
                return back()->withErrors(['You can only review products you’ve purchased.']);
            }

            // 3) ensure they haven’t already reviewed it
            $already = Suggestion::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->exists();

            if ($already) {
                return back()->withErrors(['You’ve already reviewed that product.']);
            }

            // 4) merge the rest
            $data = array_merge($data, [
                'product_id' => $productId,
                'rating'     => $request->rating,
                'user_id'    => $user->id,
            ]);
        }

        Suggestion::create($data);

        return redirect()
            ->route('suggestions.index')
            ->with('success', 'Thanks for your feedback!');
    }
    public function destroy(Suggestion $suggestion)
    {
        $suggestion->delete();
        return redirect()->route('suggestions.index')->with('success', 'Suggestion deleted successfully.');
    }
}
