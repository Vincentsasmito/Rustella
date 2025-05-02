<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //Show All Products
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }
    //Get Catalogue
    public function customerCatalogue()
    {
        $products = Product::all();
        return view('customerviews.catalogue', compact('products'));
    }


    //Show Add Product Form
    public function create()
    {
        return view('products.create');
    }

    //Store Product
    public function store(Request $request)
    {
        $validInput = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'photo'       => 'mimes:jpg,bmp,png,jpeg'
        ]);

        //save photo, get url
        if ($file = $request->file('photo')) {
            $file_path = public_path('images');
            $file_input = date('YmdHis') . '-' . $file->getClientOriginalName();
            $file->move($file_path, $file_input);
            $validInput['image_url'] = $file_input;
        }

        Product::create($validInput);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    //Show Product
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    //Show Edit Form
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    //Update Product
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image_url'   => 'nullable|url|max:255',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    //Delete Product
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
