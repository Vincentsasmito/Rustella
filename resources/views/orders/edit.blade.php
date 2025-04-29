@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Edit Order #{{ $order->id }}</h1>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('orders.update', $order) }}" method="POST">
    @csrf
    @method('PUT')

    @include('orders._form-fields')

    <button type="submit" class="btn btn-primary mt-3">Update Order</button>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Back to List</a>
  </form>

  <hr class="my-4">

  <h2>Order Items</h2>
  {{-- Add a product --}}
  <form action="{{ route('orders.products.store', $order) }}" method="POST" class="row g-2 align-items-end">
    @csrf
    <div class="col-auto">
      <label class="form-label">Product</label>
      <select name="product_id" class="form-select" required>
        @foreach($products as $p)
          <option value="{{ $p->id }}">{{ $p->name }} (${{ number_format($p->price,2) }})</option>
        @endforeach
      </select>
    </div>
    <div class="col-auto">
      <label class="form-label">Quantity</label>
      <input type="number" name="quantity" value="1" min="1" class="form-control" required>
    </div>
    <div class="col-auto">
      <button class="btn btn-success">Add Item</button>
    </div>
  </form>

  {{-- List existing items --}}
  @if($order->orderProducts->count())
    <table class="table table-bordered table-striped mt-3">
      <thead>
        <tr>
          <th>Product</th>
          <th>Qty</th>
          <th>Unit Price</th>
          <th>Subtotal</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      @foreach($order->orderProducts as $item)
        <tr>
          <td>{{ $item->product->name }}</td>
          <td>
            <form action="{{ route('orders.products.update', [$order, $item]) }}" method="POST" class="d-inline-flex">
              @csrf @method('PATCH')
              <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm me-1" style="width: 70px;">
              <button class="btn btn-sm btn-secondary">Save</button>
            </form>
          </td>
          <td>${{ number_format($item->product->price, 2) }}</td>
          <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
          <td>
            <form action="{{ route('orders.products.destroy', [$order, $item]) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('Remove this item?')">Remove</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @else
    <p class="mt-3"><em>No items added yet.</em></p>
  @endif
</div>
@endsection
