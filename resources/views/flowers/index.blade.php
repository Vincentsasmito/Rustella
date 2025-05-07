@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>All Flowers</h1>

        <a href="{{ route('flowers.create') }}" class="btn btn-primary mb-3">Add New Flower</a>
        <a href="{{ route('flowers.stock') }}" class="btn btn-primary mb-3">Manage Stocking</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($flowers as $flower)
                    <tr>
                        <td>{{ $flower->name }}</td>
                        <td>{{ $flower->quantity }}</td>
                        <td>Rp {{ number_format($flower->price, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('flowers.show', $flower) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('flowers.edit', $flower) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('flowers.destroy', $flower) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this flower?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
