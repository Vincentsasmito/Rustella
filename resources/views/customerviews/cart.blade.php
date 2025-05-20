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



    <form method="POST" action="{{ route('cart.checkout') }}" enctype="multipart/form-data">
        @csrf
        {{-- carry the validated discount_id through --}}
        <input type="hidden" name="discount_id" id="discount-id" value="">

        <main class="pt-28">
            <!-- ===== CART SECTION ===== -->
            <section id="cart-section" class="checkout-section active container mx-auto px-4 md:px-8 pb-16">
                <h1 class="font-playfair text-3xl md:text-4xl font-bold text-center mb-10">
                    Your Shopping Cart
                </h1>

                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Cart Items -->
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
                                        <!-- Thumbnail + Name -->
                                        <div class="flex items-center space-x-4 flex-1">
                                            <div class="w-20 h-20 bg-mocha-light/30 rounded-lg overflow-hidden">
                                                <img src="{{ asset('images/' . $p->image_url) }}"
                                                    alt="{{ $p->name }}" class="w-full h-full object-cover" />
                                            </div>
                                            <div>
                                                <h3 class="font-playfair font-semibold">{{ $p->name }}</h3>
                                                <p class="text-sm text-mocha-medium">
                                                    {{ Str::limit($p->description, 50) }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Qty & Price & Remove -->
                                        <div class="flex items-center space-x-6">
                                            <div class="flex-shrink-0 flex items-center space-x-1">
                                                <button type="button"
                                                    class="qty-btn h-8 w-8 flex items-center justify-center">−
                                                </button>
                                                <input type="text" value="{{ $qty }}" readonly
                                                    class="item-qty w-10 h-8 text-center bg-transparent border border-mocha-light rounded" />
                                                <button type="button"
                                                    class="qty-btn h-8 w-8 flex items-center justify-center">+
                                                </button>
                                            </div>
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
                                            <button type="button"
                                                class="remove-item text-mocha-medium hover:text-mocha-burgundy">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center py-10">Your cart is empty.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:w-1/3">
                        <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                            <h2 class="font-playfair text-xl font-semibold mb-6">Order Summary</h2>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between">
                                    <span class="text-mocha-medium">Subtotal</span>
                                    <span id="order-subtotal" class="font-semibold">
                                        Rp.{{ number_format($subtotal ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>

                                {{-- Discount Input --}}
                                <div class="flex items-center space-x-2">
                                    <input id="discount-code" type="text" placeholder="Enter discount code"
                                        class="w-full px-4 py-2 border border-mocha-light rounded focus:ring-mocha-burgundy" />
                                    <button type="button" id="apply-discount"
                                        class="bg-mocha-burgundy text-white px-4 py-2 rounded hover:bg-opacity-90">
                                        Use
                                    </button>
                                </div>

                                {{-- Discount Result Row --}}
                                <div id="discount-row" class="flex justify-between hidden">
                                    <span class="text-mocha-medium">Discount</span>
                                    <span id="discount-amount" class="text-green-600 font-semibold">-</span>
                                </div>
                            </div>

                            <div class="border-t border-b border-mocha-light py-4 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold">Total</span>
                                    <span id="order-total" class="font-semibold text-xl">
                                        Rp.{{ number_format($total ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            @if (count($cart ?? []))
                                <button type="button" id="proceed-to-checkout"
                                    class="w-full bg-mocha-burgundy text-white py-3 rounded-md hover:bg-opacity-90 transition font-medium">
                                    Proceed to Checkout
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <!-- ===== DETAILS SECTION ===== -->
            <section id="details-section" class="checkout-section hidden container mx-auto px-4 md:px-8 pb-16">
                <h2 class="font-playfair text-3xl font-bold text-center mb-10">
                    Customer & Delivery Details
                </h2>

                <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="sender_email" class="block text-sm font-medium mb-1">Your Email *</label>
                            <input type="email" name="sender_email" id="sender_email"
                                value="{{ old('sender_email', $user->email) }}" readonly
                                class="w-full border border-mocha-light rounded px-3 py-2 bg-gray-100 cursor-not-allowed" />
                        </div>
                        <div>
                            <label for="sender_phone" class="block text-sm font-medium mb-1">Your Phone *</label>
                            <input type="tel" name="sender_phone" id="sender_phone" required
                                class="w-full border border-mocha-light rounded px-3 py-2" />
                        </div>
                        <div class="md:col-span-2">
                            <label for="sender_note" class="block text-sm font-medium mb-1">Note for Recipient</label>
                            <textarea name="sender_note" id="sender_note" class="w-full border border-mocha-light rounded px-3 py-2"></textarea>
                        </div>
                        <div>
                            <label for="recipient_name" class="block text-sm font-medium mb-1">Recipient Name
                                *</label>
                            <input type="text" name="recipient_name" id="recipient_name" required
                                class="w-full border border-mocha-light rounded px-3 py-2" />
                        </div>
                        <div>
                            <label for="recipient_phone" class="block text-sm font-medium mb-1">Recipient Phone
                                *</label>
                            <input type="tel" name="recipient_phone" id="recipient_phone" required
                                class="w-full border border-mocha-light rounded px-3 py-2" />
                        </div>
                        <div class="md:col-span-2">
                            <label for="recipient_address" class="block text-sm font-medium mb-1">Delivery Address
                                *</label>
                            <input type="text" name="recipient_address" id="recipient_address" required
                                class="w-full border border-mocha-light rounded px-3 py-2" />
                        </div>
                        <div>
                            <label for="deliveries_id" class="block text-sm font-medium mb-1">City / Subdistrict *
                            </label>
                            <select name="deliveries_id" id="deliveries_id" required
                                class="w-full border border-mocha-light rounded px-3 py-2">
                                @foreach ($deliveries as $d)
                                    <option value="{{ $d->id }}" data-fee="{{ $d->fee }}">
                                        {{ $d->city }}, {{ $d->subdistrict }} —
                                        Rp.{{ number_format($d->fee, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="delivery_time" class="block text-sm font-medium mb-1">Delivery Date & Time
                                *</label>
                            <input type="datetime-local" name="delivery_time" id="delivery_time" required
                                class="w-full border border-mocha-light rounded px-3 py-2" />
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" id="back-to-cart"
                            class="px-4 py-2 border border-mocha-medium rounded">
                            Back to Cart
                        </button>
                        <button type="button" id="proceed-to-payment"
                            class="px-4 py-2 bg-mocha-burgundy text-white rounded">
                            Proceed to Payment
                        </button>
                    </div>
                </div>
            </section>
        </main>


        </section>
        <!-- ===== PAYMENT SECTION ===== -->
        <section id="payment-section" class="checkout-section container mx-auto px-4 md:px-8 py-16">
            <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md text-center">
                <h2 class="font-playfair text-2xl font-bold mb-6">Transfer Payment</h2>
                <img src="https://via.placeholder.com/150x50?text=Bank+Logo" alt="Bank Logo" class="mx-auto mb-4">
                <p class="text-mocha-medium text-lg mb-2">Bank Mandiri</p>
                <p class="text-xl font-semibold tracking-widest mb-6">1234567890</p>

                <!-- breakdown table -->
                <table class="w-full text-left mb-8">
                    <tbody>
                        <tr>
                            <td class="py-1">Subtotal</td>
                            <td class="py-1 text-right" id="payment-subtotal">Rp.0</td>
                        </tr>
                        <tr>
                            <td class="py-1">Shipping Fee</td>
                            <td class="py-1 text-right" id="payment-fee">Rp.0</td>
                        </tr>
                        <tr class="border-t border-mocha-light">
                            <th class="pt-2">Total</th>
                            <th class="pt-2 text-right text-xl" id="amount-to-pay">Rp.0</th>
                        </tr>
                    </tbody>
                </table>

                <button id="continue-to-confirmation" type="button"
                    class="mt-6 bg-mocha-burgundy text-white px-6 py-3 rounded-md hover:bg-opacity-90 transition">
                    Saya Sudah Transfer
                </button>
            </div>
        </section>

        <section id="confirmation-section" class="checkout-section container mx-auto px-4 md:px-8 py-16">
            <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md text-center">
                <h2 class="font-playfair text-2xl font-bold mb-4">Upload Bukti Pembayaran</h2>
                <p class="text-mocha-medium mb-6">Silakan unggah bukti transfer Anda untuk memproses pesanan.</p>
                <input type="file" id="photo" name="photo" accept="image/*,.pdf"
                    class="block w-full text-sm text-mocha-dark file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-mocha-cream file:text-mocha-dark hover:file:bg-mocha-light mb-4"
                    required>
                <div id="preview-container" class="mb-4 hidden">
                    <p class="text-sm mb-2">Preview:</p>
                    <img id="preview-image" class="mx-auto max-h-60 border rounded shadow-md" alt="Preview">
                </div>
                <button type="submit"
                    class="bg-mocha-burgundy text-white px-6 py-2 rounded-md hover:bg-opacity-90 transition">Kirim
                    Bukti Pembayaran</button>
            </div>
        </section>
        </main>
    </form>


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
    </script>
    <script>
        document.getElementById('photo').addEventListener('change', function(event) {
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
        document.getElementById("proceed-to-payment")?.addEventListener("click", (e) => {
            e.preventDefault(); // Hindari reload

            // ── VALIDASI DETAIL FORM ───────────────────────────────────
            const detailSec = document.getElementById("details-section");
            let valid = true;
            detailSec.querySelectorAll('input[required], select[required]').forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add("border-red-500");
                } else {
                    field.classList.remove("border-red-500");
                }
            });
            if (!valid) {
                showToast("Please fill out all required fields before proceeding.", "error");
                return;
            }
            // 2) DELIVERY DATE & TIME RULES
            const dtInput = document.getElementById("delivery_time");
            const dtValue = dtInput.value;
            if (!dtValue) {
                showToast("Please choose a delivery date & time.", "error");
                dtInput.classList.add("border-red-500");
                return;
            }
            const dt = new Date(dtValue);
            const now = new Date();
            // Tomorrow as next calendar day at 00:00
            const tomorrow = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);

            // Must be at least tomorrow
            if (dt < tomorrow) {
                showToast("Delivery date must be at least one day ahead.", "error");
                dtInput.classList.add("border-red-500");
                return;
            }

            // Must be between 09:00 and 17:00 (17:00 allowed, >17:00 disallowed)
            const h = dt.getHours(),
                m = dt.getMinutes();
            if (h < 9 || h > 17 || (h === 17 && m > 0)) {
                showToast("Delivery time must be between 09:00 and 17:00.", "error");
                dtInput.classList.add("border-red-500");
                return;
            }
            dtInput.classList.remove("border-red-500");
            // 1) parse existing total (subtotal–discount)
            const rawTotal = document.getElementById("order-total").textContent
                .replace(/[^\d]/g, '');
            const baseTotal = parseInt(rawTotal, 10) || 0;

            // 2) get the selected shipping fee
            const fee = parseInt(
                document.querySelector('#deliveries_id option:checked').dataset.fee,
                10
            ) || 0;

            // 3) calculate grand total
            const grand = baseTotal + fee;

            // 4) inject into table
            document.getElementById("payment-subtotal").textContent =
                `Rp.${baseTotal.toLocaleString('id-ID')}`;
            document.getElementById("payment-fee").textContent =
                `Rp.${fee.toLocaleString('id-ID')}`;
            document.getElementById("amount-to-pay").textContent =
                `Rp.${grand.toLocaleString('id-ID')}`;

            showSection("payment");
        });
    </script>
    <script>
        //transfer button
        document.getElementById("continue-to-confirmation")?.addEventListener("click", () => {
            showSection("confirmation");
        });
    </script>


    <script>
        // 1) CSRF-aware fetch helper, with clone for debugging
        async function request(url, method, data = {}) {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const res = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: method !== 'DELETE' ? JSON.stringify(data) : null,
            });

            // clone so we can peek the raw text without consuming the main body
            const debugText = await res.clone().text();
            console.log('Raw Response:', debugText);

            // parse the real JSON payload
            const payload = await res.json();

            if (!res.ok) {
                // assume your API returns { error: "..." }
                throw new Error(payload.error || debugText || 'Request failed');
            }

            return payload;
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
            document.getElementById('order-subtotal').textContent = 'Rp. ' + subtotal.toLocaleString('id-ID');
            document.getElementById('order-total').textContent = 'Rp. ' + (subtotal).toLocaleString('id-ID');
        }

        // 3) Remove‐item button
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', async () => {
                const item = btn.closest('.cart-item');
                const id = item.dataset.id;
                try {
                    await request(`/cart/${id}`, 'DELETE');
                    item.remove();
                    updateCartTotal();
                } catch (e) {
                    console.error(e);
                    showToast('Could not remove item.');
                }

                const proceedBtn = document.getElementById('proceed-to-checkout');
                const hasItems = document.querySelectorAll('#cart-items .cart-item').length > 0;
                if (proceedBtn) {
                    // using Tailwind’s “hidden” class
                    proceedBtn.classList.toggle('hidden', !hasItems);
                    // OR via inline style:
                    // proceedBtn.style.display = hasItems ? 'block' : 'none';
                }
            });
        });

        // 4) Qty buttons → PATCH, with revert-on-failure
        document.querySelectorAll('.cart-item').forEach(item => {
            const id = item.dataset.id;
            item.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const input = item.querySelector('.item-qty');
                    const oldQty = parseInt(input.value, 10) || 0;
                    const delta = btn.textContent.trim() === '+' ? 1 : -1;
                    const newQty = Math.max(1, oldQty + delta);

                    // optimistically update UI
                    input.value = newQty;
                    updateCartTotal();

                    try {
                        await request(`/cart/${id}`, 'PATCH', {
                            quantity: newQty
                        });
                        // success: UI already shows newQty
                    } catch (err) {
                        console.error(err);
                        showToast(err.message || 'Could not update quantity.');
                        // revert UI back
                        input.value = oldQty;
                        updateCartTotal();
                    }
                });
            });
        });

        // 5) Kick it all off
        document.addEventListener('DOMContentLoaded', updateCartTotal);
    </script>

    <script>
        function resetDiscountUI(subtotal) {
            document.getElementById('discount-id').value = '';
            document.getElementById('discount-row').classList.add('hidden');
            document.getElementById('discount-amount').textContent = '';
            document.getElementById('order-total').textContent =
                `Rp. ${subtotal.toLocaleString('id-ID')}`;
        }
        document.getElementById('apply-discount').addEventListener('click', async () => {
            const code = document.getElementById('discount-code').value.trim();
            const subtotal = parseInt(
                (document.getElementById('order-subtotal').textContent || '0')
                .replace(/[^\d]/g, '')
            );

            if (!code){
                resetDiscountUI(subtotal);
                return showToast('Please enter a discount code.');
            } 

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
                    throw new Error(json?.message || json?.error?.message || 'Invalid discount.');
                }

                // ←— inject discount_id into hidden input
                document.getElementById('discount-id').value = json.discount_id;

                const discountAmount = parseInt(json.discount_amount || 0);
                const total = subtotal - discountAmount;

                document.getElementById('discount-amount').textContent =
                    `-Rp. ${discountAmount.toLocaleString('id-ID')}`;
                document.getElementById('order-total').textContent =
                    `Rp. ${total.toLocaleString('id-ID')}`;
                document.getElementById('discount-row').classList.remove('hidden');

                showToast(json.message); // show success
            } catch (err) {
                resetDiscountUI(subtotal);
                showToast(err.message || 'Discount code is invalid.');
            }
        });
    </script>

</body>

</html>
