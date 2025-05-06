@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Flower Details</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $flower->name }}</h5>
                <p class="card-text"><strong>Quantity:</strong> {{ $flower->quantity }}</p>
                <p class="card-text"><strong>Price:</strong> Rp {{ number_format($flower->price, 0, ',', '.') }}</p>
            </div>
        </div>

        <a href="{{ route('flowers.index') }}" class="btn btn-secondary mt-3">Back to List</a>
    </div>
@endsection
