@extends('layouts.app')

@section('content')
    <div class="container-fluid px-5">
        <div class="row my-5">
            <div class="col-md-6 text-start">
                <h1 class="display-4" style="color: #877D69; font-family:'TrajanPro', sans-serif">Catalogue</h1>
            </div>
            <div class="col-md-6 text-end align-self-center">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-dark">
                    🛒 View Cart / Checkout
                </a>
            </div>
            <div class="col-12">
                <hr class="custom-hr">
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-3">
            @forelse ($products as $product)
                <div class="col mb-4">
                    <div class="card shadow-sm border-0 h-100 d-flex flex-column"
                        style="border-radius: 15px; overflow: hidden; max-width: 525px; margin: 0 auto;">

                        {{-- Product Image --}}
                        <div class="ratio ratio-1x1 overflow-hidden">
                            <img src="{{ $product->image_url ? asset('images/' . $product->image_url) : '/WebsiteStockImage/RustellaTest.png' }}"
                                alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                        </div>

                        {{-- Product Info --}}
                        <div class="card-body d-flex flex-column flex-grow-1">
                            <h5 class="card-title text-center" style="color: #322D29; font-size: 1.2rem;">
                                {{ $product->name }}
                            </h5>

                            <p class="card-text mb-3"
                                style="font-family:'TrajanProsmall', sans-serif;
                                        color: #322D29; 
                                        font-size: 1rem; 
                                        text-align: left;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 3;
                                        -webkit-box-orient: vertical;
                                        overflow: hidden;
                                        min-height: calc(1rem * 1.2 * 3);">
                                {{ $product->description ?? 'No description available.' }}
                            </p>

                            <p class="card-text text-center mb-3" style="font-weight: bold; color: #6F4F1F;">
                                IDR {{ number_format($product->price, 0) }}
                            </p>

                            {{-- In-Cart Quantity --}}
                            @php
                                $cart = session()->get('cart', []);
                                $quantityInCart = $cart[$product->id] ?? 0; // Direct access to quantity
                            @endphp

                            @if ($quantityInCart > 0)
                                <p class="text-center mb-3" style="font-weight: bold; color: #6F4F1F;">
                                    In Cart: {{ $quantityInCart }}
                                </p>
                            @endif
                            {{-- Add to Cart Button --}}
                            <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-auto add-to-cart-form"
                                data-product-id="{{ $product->id }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100" style="background-color: #877D69; border-color: #877D69;">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>No products available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
