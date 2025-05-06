@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Product</h1>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" step="0.01" required>
        </div>

        <div class="mb-3">
            <label>Upload Photo</label>
            <input type="file" name="photo" class="form-control">
        </div>

        <!-- Product Type Dropdown -->
        <div class="mb-3">
            <label>Product Type</label>
            <select name="type" class="form-control" required>
                <option value="Bouquet" {{ $product->type == 'Bouquet' ? 'selected' : '' }}>Bouquet</option>
                <option value="Bloom box" {{ $product->type == 'Bloom box' ? 'selected' : '' }}>Bloom box</option>
                <option value="Flower Basket" {{ $product->type == 'Flower Basket' ? 'selected' : '' }}>Flower Basket</option>
                <option value="Flower Bag" {{ $product->type == 'Flower Bag' ? 'selected' : '' }}>Flower Bag</option>
            </select>
        </div>

        <!-- In Stock Toggle with Clear Labels -->
        <div class="mb-3">
            <label class="form-label d-block">Availability</label>
            <div class="d-flex align-items-center">
                <span class="me-2">Out of Stock</span>
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" name="in_stock" id="in_stock" value="1" {{ $product->in_stock ? 'checked' : '' }}>
                </div>
                <span class="ms-2">In Stock</span>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
@endsection
