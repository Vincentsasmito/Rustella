<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rustella Florist - Cart & Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
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
                        playfair: ['Playfair Display', 'serif'],
                        montserrat: ['Montserrat', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            color: #322D29;
            background-color: #FFF;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .checkout-section {
            display: none;
        }

        .checkout-section.active {
            display: block;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #741D29;
            box-shadow: 0 0 0 2px rgba(116, 29, 41, 0.2);
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #EFE9E1;
            color: #322D29;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
        }

        .qty-btn:hover {
            background-color: #D1C7BD;
        }

        .remove-item {
            opacity: 0;
            transition: opacity 0.2s;
        }

        .cart-item:hover .remove-item {
            opacity: 1;
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
                    <a href="#" class="font-playfair text-2xl font-bold text-mocha-dark">
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
                    <a href="home"
                        class="text-mocha-dark hover:text-mocha-burgundy font-medium relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-mocha-burgundy after:transition-all after:duration-300 hover:after:w-full">Home</a>
                    <a href="home#bestsellers"
                        class="text-mocha-dark hover:text-mocha-burgundy font-medium relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-mocha-burgundy after:transition-all after:duration-300 hover:after:w-full">Best
                        Sellers</a>
                    <a href="home#catalog"
                        class="text-mocha-dark hover:text-mocha-burgundy font-medium relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-mocha-burgundy after:transition-all after:duration-300 hover:after:w-full">Catalog</a>
                    <a href="home#about"
                        class="text-mocha-dark hover:text-mocha-burgundy font-medium relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-mocha-burgundy after:transition-all after:duration-300 hover:after:w-full">About
                        Us</a>
                    <a href="home#contact"
                        class="text-mocha-dark hover:text-mocha-burgundy font-medium relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-mocha-burgundy after:transition-all after:duration-300 hover:after:w-full">Suggestion</a>

                </div>
                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden md:hidden bg-white px-4 py-2">
                    <a href="HomeNew.html" class="block py-2 text-mocha-dark hover:text-mocha-burgundy">Home</a>
                    <a href="HomeNew.html" class="block py-2 text-mocha-dark hover:text-mocha-burgundy">Best Sellers</a>
                    <a href="HomeNew.html#catalog"
                        class="block py-2 text-mocha-dark hover:text-mocha-burgundy">Catalog</a>
                    <a href="HomeNew.html#about" class="block py-2 text-mocha-dark hover:text-mocha-burgundy">About
                        Us</a>
                    <a href="HomeNew.html#contact"
                        class="block py-2 text-mocha-dark hover:text-mocha-burgundy">Contact</a>
                    <a href="Profieluser.html" class="block py-2 text-mocha-dark hover:text-mocha-burgundy">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <a href="#" class="block py-2 text-mocha-burgundy font-bold">
                        <i class="fas fa-shopping-cart mr-2"></i> Cart
                        <span class="bg-mocha-burgundy text-white rounded-full px-2 py-1 text-xs cart-count">3</span>
                    </a>


                </div>
    </nav>

    <!-- Sections -->
    <main class="pt-28">
        <section id="cart-section" class="checkout-section active container mx-auto px-4 md:px-8 pb-16">
            <h1 class="font-playfair text-3xl md:text-4xl font-bold text-center mb-10">Your Shopping Cart</h1>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- 2/3: Cart Items -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="font-playfair text-xl font-semibold mb-6">Items</h2>
                        <div id="cart-items" class="space-y-6">
                            @forelse($cart as $item)
                                @php
                                    $p = $item['product'];
                                    $qty = $item['quantity'];
                                @endphp

                                <div class="cart-item flex items-center py-4 border-b border-mocha-light"
                                    data-id="{{ $p->id }}" data-price="{{ $p->price }}">
                                    {{-- 1) LEFT: thumbnail + name --}}
                                    <div class="flex items-center space-x-4 flex-1">
                                        <div class="w-20 h-20 bg-mocha-light/30 rounded-lg overflow-hidden">
                                            <img src="{{ asset('images/' . $p->image_url) }}" alt="{{ $p->name }}"
                                                class="w-full h-full object-cover" />
                                        </div>
                                        <div>
                                            <h3 class="font-playfair font-semibold">{{ $p->name }}</h3>
                                            <p class="text-sm text-mocha-medium">{{ Str::limit($p->description, 50) }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- 2) RIGHT GROUP: qty controls, price, remove --}}
                                    <div class="flex items-center space-x-6">
                                        <!-- a) qty controls -->
                                        <div class="flex-shrink-0 flex items-center space-x-1">
                                            <button
                                                class="qty-btn h-8 w-8 p-0 leading-none flex items-center justify-center">−</button>
                                            <input type="text" value="{{ $qty }}"
                                                class="item-qty w-10 h-8 leading-none text-center bg-transparent border border-mocha-light rounded" />
                                            <button
                                                class="qty-btn h-8 w-8 p-0 leading-none flex items-center justify-center">+</button>
                                        </div>

                                        <!-- b) line total -->
                                        <div class="flex-shrink-0 text-right w-24 line-total">
                                            <p class="font-semibold">
                                                Rp.{{ number_format($p->price * $qty, 0, ',', '.') }}
                                            </p>
                                            @if ($p->original_price > $p->price)
                                                <p class="text-sm text-mocha-medium line-through">
                                                    Rp.{{ number_format($p->original_price * $qty, 0, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>


                                        <!-- c) remove -->
                                        <button
                                            class="flex-shrink-0 remove-item text-mocha-medium hover:text-mocha-burgundy">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center py-10">Your cart is empty.</p>
                            @endforelse
                        </div>

                    </div>
                </div> <!-- ← close the lg:w-2/3 here -->

                <!-- Order Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                        <h2 class="font-playfair text-xl font-semibold mb-6">Order Summary</h2>

                        {{-- Subtotal Row --}}
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-mocha-medium">Subtotal</span>
                                <span id="order-subtotal" class="font-semibold">Rp.
                                    {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                            </div>

                            {{-- Discount Input --}}
                            <div class="flex items-center space-x-2">
                                <input id="discount-code" type="text" placeholder="Enter discount code"
                                    class="w-full px-4 py-2 border border-mocha-light rounded focus:outline-none focus:ring-2 focus:ring-mocha-burgundy" />
                                <button id="apply-discount"
                                    class="bg-mocha-burgundy text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                                    Use
                                </button>
                            </div>

                            {{-- Discount Result Row --}}
                            <div id="discount-row" class="flex justify-between hidden">
                                <span class="text-mocha-medium">Discount</span>
                                <span id="discount-amount" class="text-green-600 font-semibold">-</span>
                            </div>
                        </div>

                        {{-- Total Row --}}
                        <div class="border-t border-b border-mocha-light py-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold">Total</span>
                                <span id="order-total" class="font-semibold text-xl">Rp.
                                    {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button id="proceed-to-checkout"
                            class="w-full bg-mocha-burgundy text-white py-3 rounded-md hover:bg-opacity-90 transition font-medium">
                            Proceed to Checkout
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <section id="details-section" class="checkout-section container mx-auto px-4 md:px-8 pb-16">
            <h2 class="font-playfair text-3xl font-bold text-center mb-10">Customer Details</h2>
            <form class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="first-name" class="block text-sm font-medium mb-1">First Name *</label>
                        <input type="text" id="first-name"
                            class="w-full border border-mocha-light rounded-md px-4 py-2" required>
                    </div>
                    <div>
                        <label for="last-name" class="block text-sm font-medium mb-1">Last Name *</label>
                        <input type="text" id="last-name"
                            class="w-full border border-mocha-light rounded-md px-4 py-2" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-1">Email Address *</label>
                    <input type="email" id="email"
                        class="w-full border border-mocha-light rounded-md px-4 py-2" required>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium mb-1">Phone Number *</label>
                    <input type="tel" id="phone"
                        class="w-full border border-mocha-light rounded-md px-4 py-2" required>
                </div>

                <h3 class="font-playfair text-2xl font-semibold mt-8 mb-4">Delivery Information</h3>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium mb-1">Address *</label>
                    <input type="text" id="address"
                        class="w-full border border-mocha-light rounded-md px-4 py-2 mb-2" required>
                    <input type="text" id="address2"
                        class="w-full border border-mocha-light rounded-md px-4 py-2"
                        placeholder="Apartment, suite, etc. (optional)">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="city" class="block text-sm font-medium mb-1">City *</label>
                        <input type="text" id="city"
                            class="w-full border border-mocha-light rounded-md px-4 py-2" required>
                    </div>
                    <div>
                        <label for="province" class="block text-sm font-medium mb-1">Province *</label>
                        <select id="province" class="w-full border border-mocha-light rounded-md px-4 py-2" required>
                            <option value="">Select Province</option>
                            <option value="DKI Jakarta">DKI Jakarta</option>
                            <option value="Banten">Banten</option>
                            <option value="Jawa Barat">Jawa Barat</option>
                            <option value="Jawa Tengah">Jawa Tengah</option>
                            <option value="Jawa Timur">Jawa Timur</option>
                        </select>
                    </div>
                    <div>
                        <label for="postal-code" class="block text-sm font-medium mb-1">Postal Code *</label>
                        <input type="text" id="postal-code"
                            class="w-full border border-mocha-light rounded-md px-4 py-2" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="delivery-notes" class="block text-sm font-medium mb-1">Delivery Notes
                        (Optional)</label>
                    <textarea id="delivery-notes" class="w-full border border-mocha-light rounded-md px-4 py-2" rows="3"
                        placeholder="Special instructions for delivery"></textarea>
                </div>

                <h3 class="font-playfair text-2xl font-semibold mt-8 mb-4">Delivery Options</h3>

                <div class="space-y-3 mb-6">
                    <label
                        class="flex items-center border border-mocha-light rounded-md px-4 py-3 cursor-pointer hover:bg-mocha-cream/30">
                        <input type="radio" name="delivery-option" value="standard" class="hidden" checked>
                        <div class="flex justify-between w-full">
                            <div>
                                <p class="font-medium">Standard Delivery</p>
                                <p class="text-sm text-mocha-medium">Delivery within 24 hours</p>
                            </div>
                            <div class="font-semibold">Rp.25.000</div>
                        </div>
                    </label>

                    <label
                        class="flex items-center border border-mocha-light rounded-md px-4 py-3 cursor-pointer hover:bg-mocha-cream/30">
                        <input type="radio" name="delivery-option" value="express" class="hidden">
                        <div class="flex justify-between w-full">
                            <div>
                                <p class="font-medium">Express Delivery</p>
                                <p class="text-sm text-mocha-medium">Delivery within 3 hours</p>
                            </div>
                            <div class="font-semibold">Rp.50.000</div>
                        </div>
                    </label>

                    <label
                        class="flex items-center border border-mocha-light rounded-md px-4 py-3 cursor-pointer hover:bg-mocha-cream/30">
                        <input type="radio" name="delivery-option" value="scheduled" class="hidden">
                        <div class="flex justify-between w-full">
                            <div>
                                <p class="font-medium">Scheduled Delivery</p>
                                <p class="text-sm text-mocha-medium">Choose your delivery date</p>
                            </div>

                            <div class="font-semibold">Rp.35.000</div>
                        </div>
                    </label>
                </div>



                <div class="flex justify-between mt-8">
                    <button type="button" id="back-to-cart"
                        class="inline-flex items-center border border-mocha-medium text-mocha-dark py-2 px-4 rounded-md hover:bg-mocha-light/30">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Cart
                    </button>
                    <button type="submit" id="continue-to-payment"
                        class="inline-flex items-center bg-mocha-burgundy text-white py-2 px-4 rounded-md hover:bg-opacity-90">
                        Continue to Payment <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </section>
        <section id="payment-section" class="checkout-section container mx-auto px-4 md:px-8 py-16">
            <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md text-center">
                <h2 class="font-playfair text-2xl font-bold mb-6">Transfer Payment</h2>
                <img src="https://via.placeholder.com/150x50?text=Bank+Logo" alt="Bank Logo" class="mx-auto mb-4">
                <p class="text-mocha-medium text-lg mb-2">Bank Mandiri</p>
                <p class="text-xl font-semibold tracking-widest">1234567890</p>
                <p class="text-sm text-mocha-medium mt-1">a.n. Rustella Florist</p>
            </div>
            <button id="continue-to-confirmation"
                class="mt-6 bg-mocha-burgundy text-white px-6 py-3 rounded-md hover:bg-opacity-90 transition">
                Saya Sudah Transfer
            </button>
        </section>

        <section id="confirmation-section" class="checkout-section container mx-auto px-4 md:px-8 py-16">
            <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md text-center">
                <h2 class="font-playfair text-2xl font-bold mb-4">Upload Bukti Pembayaran</h2>
                <p class="text-mocha-medium mb-6">Silakan unggah bukti transfer Anda untuk memproses pesanan.</p>
                <form id="payment-proof-form">
                    <input type="file" id="payment-proof" accept="image/*,.pdf"
                        class="block w-full text-sm text-mocha-dark file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-mocha-cream file:text-mocha-dark hover:file:bg-mocha-light mb-4"
                        required>
                    <div id="preview-container" class="mb-4 hidden">
                        <p class="text-sm mb-2">Preview:</p>
                        <img id="preview-image" class="mx-auto max-h-60 border rounded shadow-md" alt="Preview">
                    </div>
                    <button type="submit"
                        class="bg-mocha-burgundy text-white px-6 py-2 rounded-md hover:bg-opacity-90 transition">Kirim
                        Bukti Pembayaran</button>
                </form>
            </div>
        </section>
    </main>



    <script>
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `
        fixed bottom-4 right-4 z-50
        px-4 py-2 rounded shadow-lg text-white
        transition-opacity duration-300 opacity-0
        ${type === 'error' ? 'bg-red-600' : type === 'success' ? 'bg-green-600' : 'bg-mocha-dark'}
    `;
            toast.textContent = message;

            document.body.appendChild(toast);
            setTimeout(() => toast.classList.replace('opacity-0', 'opacity-100'), 100);
            setTimeout(() => {
                toast.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        document.getElementById('menu-toggle').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    <script>
        document.getElementById('payment-proof').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.src = '';
                previewContainer.classList.add('hidden');
            }
        });

        document.getElementById('payment-proof-form').addEventListener('submit', function(e) {
            e.preventDefault();
            showToast('Bukti pembayaran berhasil dikirim!');
            // Implementasi upload ke server akan ditambahkan di sini
        });
    </script>
    <script>
        // Toggle input radio secara manual agar tetap berfungsi saat input disembunyikan
        document.querySelectorAll('label input[type="radio"][name="delivery-option"]').forEach(radio => {
            radio.parentElement.addEventListener('click', () => {
                radio.checked = true;
            });
        });

        // Tampilkan input tanggal/waktu jika pilih Scheduled Delivery
        document.querySelectorAll('input[name="delivery-option"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const scheduledOptions = document.getElementById('scheduled-delivery-options');
                if (this.value === 'scheduled') {
                    scheduledOptions?.classList.remove('hidden');
                } else {
                    scheduledOptions?.classList.add('hidden');
                }
            });
        });
    </script>

    <script>
        // Navigasi antar section
        const sections = ["cart", "details", "payment", "confirmation"];

        function showSection(sectionId) {
            sections.forEach(id => {
                const sec = document.getElementById(`${id}-section`);
                if (sec) sec.classList.remove("active");
            });
            document.getElementById(`${sectionId}-section`).classList.add("active");
            window.scrollTo(0, 0); // Scroll ke atas saat ganti halaman
        }

        // Tombol navigasi
        document.getElementById("proceed-to-checkout")?.addEventListener("click", () => showSection("details"));
        document.getElementById("back-to-cart")?.addEventListener("click", () => showSection("cart"));
        document.getElementById("continue-to-payment")?.addEventListener("click", (e) => {
            e.preventDefault(); // Hindari reload
            showSection("payment");
        });

        document.getElementById("payment-proof-form")?.addEventListener("submit", function(e) {
            e.preventDefault();
            showToast("Bukti pembayaran berhasil dikirim!");
            showSection("confirmation");
        });
    </script>
    <script>
        //transfer button
        document.getElementById("continue-to-confirmation")?.addEventListener("click", () => {
            showSection("confirmation");
        });
    </script>


    <script>
        // 1) CSRF‐aware fetch helper
        async function request(url, method, data = {}) {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const res = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: method !== 'DELETE' ? JSON.stringify(data) : null
            });
            const text = await res.text();
            console.log('Raw Response:', text); // ← look for an HTML page here
            if (!res.ok) throw new Error(await res.text());
            return res.json();
        }

        // 2) Recompute badge, line totals & summary
        function updateCartTotal() {
            // badge
            let badgeCount = 0;
            document.querySelectorAll('#cart-items .item-qty').forEach(input => {
                badgeCount += Math.max(0, parseInt(input.value, 10) || 0);
            });
            document.querySelectorAll('.cart-count').forEach(el => el.textContent = badgeCount);

            // line totals & subtotal
            let subtotal = 0;
            document.querySelectorAll('#cart-items .cart-item').forEach(item => {
                const qty = parseInt(item.querySelector('.item-qty').value, 10) || 0;
                const unit = parseFloat(item.dataset.price) || 0;
                const row = qty * unit;
                subtotal += row;
                const lineEl = item.querySelector('.line-total p:first-child');
                if (lineEl) lineEl.textContent = 'Rp.' + row.toLocaleString('id-ID');
            });

            // summary
            document.getElementById('order-subtotal').textContent = 'Rp.' + subtotal.toLocaleString('id-ID');
            document.getElementById('order-total').textContent = 'Rp.' + (subtotal).toLocaleString('id-ID');
        }

        // 3) Remove‐item button
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', async () => {
                const item = btn.closest('.cart-item');
                const id = item.dataset.id;
                if (!confirm('Remove this item?')) return;
                try {
                    await request(`/cart/${id}`, 'DELETE');
                    item.remove();
                    updateCartTotal();
                } catch (e) {
                    console.error(e);
                    showToast('Could not remove item.');
                }
            });
        });

        // 4) Qty buttons → PATCH
        document.querySelectorAll('.cart-item').forEach(item => {
            const id = item.dataset.id;
            item.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const input = item.querySelector('.item-qty');
                    let qty = parseInt(input.value, 10) + (btn.textContent.trim() === '+' ? 1 :
                        -1);
                    qty = Math.max(1, qty);
                    input.value = qty;

                    try {
                        await request(`/cart/${id}`, 'PATCH', {
                            quantity: qty
                        });
                        updateCartTotal();
                    } catch (e) {
                        console.error(e);
                        showToast('Could not update quantity.');
                    }
                });
            });
        });

        // 5) Kick it all off
        document.addEventListener('DOMContentLoaded', updateCartTotal);
    </script>

    <script>
        document.getElementById('apply-discount').addEventListener('click', async () => {
            const code = document.getElementById('discount-code').value.trim();
            const subtotal = parseInt(
                (document.getElementById('order-subtotal').textContent || '0').replace(/[^\d]/g, '')
            );

            if (!code) return showToast('Please enter a discount code.');

            try {
                const res = await fetch('/cart/discount', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        code,
                        subtotal
                    })
                });

                const json = await res.json();

                if (!res.ok) {
                    throw new Error(json?.error?.message || 'Invalid discount.');
                }

                const discountAmount = parseInt(json.discount_amount || 0);
                const total = subtotal - discountAmount;

                document.getElementById('discount-amount').textContent =
                    `-Rp. ${discountAmount.toLocaleString('id-ID')}`;
                document.getElementById('order-total').textContent =
                    `Rp. ${total.toLocaleString('id-ID')}`;
                document.getElementById('discount-row').classList.remove('hidden');

                showToast(json.message); // show success
            } catch (err) {
                showToast(err.message || 'Discount code is invalid.');
            }
        });
    </script>

</body>

</html>
