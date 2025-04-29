@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Discounts</h1>

    <a href="{{ route('discounts.create') }}" class="btn btn-primary mb-3">Create New Discount</a>

    @if($discounts->isEmpty())
        <p>No discounts found.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Percent (%)</th>
                    <th>Max Value</th>
                    <th>Min Purchase</th>
                    <th>Orders Used</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($discounts as $disc)
                <tr>
                    <td>{{ $disc->code }}</td>
                    <td>{{ $disc->percent }}</td>
                    <td>{{ $disc->max_value }}</td>
                    <td>{{ $disc->min_purchase }}</td>
                    <td>{{ $disc->order->count() }}</td>
                    <td>
                        <a href="{{ route('discounts.show', $disc->id) }}" class="btn btn-sm btn-info">Show</a>
                        <a href="{{ route('discounts.edit', $disc->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('discounts.destroy', $disc->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this discount?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection