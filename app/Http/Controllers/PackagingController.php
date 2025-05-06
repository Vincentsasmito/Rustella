<?php

namespace App\Http\Controllers;

use App\Models\Packaging;
use Illuminate\Http\Request;

class PackagingController extends Controller
{
    //Show All flowers
    public function index()
    {
        $packaging = Packaging::all();
        return view('packagings.index', compact('packaging'));
    }

    //Show Add Product Form
    public function create()
    {
        return view('packagings.create');
    }

    //Store Product
    public function store(Request $request)
    {
        $validInput = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
        ]);

        

        Packaging::create($validInput);

        return redirect()->route('packaging.index')
            ->with('success', 'Flower created successfully.');
    }

    //Show Product
    public function show(Packaging $packagings)
    {
        return view('packagings.show', compact('packaging'));
    }

    //Show Edit Form
    public function edit(Packaging $packagings)
    {
        return view('packagings.edit', compact('packagings'));
    }

    //Update packagings
    public function update(Request $request, Packaging $packagings)
    {
        $validInput = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
        ]);
    

        $packagings->update($validInput);

        return redirect()->route('packagings.index')
            ->with('success', 'Product updated successfully.');
    }

    //Delete Flower
    public function destroy(Packaging $packagings)
    {
        $packagings->delete();

        return redirect()->route('packagings.index')
            ->with('success', 'Product deleted successfully.');
    }
}
