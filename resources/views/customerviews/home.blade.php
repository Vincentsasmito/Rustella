<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            color: #322D29;
            background-color: #FFF;
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
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full z-10">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <div class="text-mocha-burgundy">
                        <img src="{{ asset('/WebsiteStockImage/Rustella.png') }}" alt="Rustella Logo"
                            class="h-8 w-auto">
                    </div>
                    <a href="#" class="font-playfair text-2xl font-bold text-mocha-dark">Rustella<span
                            class="text-mocha-medium">Floristry</span></a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="#" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Home</a>
                    <a href="#bestsellers" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Best
                        Sellers</a>
                    <a href="#catalog" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Catalog</a>
                    <a href="#about" class="text-mocha-dark hover:text-mocha-burgundy font-medium">About Us</a>
                    <a href="#contact" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Contact</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="menu-toggle" class="text-mocha-dark focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Cart Icon -->
                <div class="hidden md:block">
                    <button class="text-mocha-dark hover:text-mocha-burgundy">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="bg-mocha-burgundy text-white rounded-full px-2 py-1 text-xs">0</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white">
            <div class="container mx-auto px-4 py-2 space-y-3">
                <a href="#" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Home</a>
                <a href="#bestsellers" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Best
                    Sellers</a>
                <a href="#catalog" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Catalog</a>
                <a href="#about" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">About Us</a>
                <a href="#contact" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Contact</a>
                <a href="#" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">
                    <i class="fas fa-shopping-cart mr-2"></i> Cart
                    <span class="bg-mocha-burgundy text-white rounded-full px-2 py-1 text-xs">0</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 pb-16 md:pt-32 md:pb-24 bg-mocha-light/20">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 md:pr-12 mb-8 md:mb-0">
                    <h1 class="font-playfair text-4xl md:text-5xl lg:text-6xl font-bold text-mocha-dark mb-4">Personal
                        Touch of Art</h1>
                    <p class="text-mocha-medium text-lg mb-8">Hand-crafted arrangements that capture nature's beauty and
                        your unique style.</p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="#catalog"
                            class="bg-mocha-burgundy text-white py-3 px-6 rounded-md text-center hover:bg-opacity-90 transition">
                            Explore Catalog
                        </a>
                        <a href="#contact"
                            class="border border-mocha-medium text-mocha-dark py-3 px-6 rounded-md text-center hover:bg-mocha-light/30 transition">
                            Custom Order
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <img src="/WebsiteStockImage/homepage_landscape.png" alt="Elegant flower arrangement"
                        class="rounded-lg shadow-lg w-full">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg bg-mocha-cream hover:shadow-md transition">
                    <div class="inline-block p-4 rounded-full bg-mocha-light mb-4">
                        <i class="fas fa-truck text-2xl text-mocha-burgundy"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Same Day Delivery</h3>
                    <p class="text-mocha-medium">Order before 2pm for same-day flower delivery within the city.</p>
                </div>

                <div class="text-center p-6 rounded-lg bg-mocha-cream hover:shadow-md transition">
                    <div class="inline-block p-4 rounded-full bg-mocha-light mb-4">
                        <i class="fas fa-leaf text-2xl text-mocha-burgundy"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Fresh Guarantee</h3>
                    <p class="text-mocha-medium">All our flowers are guaranteed fresh for at least 7 days.</p>
                </div>

                <div class="text-center p-6 rounded-lg bg-mocha-cream hover:shadow-md transition">
                    <div class="inline-block p-4 rounded-full bg-mocha-light mb-4">
                        <i class="fas fa-paint-brush text-2xl text-mocha-burgundy"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Custom Designs</h3>
                    <p class="text-mocha-medium">Create your personalized arrangement with our expert florists.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Best Sellers Section -->
    <section id="bestsellers" class="py-16 bg-mocha-burgundy/10">
        <div class="container mx-auto px-4 md:px-8">
            <div class="text-center mb-12">
                <h2 class="font-playfair text-3xl md:text-4xl font-bold text-mocha-dark">Customer Classics</h2>
                <p class="text-mocha-medium mt-2">Our tried-and-true best sellers, loved by all.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($top3product as $product)
                    <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition relative">
                        <span class="bestseller-badge">
                            <i class="fas fa-star mr-1"></i> Best Seller
                        </span>

                        <div class="aspect-[1/1] overflow-hidden">
                            <img src="{{ asset('images/' . $product->image_url) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>

                        <div class="p-5">
                            <h3 class="font-playfair text-xl font-semibold mb-2">{{ $product->name }}</h3>
                            <p class="text-mocha-medium mb-3">{{ $product->description }}</p>

                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-mocha-burgundy font-semibold text-lg">
                                        Rp.{{ number_format($product->price, 0, ',', '.') }}
                                    </span>

                                    @if ($product->original_price && $product->original_price > $product->price)
                                        <span class="text-mocha-medium line-through ml-2">
                                            Rp.{{ number_format($product->original_price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>

                                <button
                                    class="add-to-cart bg-mocha-burgundy text-white py-2 px-4 rounded-md hover:bg-opacity-90 transition">
                                    Add to Cart
                                </button>
                            </div>

                            <div class="mt-3 text-amber-500">
                                {{-- Optional: Hardcoded stars or dynamic average rating --}}
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
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
            <div class="text-center mb-12">
                <h2 class="font-playfair text-3xl md:text-4xl font-bold text-mocha-dark">Our Collection</h2>
                <p class="text-mocha-medium mt-2">Browse our hand-crafted floral arrangements</p>
            </div>

            <!-- Category Tabs -->
            <div class="flex flex-wrap justify-center mb-10">
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
                    @php
                        // show the first item for this category
                        $product = $items[0] ?? null;
                    @endphp

                    @if ($product)
                        <div class="product-card" data-category="{{ $category }}">
                            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                                <div class="aspect-[1/1] overflow-hidden">
                                    <img src="{{ asset('images/' . $product->image_url) }}"
                                        alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-playfair text-xl font-semibold mb-2">{{ $product->name }}</h3>
                                    <p class="text-mocha-medium mb-3">{{ $product->description }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-mocha-burgundy font-semibold">
                                            Rp. {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                        <button
                                            class="add-to-cart bg-mocha-dark text-white py-2 px-4 rounded-md hover:bg-opacity-90 transition">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
                    <p class="text-mocha-medium mb-4 text-lg">Rustella......................</p>
                    <p class="text-mocha-medium mb-6">Text</p>

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

    <!-- Testimonial Section -->
    <section class="py-16 bg-mocha-cream">
        <div class="container mx-auto px-4 md:px-8">
            <div class="text-center mb-12">
                <h2 class="font-playfair text-3xl md:text-4xl font-bold text-mocha-dark">What Our Customers Say</h2>
                <p class="text-mocha-medium mt-2">Trusted by flower lovers across the city</p>
            </div>

            <div class="flex flex-wrap justify-center">
                <div class="w-full md:w-1/3 px-4 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-md h-full">
                        <div class="text-mocha-burgundy mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="italic mb-4">"Review Customers"</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-mocha-medium rounded-full flex items-center justify-center mr-3">
                                <span class="text-white font-semibold">S</span>
                            </div>
                            <div>
                                <h4 class="font-semibold">Seseorang</h4>
                                <p class="text-sm text-mocha-medium">Loyal Customer</p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>

    <!-- Subscribe Section -->
    <section class="py-16 bg-mocha-burgundy text-white">
        <div class="container mx-auto px-4 md:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="font-playfair text-3xl md:text-4xl font-bold mb-4">Join Our Flower Community</h2>

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

    <!-- Script -->
    <script>
        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        menuToggle.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

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

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-to-cart')) {
                itemsInCart++;
                updateCartUI();
                e.target.textContent = 'Added!';
                e.target.classList.replace('bg-mocha-dark', 'bg-mocha-burgundy');
                setTimeout(() => {
                    e.target.textContent = 'Add to Cart';
                    e.target.classList.replace('bg-mocha-burgundy', 'bg-mocha-dark');
                }, 1500);
                showToast('Item added to your cart!');
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
            const d = document.createElement('div');
            d.className = 'product-card';
            d.dataset.category = cat;
            d.innerHTML = `
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                    <div class="aspect-[1/1] overflow-hidden">
                    <img src="/images/${p.image_url}" alt="${p.name}"
                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                    </div>
                    <div class="p-4">
                    <h3 class="font-playfair text-xl font-semibold mb-2">${p.name}</h3>
                    <p class="text-mocha-medium mb-3">${p.description}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-mocha-burgundy font-semibold">
                        Rp. ${Number(p.price).toLocaleString('id-ID')}
                        </span>
                        <button class="add-to-cart bg-mocha-dark text-white py-2 px-4 rounded-md hover:bg-opacity-90 transition">
                        Add to Cart
                        </button>
                    </div>
                    </div>
                </div>`;
            return d;
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
</body>

</html>
