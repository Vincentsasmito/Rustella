@extends('layouts.app')

@section('content')
<div class="container">
  <h1>All Orders</h1>
  <a href="{{ route('orders.create') }}" class="btn btn-primary mb-3">Create New Order</a>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($orders->isEmpty())
    <p><em>No orders yet.</em></p>
  @else
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Recipient</th>
          <th>Delivery Time</th>
          <th>Discount</th>
          <th>Items</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      @foreach($orders as $order)
        <tr>
          <td>{{ $order->id }}</td>
          <td>{{ $order->recipient_name }}<br><small>({{ $order->recipient_phone }})</small></td>
          <td>{{ $order->delivery_time }}</td>
          <td>{{ $order->getDiscountCode() }}</td>
          <td>{{ $order->orderProducts->count() }}</td>
          <td>
            <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning">Edit</a>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">View</a>
            <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this order?')">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @endif
</div>
@endsection
