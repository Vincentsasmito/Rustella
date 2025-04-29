@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Discount: {{ $discount->code }}</h1>

    <p><strong>Percent:</strong> {{ $discount->percent }}%</p>
    <p><strong>Max Value:</strong> {{ $discount->max_value }}</p>
    <p><strong>Min Purchase:</strong> {{ $discount->min_purchase }}</p>

    <h3>Orders Using This Discount</h3>
    @if($discount->order->isEmpty())
        <p>No orders have used this discount yet.</p>
    @else
        <ul>
          @foreach($discount->order as $order)
            <li>
              Order #{{ $order->id }} — {{ $order->recipient_name }} ({{ $order->delivery_time }})
            </li>
          @endforeach
        </ul>
    @endif

    <a href="{{ route('discounts.index') }}" class="btn btn-secondary mt-3">Back to Discounts</a>
</div>
@endsection