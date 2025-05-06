@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create New Product</h1>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Price</label>
                <input type="number" name="price" class="form-control" step="0.01" required>
            </div>

            <div class="mb-3">
                <label>Upload Photo</label>
                <input type="file" name="photo" class="form-control">
            </div>

            <!-- Dynamic Packaging Selection -->
            <div class="mb-3">
                <label>Product Type</label>
                <select name="packaging_id" class="form-control" required>
                    <option value="">Select a type</option>
                    @foreach ($packagings as $packaging)
                        <option value="{{ $packaging->id }}">{{ $packaging->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dynamic Flower Selection -->
            <div class="mb-3">
                <label>Flowers Used</label>
                @foreach ($flowers as $flower)
                    <div class="d-flex align-items-center mb-2">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" name="flowers[{{ $flower->id }}]" id="flower_{{ $flower->id }}">
                            <label class="form-check-label" for="flower_{{ $flower->id }}">{{ $flower->name }}</label>
                        </div>
                        <input type="number" name="quantities[{{ $flower->id }}]" placeholder="Quantity" class="form-control w-25" min="1" step="1">
                    </div>
                @endforeach
            </div>

            <!-- In Stock Toggle -->
            <div class="mb-3">
                <label class="form-label d-block">Availability</label>
                <div class="d-flex align-items-center">
                    <span class="me-2">Out of Stock</span>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" name="in_stock" id="in_stock" value="1" checked>
                    </div>
                    <span class="ms-2">In Stock</span>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Create Product</button>
        </form>
    </div>
@endsection
