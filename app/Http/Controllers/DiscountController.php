<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    // 1. List all discounts (with associated orders)
    public function index()
    {
        //Eager-Loading -> gets the association sekaligus, better performance
        $discounts = Discount::with('order')->get();
        return view('discounts.index', compact('discounts'));
    }

    // 2. Show 'create' form
    public function create()
    {
        return view('discounts.create');
    }

    // 3. Save a new discount
    public function store(Request $request)
    {
        $validInput = $request->validate([
            'code'          => 'required|string|max:20|unique:discounts,code',
            'percent'       => 'required|integer|min:0|max:100',
            'max_value'     => 'required|integer|min:0',
            'min_purchase'  => 'required|integer|min:0',
        ]);
        Discount::create($validInput);
        return redirect()->route('discounts.index')->with('success', 'Discount created successfully.');
    }

    // 4. Load a discount's orders.
    public function show(Discount $discount)
    {
        $discount->load('order');
        return view('discounts.show', compact('discount'));
    }

    // 5. Show edit discount form
    public function edit(Discount $discount)
    {
        return view('discounts.edit', compact('discount'));
    }

    // 6. Update an existing discount
    public function update(Request $request, Discount $discount)
    {
        $data = $request->validate([
            'code'          => 'required|string|max:20|unique:discounts,code,' . $discount->id,
            'percent'       => 'required|integer|min:0|max:100',
            'max_value'     => 'required|integer|min:0',
            'min_purchase'  => 'required|integer|min:0',
        ]);

        $discount->update($data);

        return redirect()->route('discounts.index')
                         ->with('success', 'Discount updated successfully.');
    }

    // 7. Delete a discount
    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect()->route('discounts.index')
                         ->with('success', 'Discount deleted successfully.');
    }

}
