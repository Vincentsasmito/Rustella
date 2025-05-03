@extends('layouts.app')

@section('content')
    <div class="container-fluid px-5 my-5">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="display-4" style="color: #877D69; font-family:'TrajanPro', sans-serif">
                    Your Cart & Order Details
                </h1>
                <hr class="custom-hr">
            </div>
        </div>

        @if ($cart && count($cart))
            <div class="card shadow-sm p-4">
                {{-- 1) Cart Table --}}
                <div class="table-responsive mb-5"
                    style="overflow: hidden; border-radius: 15px; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);">
                    <table class="table table-striped table-hover table-borderless align-middle mb-0"
                        style="background-color: #fff;">
                        <thead style="background-color: #D1C7BD; color: #322D29; border-bottom: 2px solid #322D29;">
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
                            @foreach ($cart as $item)
                                @php
                                    $price = $item['product']->price;
                                    $quantity = $item['quantity'];
                                    $subtotal = $price * $quantity;
                                    $total += $subtotal;
                                @endphp
                                <tr style="transition: background-color 0.2s;">
                                    <td>{{ $item['product']->name }}</td>
                                    <td>IDR {{ number_format($price, 0) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cart.update', $item['product']) }}"
                                            class="d-flex">
                                            @csrf @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $quantity }}" min="1"
                                                class="form-control form-control-sm me-2" style="width: 70px;">
                                            <button class="btn btn-outline-secondary btn-sm">↻</button>
                                        </form>
                                    </td>
                                    <td>IDR {{ number_format($subtotal, 0) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cart.remove', $item['product']) }}">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">✕</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                {{-- 2) Order Details & Checkout Form --}}
                <form method="POST" action="{{ route('orders.store') }}">
                    @csrf

                    <div class="row g-3 mb-4">
                        {{-- Sender --}}
                        <div class="col-md-6">
                            <label class="form-label">Sender Email</label>
                            <input type="email" name="sender_email" value="{{ old('sender_email') }}" class="form-control"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sender Phone</label>
                            <input type="text" name="sender_phone" value="{{ old('sender_phone') }}" class="form-control"
                                required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Sender Note</label>
                            <textarea name="sender_note" class="form-control">{{ old('sender_note') }}</textarea>
                        </div>

                        {{-- Recipient --}}
                        <div class="col-md-6">
                            <label class="form-label">Recipient Name</label>
                            <input type="text" name="recipient_name" value="{{ old('recipient_name') }}"
                                class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Recipient Phone</label>
                            <input type="text" name="recipient_phone" value="{{ old('recipient_phone') }}"
                                class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Recipient Address</label>
                            <input type="text" name="recipient_address" value="{{ old('recipient_address') }}"
                                class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Recipient City</label>
                            <input type="text" name="recipient_city" value="{{ old('recipient_city') }}"
                                class="form-control" required>
                        </div>

                        {{-- Delivery --}}
                        <div class="col-md-6">
                            <label class="form-label">Delivery Time</label>
                            <input type="datetime-local" name="delivery_time" value="{{ old('delivery_time') }}"
                                class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Delivery Details</label>
                            <textarea name="delivery_details" class="form-control">{{ old('delivery_details') }}</textarea>
                        </div>

                        {{-- Progress & Discount --}}
                        <div class="col-md-6">
                            <label class="form-label">Progress</label>
                            <input type="text" name="progress" value="{{ old('progress') }}" class="form-control"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Discount</label>
                            <select name="discount_id" class="form-select">
                                <option value="">No Discount</option>
                                @foreach ($discounts as $d)
                                    <option value="{{ $d->id }}"
                                        {{ old('discount_id') == $d->id ? 'selected' : '' }}>
                                        {{ $d->code }} ({{ $d->percent }}%)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- 3) Summary, Discount & Checkout --}}
                    <div class="row">
                      <div class="col-md-6 offset-md-6 text-end">
                          <div class="d-flex justify-content-between">
                              <span>Subtotal:</span>
                              <strong id="subtotal">IDR {{ number_format($total, 0) }}</strong>
                          </div>
                  
                          {{-- always render, but hidden until JS shows it --}}
                          <div
                              id="discount-line"
                              class="d-flex justify-content-between text-danger"
                              style="display: none;"
                          >
                              <span id="discount-label"></span>
                              <strong id="discount-amount"></strong>
                          </div>
                  
                          <div class="d-flex justify-content-between mt-2">
                              <span><strong>Total:</strong></span>
                              <strong id="final-total">IDR {{ number_format($total, 0) }}</strong>
                          </div>
                  
                          <button type="submit" class="btn btn-success mt-3 w-100">
                              Proceed to Checkout
                          </button>
                      </div>
                  </div>
                  

                </form>
            </div>
        @else
            <div class="text-center">
                <p>Your cart is empty.</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">← Back to Catalogue</a>
            </div>
        @endif
    </div>
@endsection
@php
    // Build a simple PHP array of discount rules
    $discountsData = $discounts
        ->mapWithKeys(fn($d) => [
            $d->id => [
                'percent'      => $d->percent,
                'min_purchase' => $d->min_purchase,
                'max_discount' => $d->max_discount,
            ],
        ])
        ->toArray();
@endphp

@section('scripts')
<script>
    // JSON‑encode that PHP array for JS use
    const discounts      = @json($discountsData);
    const discountSelect = document.querySelector('select[name="discount_id"]');
    const subtotalRaw    = {{ $total }};
    const discountLine   = document.getElementById('discount-line');
    const discountLabel  = document.getElementById('discount-label');
    const discountAmountEl = document.getElementById('discount-amount');
    const finalTotalEl   = document.getElementById('final-total');

    discountSelect.addEventListener('change', function() {
        const d = discounts[this.value] || null;
        let discountAmount = 0;

        if (d && subtotalRaw >= d.min_purchase) {
            const raw = subtotalRaw * (d.percent / 100);
            discountAmount = Math.min(raw, d.max_discount);
        }

        const finalTotal = subtotalRaw - discountAmount;

        if (discountAmount > 0) {
            discountLine.style.display      = 'flex';
            discountLabel.innerText         = `Discount (${d.percent}%)`;
            discountAmountEl.innerText      = `- IDR ${discountAmount.toLocaleString()}`;
        } else {
            discountLine.style.display      = 'none';
        }

        finalTotalEl.innerText           = `IDR ${finalTotal.toLocaleString()}`;
    });
</script>
@endsection