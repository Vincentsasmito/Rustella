@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="padding-inline: 8%;">
    <div class="row row-cols-1 row-cols-md-3 g-5">
        <!-- Card 1 -->
        <div class="col">
            <div class="card shadow-sm">
                <img src="/WebsiteStockImage/RustellaLP1.png" class="card-img-top" alt="Placeholder image for About Us">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">About Us</h5>
                    <p class="card-text">Discover the story behind Rustella, our passion for flowers, and commitment to quality.</p>
                    <a href="#" class="btn mt-auto" style="background-color: #D1C7BD; border-color: #D1C7BD; color: #444;">Learn More</a>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col">
            <div class="card shadow-sm">
                <img src="/WebsiteStockImage/RustellaLP1.png" class="card-img-top" alt="Placeholder image for Shop Flowers">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Shop Flowers</h5>
                    <p class="card-text">Browse our curated collection of fresh bouquets, arrangements, and plants for every occasion.</p>
                    <a href="#" class="btn mt-auto" style="background-color: #D1C7BD; border-color: #D1C7BD; color: #444;">Explore Catalogue</a>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col">
            <div class="card shadow-sm">
                <img src="/WebsiteStockImage/RustellaLP1.png" class="card-img-top" alt="Placeholder image for Reviews">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Reviews</h5>
                    <p class="card-text">See what our valued customers are saying about their experience with Rustella.</p>
                    <a href="#" class="btn mt-auto" style="background-color: #D1C7BD; border-color: #D1C7BD; color: #444;">Read Testimonials</a>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="custom-hr">
<!-- favorites -->
<div class="text-center my-5">
    <h2 style="font-size: 2rem; color: #877D69; font-family:'TrajanPro', sans-serif">Favorites</h2>
</div>
<!-- Product Cards Section (4 cards) -->
<div class="container-fluid py-4" style="padding-inline: 8%;">
    <div class="row row-cols-1 row-cols-md-4 g-5">
        <!-- Card 1 -->
        <div class="col">
            <div class="card shadow-sm">
                <img src="/WebsiteStockImage/RustellaLP1.png" class="card-img-top" alt="Placeholder image for Product 1">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Product 1</h5>
                    <p class="card-text">This is a description of the first product in the favorites collection.</p>
                    <a href="#" class="btn mt-auto" style="background-color: #D1C7BD; border-color: #D1C7BD; color: #444;">View Details</a>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col">
            <div class="card shadow-sm">
                <img src="/WebsiteStockImage/RustellaLP1.png" class="card-img-top" alt="Placeholder image for Product 2">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Product 2</h5>
                    <p class="card-text">This is a description of the second product in the favorites collection.</p>
                    <a href="#" class="btn mt-auto" style="background-color: #D1C7BD; border-color: #D1C7BD; color: #444;">View Details</a>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col">
            <div class="card shadow-sm">
                <img src="/WebsiteStockImage/RustellaLP1.png" class="card-img-top" alt="Placeholder image for Product 3">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Product 3</h5>
                    <p class="card-text">This is a description of the third product in the favorites collection.</p>
                    <a href="#" class="btn mt-auto" style="background-color: #D1C7BD; border-color: #D1C7BD; color: #444;">View Details</a>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col">
            <div class="card shadow-sm">
                <img src="/WebsiteStockImage/RustellaLP1.png" class="card-img-top" alt="Placeholder image for Product 4">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Product 4</h5>
                    <p class="card-text">This is a description of the fourth product in the favorites collection.</p>
                    <a href="#" class="btn mt-auto" style="background-color: #D1C7BD; border-color: #D1C7BD; color: #444;">View Details</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection