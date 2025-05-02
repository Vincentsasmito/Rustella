@extends('layouts.app')

@section('content')
<div class="container-fluid px-5 my-5">
  <div class="row mb-4">
    <div class="col-12 text-center">
      <h1 class="display-4" style="color: #877D69; font-family:'TrajanPro', sans-serif">Your Cart</h1>
      <hr class="custom-hr">
    </div>
  </div>

  @if($cart && count($cart))
    <div class="card shadow-sm p-4">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th style="width: 140px;">Quantity</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @php $total = 0; @endphp
            @foreach($cart as $item)
              @php
                $price = $item['product']->price;
                $quantity = $item['quantity'];
                $subtotal = $price * $quantity;
                $total += $subtotal;
              @endphp
              <tr>
                <td>{{ $item['product']->name }}</td>
                <td>IDR {{ number_format($price, 0) }}</td>
                <td>
                  <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="d-flex">
                    @csrf @method('PATCH')
                    <input type="number" name="quantity" value="{{ $quantity }}" min="1" class="form-control me-2" style="width: 70px;">
                    <button class="btn btn-sm btn-outline-secondary">↻</button>
                  </form>
                </td>
                <td>IDR {{ number_format($subtotal, 0) }}</td>
                <td>
                  <form method="POST" action="{{ route('cart.remove', $item['product']) }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">✕</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="row mt-4">
        <div class="col-md-6 offset-md-6 text-end">
          <h4>Total: <span class="text-success">IDR {{ number_format($total, 0) }}</span></h4>

          {{-- Checkout Button --}}
          <form method="POST" action="">
            @csrf
            <button type="submit" class="btn btn-success mt-3 w-100">
              Proceed to Checkout
            </button>
          </form>
        </div>
      </div>
    </div>
  @else
    <div class="text-center">
      <p>Your cart is empty.</p>
      <a href="{{ route('products.index') }}" class="btn btn-outline-primary">← Back to Catalogue</a>
    </div>
  @endif
</div>
@endsection
