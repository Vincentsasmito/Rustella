@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Flower</h1>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif

        <form action="{{ route('flowers.update', $flower) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $flower->name }}" required>
            </div>

            <div class="mb-3">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ $flower->quantity }}">
            </div>

            <div class="mb-3">
                <label>Price</label>
                <input type="number" name="price" step="0.01" class="form-control" value="{{ $flower->price }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
