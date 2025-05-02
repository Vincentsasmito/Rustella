@extends('layouts.app')

@section('content')
<div class="container-fluid px-5">
  <div class="row my-5">
    <div class="col-12 text-center">
      <h1 class="display-4" style="color: #877D69; font-family:'TrajanPro', sans-serif">Catalogue</h1>
      <hr class="custom-hr">
    </div>
  </div>

  <div class="row row-cols-1 row-cols-md-3 g-3">
    @forelse ($products as $product)
      <div class="col mb-4">
        {{-- Make the card fill the column height --}}
        <div class="card shadow-sm border-0 h-100 d-flex flex-column" style="border-radius: 15px; overflow: hidden; max-width: 420px; margin: 0 auto;">
          
          {{-- 1:1 Image --}}
          <div class="ratio ratio-1x1 overflow-hidden">
            @if ($product->image_url)
              <img 
                src="{{ asset('images/' . $product->image_url) }}" 
                alt="{{ $product->name }}"
                class="w-100 h-100 object-fit-cover"
              >
            @else
              <img 
                src="/WebsiteStockImage/RustellaTest.png" 
                alt="Placeholder"
                class="w-100 h-100 object-fit-cover"
              >
            @endif
          </div>

          {{-- Card body as a flex column --}}
          <div class="card-body d-flex flex-column flex-grow-1">
            <h5 class="card-title text-center" style="color: #322D29; font-size: 1.2rem;">
              {{ $product->name }}
            </h5>

            {{-- 
              Force the description area to be at least 3 lines tall,
              and allow it to grow if text is longer 
            --}}
            <p class="card-text mb-3" 
               style="
                 font-family:'TrajanProsmall', sans-serif;
                 color: #322D29; 
                 font-size: 1rem; 
                 text-align: left;
                 
                 /* clamp to 3 lines */
                 display: -webkit-box;
                 -webkit-line-clamp: 3;
                 -webkit-box-orient: vertical;
                 overflow: hidden;

                 /* ensure 3 lines of height even if text is short */
                 min-height: calc(1rem * 1.2 /*line-height*/ * 3 /*lines*/);
               ">
              {{ $product->description ?? 'No description available.' }}
            </p>

            <p class="card-text text-center mt-auto" style="font-weight: bold; color: #6F4F1F;">
              IDR {{ number_format($product->price, 0) }}
            </p>
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
