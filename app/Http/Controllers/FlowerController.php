<?php

namespace App\Http\Controllers;

use App\Models\Flower;
use Illuminate\Http\Request;

class FlowerController extends Controller
{
    // Show All flowers
    public function index()
    {
        $flowers = Flower::all();
        return view('flowers.index', compact('flowers'));
    }

    // Show Add Flower Form
    public function create()
    {
        return view('flowers.create');
    }

    // Store Flower
    public function store(Request $request)
    {
        $validInput = $request->validate([
            'name'     => 'required|string|max:255',
            'quantity' => 'nullable|numeric',
            'price'    => 'required|numeric|min:0',
        ]);
        $validInput['price'] = $request->price / $request->quantity;

        Flower::create($validInput);

        return redirect()->route('flowers.index')
            ->with('success', 'Flower created successfully.');
    }

    // Show Single Flower
    public function show(Flower $flower)
    {
        return view('flowers.show', compact('flower'));
    }

    // Show Edit Form
    public function edit(Flower $flower)
    {
        return view('flowers.edit', compact('flower'));
    }

    // Update Flower
    public function update(Request $request, Flower $flower)
    {
        $validInput = $request->validate([
            'name'     => 'required|string|max:255',
            'quantity' => 'nullable|numeric',
            'price'    => 'required|numeric|min:0',
        ]);

        $flower->update($validInput);

        return redirect()->route('flowers.index')
            ->with('success', 'Flower updated successfully.');
    }

    public function stock()
    {
        $flowers = Flower::all();
        return view('flowers.stock', compact('flowers'));
    }

    // Add Flower Stock
    public function stockupdate(Request $request, Flower $flower)
    {
        // 1. Validate inputs
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price'    => 'required|numeric|min:0',
        ]);

        $addedQty       = $data['quantity'];
        $addedTotalCost = $data['price'];

        // 2. Compute totals
        $currentQty   = $flower->quantity;
        $currentPrice = $flower->price;
        $currentTotalCost = $currentQty * $currentPrice;

        $newQty       = $currentQty + $addedQty;
        $newTotalCost = $currentTotalCost + $addedTotalCost;

        // 3. Weighted average price
        $newAvgPrice = $newTotalCost / $newQty;

        // 4. Update the model
        $flower->update([
            'quantity' => $newQty,
            'price'    => round($newAvgPrice, 2),
        ]);

        // 5. Redirect back
        return redirect()
            ->route('flowers.stock')
            ->with('success', 'Stock updated successfully!');
    }


    // Delete Flower
    public function destroy(Flower $flower)
    {
        $flower->delete();

        return redirect()->route('flowers.index')
            ->with('success', 'Flower deleted successfully.');
    }
}
