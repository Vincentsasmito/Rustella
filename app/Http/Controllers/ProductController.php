<?php

namespace App\Http\Controllers;

use App\Models\Flower;
use App\Models\FlowerProduct;
use App\Models\Packaging;
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
        $flowers = Flower::all(); // for flower selection
        $packagings = Packaging::all(); // get product types dynamically
        return view('products.create', compact('flowers', 'packagings'));
    }

    //Store Product
    public function store(Request $request)
    {
        $validInput = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'photo'       => 'mimes:jpg,bmp,png,jpeg',
            'packaging_id'=> 'required|numeric',
        ]);

        //save photo, get url
        if ($file = $request->file('photo')) {
            $file_path = public_path('images');
            $file_input = date('YmdHis') . '-' . $file->getClientOriginalName();
            $file->move($file_path, $file_input);
            $validInput['image_url'] = $file_input;
        }

        $product = Product::create($validInput);
        if ($request->has('flowers')) {
            foreach ($request->flowers as $flowerId => $checked) {
                if ($checked && isset($request->quantities[$flowerId])) {
                    FlowerProduct::create([
                        'product_id' => $product->id,
                        'flower_id'  => $flowerId,
                        'quantity'   => $request->quantities[$flowerId],
                    ]);
                }
            }
        }
        

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
        $validInput = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'photo'       => 'mimes:jpg,bmp,png,jpeg',
            'packaging_id'=> 'required|integer',
        ]);
        //save photo, get url
        if ($file = $request->file('photo')) {
            $file_path = public_path('images');
            $file_input = date('YmdHis') . '-' . $file->getClientOriginalName();
            $file->move($file_path, $file_input);
            $validInput['image_url'] = $file_input;
        }

        $product->update($validInput);

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
