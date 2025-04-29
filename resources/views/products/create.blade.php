@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Product</h1>

    <form action="{{ route('products.store') }}" method="POST">
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
            <label>Image URL</label>
            <input type="url" name="image_url" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Create Product</button>
    </form>
</div>
@endsection
