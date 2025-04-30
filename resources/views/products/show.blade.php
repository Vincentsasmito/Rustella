@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Product Details</h1>

    <div class="mb-3">
        <strong>Name:</strong> {{ $product->name }}
    </div>

    <div class="mb-3">
        <strong>Description:</strong> {{ $product->description }}
    </div>

    <div class="mb-3">
        <strong>Price:</strong> ${{ number_format($product->price, 2) }}
    </div>

    <div class="mb-3">
        <strong>Image:</strong> 
        <img src="{{asset('images/' . @$product->image_url)}}" style="width: 200px; height: 200px;"/>
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
