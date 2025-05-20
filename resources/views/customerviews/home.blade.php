<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- expose Laravel’s CSRF token for AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rustella Florist- Professional Floral Design</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mocha: {
                            light: '#D1C7BD',
                            medium: '#AC9C8D',
                            cream: '#EFE9E1',
                            burgundy: '#741D29',
                            dark: '#322D29',
                            gray: '#D9D9D9'
                        }
                    },
                    fontFamily: {
                        'playfair': ['Playfair Display', 'serif'],
                        'montserrat': ['Montserrat', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <!-- Initialize cart count from session -->
    @php
        $initialCount = array_sum(session('cart', []));
    @endphp
    <script>
        window.RustellaCart = {
            count: {{ $initialCount }}
        };
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <!-- AOS Animation Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- GSAP for more advanced animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <!-- Lottie for vector animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.10.2/lottie.min.js"></script>

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            color: #322D29;
            background-color: #FFF;
            overflow-x: hidden;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .bestseller-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #741D29;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 1;
        }

        /* Animation Classes */
        .fade-in {
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .fade-in.visible {
            opacity: 1;
        }

        .slide-up {
            transform: translateY(50px);
            opacity: 0;
            transition: transform 0.8s ease-out, opacity 0.8s ease-out;
        }

        .slide-up.visible {
            transform: translateY(0);
            opacity: 1;
        }

        .slide-in-left {
            transform: translateX(-100px);
            opacity: 0;
            transition: transform 0.8s ease-out, opacity 0.8s ease-out;
        }

        .slide-in-left.visible {
            transform: translateX(0);
            opacity: 1;
        }

        .slide-in-right {
            transform: translateX(100px);
            opacity: 0;
            transition: transform 0.8s ease-out, opacity 0.8s ease-out;
        }

        .slide-in-right.visible {
            transform: translateX(0);
            opacity: 1;
        }

        .scale-in {
            transform: scale(0.8);
            opacity: 0;
            transition: transform 0.6s ease-out, opacity 0.6s ease-out;
        }

        .scale-in.visible {
            transform: scale(1);
            opacity: 1;
        }

        /* Flower Petal Animation */
        .petal {
            position: absolute;
            width: 15px;
            height: 15px;
            background-color: rgba(116, 29, 41, 0.2);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            pointer-events: none;
            z-index: 1000;
            animation: fall 10s linear forwards;
        }

        @keyframes fall {
            0% {
                transform: translateY(-10vh) rotate(0deg) scale(0.8);
                opacity: 0.8;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 0.9;
            }

            100% {
                transform: translateY(100vh) rotate(360deg) scale(0.5);
                opacity: 0;
            }
        }

        /* Hover Effects */
        .btn-hover {
            position: relative;
            overflow: hidden;
            z-index: 1;
            transition: color 0.3s ease;
        }

        .btn-hover:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0%;
            background-color: #322D29;
            transition: height 0.3s ease;
            z-index: -1;
        }

        .btn-hover:hover:before {
            height: 100%;
        }

        /* Card Hover Effect */
        .card-hover {
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(50, 45, 41, 0.1);
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-in-out, visibility 0.5s ease-in-out;
        }

        .loading-spinner {
            width: 100px;
            height: 100px;
            border: 5px solid #EFE9E1;
            border-top: 5px solid #741D29;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Toast Animation */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-notification {
            animation: slideInRight 0.5s ease forwards;
        }

        /* Pulse Animation */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(116, 29, 41, 0.7);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(116, 29, 41, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(116, 29, 41, 0);
            }
        }

        /* Parallax Effect */
        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* Floating Animation */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translate(0, 0px);
            }

            50% {
                transform: translate(0, 15px);
            }

            100% {
                transform: translate(0, 0px);
            }
        }
    </style>
</head>

<body>
    <!-- Loading Screen -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="loading-spinner mb-4"></div>
            <h2 class="font-playfair text-2xl text-mocha-burgundy mt-4">Rustella Floristry</h2>
            <p class="text-mocha-medium">Crafting beauty...</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full z-10">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <div class="text-mocha-burgundy">
                        <img src="{{ asset('/WebsiteStockImage/Rustella.png') }}" alt="Rustella Logo"
                            class="h-8 w-auto">
                    </div>
                    <a href="home" class="font-playfair text-2xl font-bold text-mocha-dark">
                        <span class="inline-block hover:scale-105 transition-transform duration-300">R</span>
                        <span class="inline-block hover:scale-105 transition-transform duration-300">u</span>
                        <span class="inline-block hover:scale-105 transition-transform duration-300">s</span>
                        <span class="inline-block hover:scale-105 transition-transform duration-300">t</span>
                        <span class="inline-block hover:scale-105 transition-transform duration-300">e</span>
                        <span class="inline-block hover:scale-105 transition-transform duration-300">l</span>
                        <span class="inline-block hover:scale-105 transition-transform duration-300">l</span>
                        <span class="inline-block hover:scale-105 transition-transform duration-300">a</span>
                        <span
                            class="inline-block text-mocha-burgundy hover:scale-105 transition-transform duration-300">F</span>
                        <span
                            class="inline-block text-mocha-burgundy hover:scale-105 transition-transform duration-300">l</span>
                        <span
                            class="inline-block text-mocha-burgundy hover:scale-105 transition-transform duration-300">o</span>
                        <span
                            class="inline-block text-mocha-burgundy hover:scale-105 transition-transform duration-300">r</span>
                        <span
                            class="inline-block text-mocha-burgundy hover:scale-105 transition-transform duration-300">i</span>
                        <span
                            class="inline-block text-mocha-burgundy hover:scale-105 transition-transform duration-300">s</span>
                        <span
                            class="inline-block text-mocha-burgundy hover:scale-105 transition-transform duration-300">t</span>
                        <span
                            class="inline-block text-mocha-burgundy hover:scale-105 transition-transform duration-300">r</span>
                        <span
                            class="inline-block text-mocha-burgundy hover:scale-105 transition-transform duration-300">y</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="#"
                        class="text-mocha-dark hover:text-mocha-burgundy font-medium relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-mocha-burgundy after:transition-all after:duration-300 hover:after:w-full">Home</a>
                    <a href="#bestsellers"
                        class="text-mocha-dark hover:text-mocha-burgundy font-medium relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-mocha-burgundy after:transition-all after:duration-300 hover:after:w-full">Best
                        Sellers</a>
                    <a href="#catalog"
                        class="text-mocha-dark hover:text-mocha-burgundy font-medium relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-mocha-burgundy after:transition-all after:duration-300 hover:after:w-full">Catalog</a>
                    <a href="#about"
                        class="text-mocha-dark hover:text-mocha-burgundy font-medium relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-mocha-burgundy after:transition-all after:duration-300 hover:after:w-full">About
                        Us</a>
                    <a href="#contact"
                        class="text-mocha-dark hover:text-mocha-burgundy font-medium relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-mocha-burgundy after:transition-all after:duration-300 hover:after:w-full">Suggestion</a>

                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="menu-toggle" class="text-mocha-dark focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Cart & Profile Icons -->
                <div class="hidden md:flex items-center space-x-6">
                    @auth
                        <span class="text-mocha-medium font-medium">
                            Welcome back,&nbsp;{{ Auth::user()->name }}!
                        </span>
                    @endauth

                    <a href="profile" class="text-mocha-burgundy hover:text-mocha-dark transition-colors duration-300">
                        <i class="fas fa-user text-xl"></i>
                    </a>

                    <a href="{{ route('cart.index') }}"
                        class="text-mocha-dark hover:text-mocha-burgundy transition-colors duration-300 relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span id="cart-badge"
                            class="bg-mocha-burgundy text-white rounded-full px-2 py-1 text-xs absolute -top-2 -right-2 transition-transform duration-300">
                            {{ $initialCount }}
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu"
            class="hidden md:hidden bg-white transform -translate-y-full transition-transform duration-300 ease-in-out">
            <div class="container mx-auto px-4 py-2 space-y-3">
                <a href="#" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Home</a>
                <a href="#bestsellers" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Best
                    Sellers</a>
                <a href="#catalog" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Catalog</a>
                <a href="#about" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">About Us</a>
                <a href="#contact"
                    class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Suggestion</a>
                <a href="Profieluser.html" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>
                <a href="{{ route('cart.index') }}"
                    class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">
                    <i class="fas fa-shopping-cart mr-2"></i> Cart
                    <span class="bg-mocha-burgundy text-white rounded-full px-2 py-1 text-xs">3</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 pb-16 md:pt-32 md:pb-24 bg-mocha-light/20 relative overflow-hidden">
        <!-- Animated background petals -->
        <div id="petals-container" class="absolute top-0 left-0 w-full h-full pointer-events-none"></div>

        <div class="container mx-auto px-4 md:px-8">
            <div class="flex flex-col md:flex-row items-center">
                <!-- Text Column (now half width) -->
                <div class="md:w-1/2 md:pr-12 mb-8 md:mb-0" data-aos="fade-right" data-aos-duration="1000">
                    <h1 class="font-playfair text-4xl md:text-5xl lg:text-6xl font-bold text-mocha-dark mb-4">
                        Personal Touch of
                        <span class="text-mocha-burgundy relative">Art
                            <svg class="absolute -bottom-2 left-0 w-full" height="6" viewBox="0 0 200 6"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 3C50 0.5 150 0.5 200 3" stroke="#741D29" stroke-width="5"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                    </h1>
                    <p class="text-mocha-medium text-lg mb-8">
                        Hand-crafted arrangements that capture nature's beauty and your unique style.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="#catalog"
                            class="bg-mocha-burgundy text-white py-3 px-6 rounded-md text-center hover:bg-opacity-90 transition">
                            Explore Catalog
                        </a>
                    </div>
                </div>

                <!-- Image Column (now half width) -->
                <div class="md:w-1/2" data-aos="fade-left" data-aos-duration="1000">
                    <div class="relative">
                        <img src="/WebsiteStockImage/homepage_landscape.png" alt="Elegant flower arrangement"
                            class="rounded-lg shadow-lg w-full h-auto max-h-[400px] object-cover" />

                        <div class="absolute -bottom-4 -right-4 bg-white p-3 rounded-lg shadow-md" data-aos="fade-up"
                            data-aos-delay="500">
                            <div class="flex items-center space-x-2">
                                <div class="text-amber-500 flex space-x-0.5">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="text-mocha-dark font-medium">4.8/5</span>
                            </div>
                            <p class="text-sm text-mocha-medium mt-1">Over 500+ Happy Customers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg bg-mocha-cream hover:shadow-md transition card-hover"
                    data-aos="fade-up" data-aos-delay="100">
                    <div class="inline-block p-4 rounded-full bg-mocha-light mb-4 pulse">
                        <i class="fas fa-truck text-2xl text-mocha-burgundy"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Same Day Delivery</h3>
                    <p class="text-mocha-medium">Order before 2pm for same-day flower delivery within the city.</p>
                </div>

                <div class="text-center p-6 rounded-lg bg-mocha-cream hover:shadow-md transition card-hover"
                    data-aos="fade-up" data-aos-delay="200">
                    <div class="inline-block p-4 rounded-full bg-mocha-light mb-4 pulse">
                        <i class="fas fa-leaf text-2xl text-mocha-burgundy"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Fresh Guarantee</h3>
                    <p class="text-mocha-medium">All our flowers are guaranteed fresh for at least 7 days.</p>
                </div>

                <div class="text-center p-6 rounded-lg bg-mocha-cream hover:shadow-md transition card-hover"
                    data-aos="fade-up" data-aos-delay="300">
                    <div class="inline-block p-4 rounded-full bg-mocha-light mb-4 pulse">
                        <i class="fas fa-paint-brush text-2xl text-mocha-burgundy"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Intricate Designs</h3>
                    <p class="text-mocha-medium">Create your memorable arrangements with our expert florists.</p>
                </div>
            </div>
        </div>

    </section>

    <!-- Best Sellers Section -->
    <section id="bestsellers" class="py-16 bg-mocha-burgundy/10">
        <div class="container mx-auto px-4 md:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="font-playfair text-3xl md:text-4xl font-bold text-mocha-dark">Customer Classics</h2>
                <p class="text-mocha-medium mt-2">Our tried-and-true best sellers, loved by all.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($top3product as $product)
                    @php
                        $reviewsJson = json_encode(
                            $product->limitedReviews->map(
                                fn($r) => [
                                    'rating' => $r->rating,
                                    'message' => $r->message,
                                    'created_at' => $r->created_at->toDateTimeString(),
                                    'user' => ['name' => $r->user->name],
                                ],
                            ),
                            JSON_HEX_APOS | JSON_HEX_QUOT,
                        );
                    @endphp
                    <div class="card-clickable bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition cursor-pointer"
                        data-aos="fade-up" data-aos-delay="100" data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}" data-img="{{ asset('images/' . $product->image_url) }}"
                        data-desc="{{ $product->description }}"
                        data-price="Rp {{ number_format($product->price, 0, ',', '.') }}"
                        data-pack="{{ $product->packaging->name }}" data-recipe='@json($product->flowerProducts->map(fn($fp) => ['name' => $fp->flower->name, 'qty' => $fp->quantity]))'
                        data-reviews="{{ $reviewsJson }}">
                        <span class="bestseller-badge">
                            <i class="fas fa-star mr-1"></i> Best Seller
                        </span>

                        <div class="aspect-[1/1] overflow-hidden">
                            <img src="{{ asset('images/' . $product->image_url) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover transition-transform duration-700 hover:scale-110">
                        </div>

                        <div class="p-5">
                            <h3 class="font-playfair text-xl font-semibold mb-2">{{ $product->name }}</h3>
                            <p class="text-mocha-medium mb-3 truncate">{{ $product->description }}</p>

                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-mocha-burgundy font-semibold text-lg">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    @if ($product->original_price && $product->original_price > $product->price)
                                        <span class="text-mocha-medium line-through ml-2">
                                            Rp{{ number_format($product->original_price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                <button
                                    class="add-to-cart bg-mocha-burgundy text-white py-2 px-4 rounded-md hover:bg-opacity-90 transition"
                                    data-url="{{ route('cart.add', $product->id) }}">
                                    Add to Cart
                                </button>
                            </div>

                            <div class="mt-3 text-amber-500">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                <span class="text-mocha-medium ml-1">
                                    ({{ $quantities[$product->id] ?? 0 }} sold)
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="#catalog"
                    class="inline-block bg-mocha-dark text-white py-3 px-8 rounded-md hover:bg-opacity-90 transition">
                    View All Collections
                </a>
            </div>
        </div>
    </section>

    <!-- Catalog Section -->
    <section id="catalog" class="py-16 bg-mocha-light/10">
        <div class="container mx-auto px-4 md:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="font-playfair text-3xl md:text-4xl font-bold text-mocha-dark">Our Collection</h2>
                <p class="text-mocha-medium mt-2">Browse our hand-crafted floral arrangements</p>
            </div>

            <!-- Category Tabs -->
            <div class="flex flex-wrap justify-center mb-10" data-aos="fade-up">
                <!-- “All” tab -->
                <button class="category-tab active m-2 px-6 py-2 rounded-full bg-mocha-burgundy text-white"
                    data-category="all">
                    All
                </button>

                <!-- One tab per packaging name -->
                @foreach ($groupedProducts->keys() as $category)
                    <button
                        class="category-tab m-2 px-6 py-2 rounded-full bg-mocha-gray text-mocha-dark hover:bg-mocha-light transition"
                        data-category="{{ $category }}">
                        {{ Str::title($category) }}
                    </button>
                @endforeach
            </div>

            <!-- Product Grid -->
            <div id="catalog-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($groupedProducts as $category => $items)
                    @foreach ($items as $product)
                        @php
                            // prepare the reviews JSON once
                            $reviewsJson = json_encode(
                                $product->limitedReviews->map(
                                    fn($r) => [
                                        'rating' => $r->rating,
                                        'message' => $r->message,
                                        'created_at' => $r->created_at->toDateTimeString(),
                                        'user' => ['name' => $r->user->name],
                                    ],
                                ),
                                JSON_HEX_APOS | JSON_HEX_QUOT,
                            );
                        @endphp
                        <div class="product-card bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition cursor-pointer"
                            data-id     ="{{ $product->id }}" data-name   ="{{ $product->name }}"
                            data-img    ="{{ asset('images/' . $product->image_url) }}"
                            data-desc   ="{{ $product->description }}"
                            data-price  ="Rp {{ number_format($product->price, 0, ',', '.') }}"
                            data-pack   ="{{ $product->packaging->name }}"
                            data-recipe ='@json($product->flowerProducts->map(fn($fp) => ['name' => $fp->flower->name, 'qty' => $fp->quantity]))' data-reviews="{{ $reviewsJson }}">
                            <div class="aspect-[1/1] overflow-hidden">
                                <img src="{{ asset('images/' . $product->image_url) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover" />
                            </div>
                            <div class="p-4">
                                <h3 class="font-playfair text-xl font-semibold mb-2">{{ $product->name }}</h3>
                                <p class="text-mocha-medium mb-3 truncate">{{ $product->description }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-mocha-burgundy font-semibold text-lg">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    <button
                                        class="add-to-cart bg-mocha-burgundy text-white py-2 px-4 rounded-md hover:bg-opacity-90 transition"
                                        data-url="{{ route('cart.add', $product->id) }}">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button id="load-more"
                    class="border border-mocha-medium text-mocha-dark py-3 px-8 rounded-md hover:bg-mocha-light/30 transition">
                    Load More
                </button>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-16 bg-mocha-cream">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex flex-col lg:flex-row items-center">
                <div class="lg:w-1/2 mb-10 lg:mb-0 lg:pr-12">
                    <img src="/WebsiteStockImage/homepage_aboutus.png" alt="Our Story" class="rounded-lg">
                </div>
                <div class="lg:w-1/2">
                    <h2 class="font-playfair text-3xl md:text-4xl font-bold text-mocha-dark mb-6">Our Story</h2>
                    <p class="text-mocha-medium mb-4 text-lg">Founded in 2023 by Vania Evangeline, Rustella Floristry
                        transforms nature’s finest blooms into handcrafted arrangements that captivate the eye and
                        uplift the spirit. Each creation is thoughtfully designed to bring warmth, beauty, and joy to
                        every occasion.
                    </p>
                    <p class="text-mocha-medium mb-6"></p>

                    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                        <h3 class="font-playfair text-xl font-semibold mb-3 text-mocha-burgundy">Our Mission</h3>
                        <p class="text-mocha-medium">To bring the transformative beauty of nature into everyday life
                            through thoughtfully crafted floral designs that celebrate life's moments and connections.
                        </p>
                    </div>

                    <h3 class="font-semibold text-lg mb-3">What Sets Us Apart</h3>
                    <ul class="text-mocha-medium space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-mocha-burgundy mt-1 mr-2"></i>
                            <span>Locally-sourced, sustainable flowers whenever possible</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-mocha-burgundy mt-1 mr-2"></i>
                            <span>Expert florists with professional training and certification</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-mocha-burgundy mt-1 mr-2"></i>
                            <span>Personalized consultations for events and special occasions</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-mocha-burgundy mt-1 mr-2"></i>
                            <span>Commitment to environmental responsibility in all operations</span>
                        </li>
                    </ul>

                    <div class="mt-8">
                        <a href="#contact"
                            class="bg-mocha-burgundy text-white py-3 px-6 rounded-md hover:bg-opacity-90 transition">
                            Get in Touch
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Logos Section -->
    <section class="py-16 bg-mocha-light/10" data-aos="fade-up">
        <div class="container mx-auto px-4 md:px-8">
            <div class="text-center mb-10">
                <h2 class="font-playfair text-3xl font-bold text-mocha-dark">Professional Clients</h2>
                <p class="text-mocha-medium mt-2">Companies we've worked with</p>
                <div class="w-24 h-1 bg-mocha-burgundy mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-8 items-center">
                <img src="{{ asset('/WebsiteStockImage/Stockbit.png') }}" alt="Partner 1"
                    class="mx-auto h-16 object-contain" data-aos="zoom-in" data-aos-delay="100">
                <img src="{{ asset('/WebsiteStockImage/bca.png') }}" alt="Partner 2"
                    class="mx-auto h-16 object-contain" data-aos="zoom-in" data-aos-delay="200">
                <img src="{{ asset('/WebsiteStockImage/manulife.jpg') }}" alt="Partner 3"
                    class="mx-auto h-16 object-contain" data-aos="zoom-in" data-aos-delay="300">
                <img src="{{ asset('/WebsiteStockImage/allianz.png') }}" alt="Partner 4"
                    class="mx-auto h-16 object-contain" data-aos="zoom-in" data-aos-delay="400">
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-16 bg-mocha-cream">
        <div class="container mx-auto px-4 md:px-8">
            <div class="text-center mb-12">
                <h2 class="font-playfair text-3xl md:text-4xl font-bold text-mocha-dark">
                    What People Say About Rustella
                </h2>
                <p class="text-mocha-medium mt-2">Trusted by flower lovers across the city</p>
            </div>

            @php
                $count = $testimonials->count();
                $cardW = 425; // width of each card in px
                $gapPx = 24; // gap between cards in px (1.5rem)
                $visibleCount = 3; // how many cards are visible at once
                $visibleW = $visibleCount * $cardW + ($visibleCount - 1) * $gapPx;
                $totalW = $count * $cardW + max(0, $count - 1) * $gapPx;
                // for seamless loop, scroll exactly one set’s width
                $scrollAmount = max(0, $totalW);
                $duration = max(10, $count * 3); // adjust speed as needed
            @endphp

            @if ($count > $visibleCount)
                <div class="mx-auto overflow-hidden" style="width: {{ $visibleW }}px;">
                    <div class="flex"
                        style="
                        gap: 1.5rem;
                        animation: scrollTestimonials {{ $duration }}s linear infinite;
                        --scroll-amount: {{ $scrollAmount }}px;
                    ">
                        {{-- first pass --}}
                        @foreach ($testimonials as $t)
                            <div class="min-w-[350px] bg-white p-6 rounded-lg shadow-md flex-shrink-0">
                                <div class="text-mocha-burgundy mb-3">
                                    @for ($i = 0; $i < $t->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <p class="italic mb-4">"{{ $t->message }}"</p>
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-mocha-medium rounded-full flex items-center justify-center mr-3">
                                        <span
                                            class="text-white font-semibold">{{ strtoupper(substr($t->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">{{ $t->user->name }}</h4>
                                        <p class="text-sm text-mocha-medium">Loyal Customer</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- duplicate pass for seamless loop --}}
                        @foreach ($testimonials as $t)
                            <div class="min-w-[350px] bg-white p-6 rounded-lg shadow-md flex-shrink-0">
                                <div class="text-mocha-burgundy mb-3">
                                    @for ($i = 0; $i < $t->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <p class="italic mb-4">"{{ $t->message }}"</p>
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-mocha-medium rounded-full flex items-center justify-center mr-3">
                                        <span
                                            class="text-white font-semibold">{{ strtoupper(substr($t->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">{{ $t->user->name }}</h4>
                                        <p class="text-sm text-mocha-medium">Loyal Customer</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <style>
                    @keyframes scrollTestimonials {
                        from {
                            transform: translateX(0);
                        }

                        to {
                            transform: translateX(calc(-1 * var(--scroll-amount)));
                        }
                    }
                </style>
            @else
                {{-- fewer than 4 → static 3-column grid of dummies --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="text-mocha-burgundy mb-3">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="italic mb-4">"We absolutely love Rustella’s service!"</p>
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-mocha-medium rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white font-semibold">A</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold">Alice</h4>
                                    <p class="text-sm text-mocha-medium">Happy Customer</p>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            @endif

        </div>
    </section>


    <section id="contact" class="py-16 bg-mocha-cream">
        <div class="container mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">

                <!-- FAQ Accordion -->
                <div class="space-y-4">
                    <h2 class="font-playfair text-3xl font-bold text-mocha-dark mb-6">FAQ & Tips</h2>
                    <!-- Repeat this block 7×, changing the question/text -->
                    <details class="bg-white rounded-lg shadow p-4">
                        <summary class="cursor-pointer font-semibold text-mocha-burgundy">
                            How do I track my order?
                        </summary>
                        <p class="mt-2 text-mocha-medium text-sm">
                            Once your order ships, you’ll receive an email with a tracking link—just click and follow
                            along!
                        </p>
                    </details>
                    <details class="bg-white rounded-lg shadow p-4">
                        <summary class="cursor-pointer font-semibold text-mocha-burgundy">
                            Can I customize my bouquet?
                        </summary>
                        <p class="mt-2 text-mocha-medium text-sm">
                            Absolutely! At checkout you can add special instructions, or send us a note and we'll get in
                            touch.
                        </p>
                    </details>
                    <!-- …add 5 more… -->
                </div>

                <!-- Feedback Form -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="font-playfair text-3xl font-bold text-mocha-dark mb-4">
                        Love It? Hate It? Let Us Know!
                    </h2>
                    <div id="suggestion-container">
                        <form id="site-suggestion-form" action="{{ route('site.suggestions') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Your Rating</label>
                                <input type="hidden" name="rating" id="rating" value="{{ old('rating') }}">
                                <div id="star-rating" class="flex space-x-1 text-2xl cursor-pointer">
                                    <span class="star text-mocha-gray hover:text-amber-500" data-value="1">★</span>
                                    <span class="star text-mocha-gray hover:text-amber-500" data-value="2">★</span>
                                    <span class="star text-mocha-gray hover:text-amber-500" data-value="3">★</span>
                                    <span class="star text-mocha-gray hover:text-amber-500" data-value="4">★</span>
                                    <span class="star text-mocha-gray hover:text-amber-500" data-value="5">★</span>
                                </div>
                                @error('rating')
                                    <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium mb-1">What do you think about
                                    our
                                    site?</label>
                                <textarea id="message" name="message" rows="4"
                                    class="w-full border border-mocha-light rounded p-2 focus:outline-none" placeholder="Your suggestions…">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full bg-mocha-burgundy text-white py-2 rounded hover:bg-opacity-90 transition">
                                Send Feedback
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <!-- Footer -->
    <footer class="bg-mocha-dark text-white pt-12 pb-6">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex flex-wrap">
                <div class="w-full md:w-1/4 mb-8">
                    <h3 class="font-playfair text-xl font-bold mb-4">RustellaFloristry</h3>
                    <p class="text-mocha-light mb-4"></p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-mocha-light hover:text-white transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-mocha-light hover:text-white transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-mocha-light hover:text-white transition">
                            <i class="fab fa-pinterest-p"></i>
                        </a>
                    </div>
                </div>

                <div class="w-full md:w-1/4 mb-8">
                    <h3 class="font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-mocha-light hover:text-white transition">Home</a></li>
                        <li><a href="#catalog" class="text-mocha-light hover:text-white transition">Catalog</a></li>
                        <li><a href="#" class="text-mocha-light hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="text-mocha-light hover:text-white transition">Contact</a></li>
                    </ul>
                </div>


                <div class="w-full md:w-1/4 mb-8">
                    <h3 class="font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2 text-mocha-light"></i>
                            <span>Karawaci,Tangerang</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone mt-1 mr-2 text-mocha-light"></i>
                            <span>+62</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-2 text-mocha-light"></i>
                            <span>Email</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-clock mt-1 mr-2 text-mocha-light"></i>
                            <span>Mon-Sat: 9AM - 5PM</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-mocha-medium/30 mt-8 pt-6 text-center">
                <p class="text-mocha-light text-sm">&copy; 2025 Rustella Floristy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Product Detail Modal -->
    <div id="product-detail-modal"
        class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <!-- header -->
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 id="modal-name" class="text-xl font-semibold"></h3>
                <button id="modal-close" class="text-mocha-dark hover:text-mocha-burgundy text-2xl">&times;</button>
            </div>

            <!-- body -->
            <div class="px-6 py-4 space-y-4">
                <!-- 1:1 image container -->
                <div class="w-full aspect-square overflow-hidden rounded image-zoom-container">
                    <img id="modal-image" src="" alt=""
                        class="object-cover w-full h-full cursor-grab" />
                </div>

                <p id="modal-desc" class="text-mocha-medium"></p>

                <ul class="space-y-2">
                    <li><strong>Price:</strong> <span id="modal-price"></span></li>
                    <li><strong>Packaging:</strong> <span id="modal-packaging"></span></li>
                    <li>
                        <strong>Composition:</strong>
                        <ul id="modal-recipe" class="list-disc list-inside text-mocha-dark"></ul>
                    </li>
                </ul>

                <div class="space-y-2">
                    <strong>Reviews:</strong>
                    <div id="modal-reviews" class="mt-2 space-y-4 text-sm text-mocha-medium">
                        {{-- filled in by JS --}}
                    </div>
                </div>

                <!-- footer -->
                <div class="px-6 py-4 border-t text-right">
                    <button id="modal-close-footer"
                        class="px-4 py-2 bg-mocha-burgundy text-white rounded hover:bg-opacity-90">
                        Close
                    </button>
                </div>
            </div>
        </div>


        <!-- Script -->
        <!-- Panzoom -->
        <script src="https://unpkg.com/@panzoom/panzoom/dist/panzoom.min.js"></script>
        <script>
            // 1) declare at top‐level
            let panzoom;

            document.addEventListener('DOMContentLoaded', () => {
                const imgEl = document.getElementById('modal-image');
                // 2) assign into our outer variable
                panzoom = Panzoom(imgEl, {
                    maxScale: 3,
                    step: 0.3,
                    contain: 'outside'
                });
                imgEl.parentElement.addEventListener('wheel', panzoom.zoomWithWheel);
                imgEl.addEventListener('panzoomstart', () => imgEl.style.cursor = 'grabbing');
                imgEl.addEventListener('panzoomend', () => imgEl.style.cursor = 'grab');
            });
        </script>
        <!-- AOS Initialization -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
        <script>
            AOS.init();
        </script>
        <!-- Loading Screen Fade Out -->
        <script>
            window.addEventListener('load', () => {
                const overlay = document.getElementById('loadingOverlay');
                overlay.style.opacity = '0';
                setTimeout(() => overlay.style.display = 'none', 500);
            });
        </script>
        <script>
            // Mobile Menu Toggle
            document.getElementById('menu-toggle').addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobile-menu');
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('-translate-y-full');
            });


            // Toast Notification
            function showToast(message) {
                const toast = document.createElement('div');
                toast.className =
                    'fixed bottom-4 right-4 bg-mocha-dark text-white py-2 px-4 rounded-md shadow-lg transition-opacity duration-300 opacity-0';
                toast.textContent = message;
                document.body.appendChild(toast);
                setTimeout(() => toast.classList.replace('opacity-0', 'opacity-100'), 100);
                setTimeout(() => {
                    toast.classList.replace('opacity-100', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Smooth Scroll
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 80,
                            behavior: 'smooth'
                        });
                        if (!mobileMenu.classList.contains('hidden')) mobileMenu.classList.add('hidden');
                    }
                });
            });

            // Category Filtering
            const categoryTabs = document.querySelectorAll('.category-tab');
            const productCards = document.querySelectorAll('.product-card');

            categoryTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    categoryTabs.forEach(t => t.classList.remove('active', 'bg-mocha-burgundy', 'text-white'));
                    tab.classList.add('active', 'bg-mocha-burgundy', 'text-white');
                    const category = tab.getAttribute('data-category');
                    productCards.forEach(card => {
                        card.style.display = category === 'all' || card.getAttribute(
                            'data-category') === category ? 'block' : 'none';
                    });
                });
            });

            // Add to Cart
            const addToCartButtons = document.querySelectorAll('.add-to-cart');
            const cartCount = document.querySelectorAll('.bg-mocha-burgundy.text-white.rounded-full');
            let itemsInCart = 0;

            function updateCartUI() {
                cartCount.forEach(count => count.textContent = itemsInCart);
            }

            document.addEventListener('click', async function(e) {
                const btn = e.target.closest('.add-to-cart');
                if (!btn) return;
                e.preventDefault();

                const url = btn.dataset.url;
                if (!url) return console.error('No data-url on button');

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            qty: 1
                        }),
                    });

                    // parse JSON regardless of OK or not
                    const payload = await res.json();

                    if (!res.ok) {
                        // server should return { error: "Not enough stock…" }
                        showToast(payload.error || 'Could not add to cart.');
                        return;
                    }

                    // success path
                    document.getElementById('cart-badge').textContent = payload.count;

                    // quick feedback
                    const prev = btn.textContent;
                    btn.textContent = 'Added!';
                    setTimeout(() => btn.textContent = prev, 1200);

                } catch (err) {
                    console.error('Add to cart failed:', err);
                    showToast('Network error—please try again.');
                }
            });
            // Image Hover Effect
            document.querySelectorAll('.product-card img').forEach(img => {
                img.addEventListener('mouseenter', () => img.classList.add('scale-105', 'transition-transform',
                    'duration-300'));
                img.addEventListener('mouseleave', () => img.classList.remove('scale-105'));
            });

            // Header Scroll Effect
            const nav = document.querySelector('nav');
            window.addEventListener('scroll', () => {
                nav.classList.toggle('shadow-lg', window.scrollY > 100);
                nav.classList.toggle('bg-white/95', window.scrollY > 100);
                nav.classList.toggle('backdrop-blur-sm', window.scrollY > 100);
            });

            // Load More with Grouped Catalog Logic
            // 1) Pull in the raw grouped data from Blade
            const rawGrouped = @json($groupedProducts);

            // 2) Build a flat 'all' list so all is treated like any other category
            const categories = Object.keys(rawGrouped);
            const flatList = categories.flatMap(cat => rawGrouped[cat] || []);
            const groupedCatalog = {
                ...rawGrouped,
                all: flatList
            };

            // 3) Hook up DOM + state
            const catalogDiv = document.getElementById('catalog-grid');
            const btn = document.getElementById('load-more');
            let currentCat = 'all';
            let expanded = false;

            // 4) Card factory (unchanged)
            function makeCard(p, cat) {
                const d = document.createElement('div')
                d.className =
                    'product-card bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition cursor-pointer'

                // carry through your filtering category
                d.dataset.category = cat

                // now copy the same dataset you used in your blade templates:
                d.dataset.id = p.id
                d.dataset.name = p.name
                d.dataset.img = `/images/${p.image_url}`
                d.dataset.desc = p.description
                d.dataset.price = `Rp ${Number(p.price).toLocaleString('id-ID')}`
                d.dataset.pack = p.packaging.name
                d.dataset.recipe = JSON.stringify(
                    (p.flower_products || []).map(fp => ({
                        name: fp.flower.name,
                        qty: fp.quantity
                    }))
                )
                // ADD THIS LINE:
                d.dataset.reviews = JSON.stringify(
                    (p.limited_reviews || []).map(r => ({
                        rating: r.rating,
                        message: r.message,
                        created_at: r.created_at,
                        user: {
                            name: r.user.name
                        }
                    }))
                );

                // then build the innerHTML exactly as before
                d.innerHTML = `
    <div class="aspect-[1/1] overflow-hidden">
      <img src="/images/${p.image_url}"
           alt="${p.name}"
           class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
    </div>
    <div class="p-4">
      <h3 class="font-playfair text-xl font-semibold mb-2">${p.name}</h3>
      <p class="text-mocha-medium mb-3 truncate">${p.description}</p>
      <div class="flex justify-between items-center">
        <span class="text-mocha-burgundy font-semibold">
          Rp. ${Number(p.price).toLocaleString('id-ID')}
        </span>
        <button
  class="add-to-cart bg-mocha-burgundy text-white py-2 px-4 rounded-md hover:bg-opacity-90 transition"
  data-url="/cart/${p.id}"
>
  Add to Cart
</button>
      </div>
    </div>`

                return d
            }

            // 5) Render first `count` items for any category (including 'all')
            function render(cat, count) {
                catalogDiv.innerHTML = '';
                const list = groupedCatalog[cat] || [];
                for (let i = 0; i < Math.min(count, list.length); i++) {
                    catalogDiv.append(makeCard(list[i], cat));
                }
            }

            // 6) Show/hide and label the button based on total items
            function updateButton() {
                const total = (groupedCatalog[currentCat] || []).length;
                if (total <= 4) {
                    btn.style.display = 'none';
                } else {
                    btn.style.display = '';
                    btn.textContent = expanded ? 'Show Less' : 'Load More';
                }
            }

            // 7) Category‐tab clicks
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    // highlight
                    document.querySelectorAll('.category-tab').forEach(t => {
                        t.classList.remove('active', 'bg-mocha-burgundy', 'text-white');
                        t.classList.add('bg-mocha-gray', 'text-mocha-dark');
                    });
                    tab.classList.add('active', 'bg-mocha-burgundy', 'text-white');
                    tab.classList.remove('bg-mocha-gray', 'text-mocha-dark');

                    // state + initial render
                    currentCat = tab.dataset.category;
                    expanded = false;
                    render(currentCat, 4);
                    updateButton();
                });
            });

            // 8) Load More / Show Less clicks
            btn.addEventListener('click', () => {
                const total = (groupedCatalog[currentCat] || []).length;
                if (!expanded) {
                    render(currentCat, total); // show all
                    expanded = true;
                } else {
                    render(currentCat, 4); // back to first 4
                    expanded = false;
                }
                updateButton();
            });

            // 9) Initialize “all” on page load
            document.querySelector('.category-tab[data-category="all"]').click();
        </script>
        <script>
            const modal = document.getElementById('product-detail-modal');
            const imgEl = document.getElementById('modal-image');
            const setText = (id, txt) => document.getElementById(id).textContent = txt;
            const setHTML = (id, html) => document.getElementById(id).innerHTML = html;

            document.addEventListener('click', e => {


                // 1) if they clicked “Add to Cart” (or anything inside it), bail out
                if (e.target.closest('.add-to-cart')) {
                    return
                }

                // 2) otherwise, look for a card click
                const card = e.target.closest('.product-card, .card-clickable')
                if (!card) return

                // …populate & open your modal as before…
                const {
                    name,
                    img,
                    desc,
                    price,
                    pack,
                    recipe
                } = card.dataset
                const recList = JSON.parse(recipe || '[]')

                setText('modal-name', name)
                imgEl.src = img
                imgEl.alt = name
                setText('modal-desc', desc)
                setText('modal-price', price)
                setText('modal-packaging', pack)
                setHTML('modal-recipe',
                    recList.map(fp => `<li>${fp.name} × ${fp.qty}</li>`).join('')
                )

                // 4) grab the pre-loaded reviews JSON instead of filtering a global array
                const prodId = card.dataset.id;
                console.log("reviews payload:", card.dataset.reviews);
                const reviews = JSON.parse(card.dataset.reviews || '[]');
                console.log(reviews);

                // 5) build the HTML
                let reviewsHTML;
                if (reviews.length) {
                    reviewsHTML = reviews.map(r => `
      <div class="p-4 bg-mocha-cream rounded-lg">
        <div class="text-mocha-burgundy mb-1">
          ${'<i class="fas fa-star"></i>'.repeat(r.rating)}
        </div>
        <p class="italic mb-1">"${r.message}"</p>
        <div class="text-xs text-mocha-dark">— ${r.user.name}</div>
      </div>
    `).join('');
                } else {
                    reviewsHTML = `<p class="italic text-mocha-dark">No reviews yet :(</p>`;
                }

                // 6) inject into the modal
                document.getElementById('modal-reviews').innerHTML = reviewsHTML;
                panzoom.reset({
                    animate: false
                });

                modal.classList.remove('hidden')
                modal.classList.add('flex')
            })

            // close buttons & backdrop
            document.querySelectorAll('#modal-close, #modal-close-footer').forEach(btn =>
                btn.addEventListener('click', () => modal.classList.add('hidden'))
            );
            modal.addEventListener('click', e => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // 0) pick up our Blade->JS flag:
                window.siteSuggestionSent = @json(session('site_suggestion_sent', false));

                const form = document.getElementById('site-suggestion-form');
                const stars = document.querySelectorAll('#star-rating .star');
                const ratingInput = document.getElementById('rating');
                const submitBtn = form.querySelector('button[type="submit"]');

                function replaceWithThankYou() {
                    form.innerHTML =
                        '<p class="text-center text-lg font-medium text-mocha-dark">Thank you for your feedback!</p>';
                }

                // A) If server says they've already sent, hide immediately
                if (window.siteSuggestionSent || sessionStorage.getItem('siteSuggestionSent')) {
                    replaceWithThankYou();
                    return;
                }

                // 1) Star-rating widget
                stars.forEach(star => {
                    star.addEventListener('click', () => {
                        const v = Number(star.dataset.value);
                        ratingInput.value = v;
                        stars.forEach(s => {
                            s.classList.toggle('text-amber-500', Number(s.dataset.value) <= v);
                            s.classList.toggle('text-mocha-gray', Number(s.dataset.value) > v);
                        });
                    });
                });

                // 2) restore old on validation error
                const old = Number(ratingInput.value);
                if (old) stars.forEach(s =>
                    s.classList.toggle('text-amber-500', Number(s.dataset.value) <= old)
                );

                // 3) AJAX submit
                form.addEventListener('submit', async e => {
                    e.preventDefault();
                    submitBtn.textContent = 'Sending…';
                    submitBtn.disabled = true;

                    const payload = {
                        rating: ratingInput.value,
                        message: form.message.value.trim()
                    };

                    try {
                        const res = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            },
                            body: JSON.stringify(payload)
                        });
                        if (!res.ok) throw new Error(await res.text());

                        showToast('Thank you for your feedback!');
                        // mark as sent:
                        sessionStorage.setItem('siteSuggestionSent', '1');
                        replaceWithThankYou();

                    } catch (err) {
                        console.error(err);
                        showToast('Oops, something went wrong.', 'error');
                        submitBtn.textContent = 'Send Feedback';
                        submitBtn.disabled = false;
                    }
                });


            });
        </script>
</body>

</html>
