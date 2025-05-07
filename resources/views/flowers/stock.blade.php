@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Flowers</h1>

    <a href="{{ route('flowers.create') }}" class="btn btn-primary mb-3">Add New Flower</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>In Stock</th>
                <th>Current Price</th>
                <th>Add Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($flowers as $flower)
                <tr>
                    <td>{{ $flower->name }}</td>
                    <td>{{ $flower->quantity }}</td>
                    <td>Rp {{ number_format($flower->price, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('flowers.stockupdate', $flower) }}"
                              method="POST"
                              class="d-flex gap-1 align-items-center">
                            @csrf

                            <input type="number"
                                   name="quantity"
                                   class="form-control form-control-sm"
                                   placeholder="Qty"
                                   min="1"
                                   style="width: 70px;">

                            <input type="number"
                                   name="price"
                                   class="form-control form-control-sm"
                                   placeholder="Total Price"
                                   min="0"
                                   step="0.01"
                                   style="width: 120px;">

                            <button type="submit" class="btn btn-success btn-sm">
                                Add
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
