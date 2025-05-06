@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add New Flower</h1>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif

        <form action="{{ route('flowers.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control">
            </div>

            <div class="mb-3">
                <label>Price</label>
                <input type="number" name="price" step="0.01" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
