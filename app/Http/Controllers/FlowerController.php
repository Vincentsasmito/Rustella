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

    // Delete Flower
    public function destroy(Flower $flower)
    {
        $flower->delete();

        return redirect()->route('flowers.index')
            ->with('success', 'Flower deleted successfully.');
    }
}
