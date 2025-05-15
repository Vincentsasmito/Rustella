<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Rustella Florist</title>
    {{-- expose Laravel’s CSRF token for any AJAX you might add later --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        // total items in cart (session)
        $cartCount = array_sum(session('cart', []));
    @endphp

    {{-- make it available in JS if needed --}}
    <script>
        window.RustellaCart = {
            count: {{ $cartCount }}
        };
    </script>
</head>
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

    .tab-active {
        color: #741D29;
        border-bottom: 2px solid #741D29;
    }

    .order-status {
        position: relative;
        padding-left: 24px;
    }

    .order-status:before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    /* Payment Pending – Yellowish */
    .status-pending:before {
        background-color: #F59E0B;
    }

    /* On Progress – Blue */
    .status-on-progress:before {
        background-color: #3B82F6;
    }

    /* Ready to Deliver – Blue */
    .status-ready-to-deliver:before {
        background-color: #3B82F6;
    }

    /* Delivery – Blue */
    .status-delivery:before {
        background-color: #3B82F6;
    }

    /* Completed – Green */
    .status-completed:before {
        background-color: #10B981;
    }

    /* Cancelled – Red */
    .status-cancelled:before {
        background-color: #EF4444;
    }
</style>
</head>

<body class="bg-mocha-cream/30">
    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full z-10">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <div class="text-mocha-burgundy">
                        <i class="fas fa-flower text-2xl"></i>
                    </div>
                    <a href="home" class="font-playfair text-2xl font-bold text-mocha-dark">Rustella<span
                            class="text-mocha-burgundy">Floristry</span></a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="home" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Home</a>
                    <a href="home#bestsellers" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Best
                        Sellers</a>
                    <a href="home#catalog" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Catalog</a>
                    <a href="home#about" class="text-mocha-dark hover:text-mocha-burgundy font-medium">About
                        Us</a>
                    <a href="home#contact" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Suggestion</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="menu-toggle" class="text-mocha-dark focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Cart Icon -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="UserProfile.html" class="text-mocha-burgundy hover:text-mocha-dark">
                        <i class="fas fa-user text-xl"></i>
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="text-mocha-dark hover:text-mocha-burgundy transition-colors duration-300 relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span id="cart-badge"
                            class="bg-mocha-burgundy text-white rounded-full px-2 py-1 text-xs absolute -top-2 -right-2">
                            {{ $cartCount }}
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white">
            <div class="container mx-auto px-4 py-2 space-y-3">
                <a href="home" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Home</a>
                <a href="home#bestsellers" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Best
                    Sellers</a>
                <a href="home#catalog"
                    class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Catalog</a>
                <a href="home#about" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">About
                    Us</a>
                <a href="home#contact"
                    class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Suggestion</a>
                <a href="#" class="block text-mocha-burgundy hover:text-mocha-dark font-medium py-2">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>
                <a href="{{ route('cart.index') }}"
                    class="text-mocha-dark hover:text-mocha-burgundy transition-colors duration-300 relative">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span id="cart-badge"
                        class="bg-mocha-burgundy text-white rounded-full px-2 py-1 text-xs absolute -top-2 -right-2">
                        {{ $cartCount }}
                    </span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-16">
        <div class="container mx-auto px-4 md:px-8">
            <!-- Profile Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center md:items-start">

                    <!-- Logo Circle -->
                    <div
                        class="w-24 h-24 rounded-full overflow-hidden mb-4 md:mb-0 md:mr-6
             bg-gradient-to-br from-mocha-burgundy to-mocha-dark
             ring-2 ring-mocha-light">
                        <img src="{{ asset('WebsiteStockImage/rustellalogoplain.png') }}" alt="Rustella Logo"
                            class="w-full h-full object-contain p-2 bg-white/10" />
                    </div>

                    <!-- Name & Email -->
                    <div class="text-center md:text-left">
                        <!-- User Name -->
                        <h1 class="font-playfair text-2xl md:text-3xl font-bold text-mocha-dark mb-1">
                            {{ $user->name }}
                        </h1>

                        <!-- User Email -->
                        <p class="text-sm text-mocha-medium mb-4">
                            <a href="mailto:{{ $user->email }}" class="hover:underline">
                                {{ $user->email }}
                            </a>
                        </p>

                        <!-- Member since -->
                        <p class="text-mocha-medium mb-4">
                            Member since: {{ $user->created_at->format('F Y') }}
                        </p>

                        <!-- Stats -->
                        <div class="flex flex-wrap justify-center md:justify-start gap-4">
                            <div class="bg-mocha-cream px-4 py-2 rounded-md">
                                <p class="text-sm text-mocha-dark">Total Orders</p>
                                <p class="font-bold text-mocha-burgundy text-xl">{{ $orderCount }}</p>
                            </div>
                            <div class="bg-mocha-cream px-4 py-2 rounded-md">
                                <p class="text-sm text-mocha-dark">My Reviews</p>
                                <p class="font-bold text-mocha-burgundy text-xl">
                                    {{ $suggestionCount }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Button -->
                    <div class="mt-6 md:mt-0 md:ml-auto">
                        <button id="openEditModal"
                            class="bg-mocha-light/80 text-mocha-dark py-2 px-4 rounded-md hover:bg-mocha-light transition flex items-center">
                            <i class="fas fa-edit mr-2"></i> Edit Profile
                        </button>
                    </div>
                </div>
            </div>


            <!-- Tabs -->
            <div class="border-b border-mocha-light mb-8">
                <div class="flex flex-wrap">
                    <button id="tab-orders" class="tab-active py-3 px-6 font-medium text-lg">Order History</button>
                    <button id="tab-reviews" class="py-3 px-6 font-medium text-lg text-mocha-medium">My Reviews</button>
                </div>
            </div>

            <!-- Tab Content -->
            <div id="content-orders" class="tab-content">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6 border-b border-mocha-light">
                        <h2 class="font-playfair text-xl font-semibold text-mocha-dark mb-2">My Orders</h2>
                        <p class="text-mocha-medium">View and manage your order history</p>
                    </div>

                    {{-- Order Filters (auto‐submits on status change) --}}
                    <form method="GET" action="{{ route('profile.index') }}">
                        <div class="p-4 border-b border-mocha-light bg-mocha-cream/30">
                            <div class="flex items-center space-x-2">
                                <label for="order-progress" class="text-sm text-mocha-dark">Filter by status:</label>
                                <select name="progress" id="order-progress" onchange="this.form.submit()"
                                    class="border border-mocha-light rounded-md px-3 py-1 text-sm">
                                    @php
                                        $options = [
                                            'all' => 'All Orders',
                                            'Payment Pending' => 'Payment Pending',
                                            'On Progress' => 'On Progress',
                                            'Ready to Deliver' => 'Ready to Deliver',
                                            'Delivery' => 'Delivery',
                                            'Completed' => 'Completed',
                                            'Cancelled' => 'Cancelled',
                                        ];
                                        $current = request('progress', 'all');
                                    @endphp

                                    @foreach ($options as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ $current === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    {{-- Orders List --}}
                    <div class="divide-y divide-mocha-light">
                        @forelse($orders as $order)
                            @php
                                $map = [
                                    'Payment Pending' => 'status-pending',
                                    'On Progress' => 'status-on-progress',
                                    'Ready to Deliver' => 'status-ready-to-deliver',
                                    'Delivery' => 'status-delivery',
                                    'Completed' => 'status-completed',
                                    'Cancelled' => 'status-cancelled',
                                ];
                                $cls = $map[$order->progress] ?? 'status-pending';
                            @endphp

                            <div class="p-6 hover:bg-mocha-cream/10 transition">

                                {{-- Header --}}
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-4">
                                    <div>
                                        <p class="text-sm text-mocha-medium mb-1">Order #{{ $order->id }}</p>
                                        <p class="font-semibold">{{ $order->created_at->format('F j, Y') }}</p>
                                    </div>

                                    <span class="order-status {{ $cls }} font-medium">
                                        {{ $order->progress }}
                                    </span>

                                    <p class="font-bold text-mocha-burgundy">
                                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                    </p>

                                    <div class="mt-3 lg:mt-0 flex space-x-2">
                                        <button type="button"
                                            class="details-btn bg-mocha-light/50 text-mocha-dark text-sm py-1 px-3 rounded hover:bg-mocha-light transition"
                                            data-order-id        ="{{ $order->id }}"
                                            data-recipient-name  ="{{ $order->recipient_name }}"
                                            data-sender-email    ="{{ $order->sender_email }}"
                                            data-recipient-phone ="{{ $order->recipient_phone }}"
                                            data-recipient-address="{{ $order->recipient_address }}"
                                            data-order-date      ="{{ $order->created_at->format('F j, Y') }}"
                                            data-status          ="{{ $order->progress }}"
                                            data-location        ="{{ optional($order->delivery)->city }}, {{ optional($order->delivery)->subdistrict }}"
                                            data-items='@json($order->items_json)'
                                            data-subtotal        ="{{ $order->subtotal }}"
                                            data-discount        ="{{ $order->discount_amount }}"
                                            data-delivery-fee    ="{{ $order->delivery_fee }}"
                                            data-grand-total     ="{{ $order->grand_total }}"
                                            data-payment-url     ="{{ $order->payment_url ?? '' }}"
                                            data-sender-note     ="{{ $order->sender_note }}">
                                            Details </button>

                                        @if ($order->progress === 'Payment Pending')
                                            <form action="{{ route('orders.uploadPayment', $order) }}" method="POST"
                                                enctype="multipart/form-data" class="inline-block">
                                                @csrf
                                                <input type="file" name="photo" accept="image/*"
                                                    class="hidden js-upload-input" onchange="this.form.submit()">
                                                <button type="button"
                                                    class="bg-mocha-burgundy text-white text-sm py-1 px-3 rounded hover:bg-opacity-90 transition"
                                                    onclick="this.closest('form').querySelector('.js-upload-input').click()">
                                                    {{ $order->payment_url ? 'Edit Payment Screenshot' : 'Upload Payment Screenshot' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                {{-- Line Items (will wrap to new rows automatically) --}}
                                <div class="flex flex-wrap gap-6 mb-4">
                                    @foreach ($order->orderProducts as $op)
                                        <div class="flex items-center space-x-3 flex-shrink-0">
                                            <div class="w-16 h-16 rounded-md overflow-hidden">
                                                <img src="{{ asset('Images/' . $op->product->image_url) }}"
                                                    alt="{{ $op->product->name }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <p class="font-medium">{{ $op->product->name }}</p>
                                                <p class="text-sm text-mocha-medium">Qty: {{ $op->quantity }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Totals --}}
                                <div class="flex flex-col md:flex-row md:justify-end md:space-x-6 text-sm">
                                    <div>
                                        Subtotal:
                                        <span class="font-medium">
                                            Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    @if ($order->discount_amount > 0)
                                        <div>
                                            Discount:
                                            <span class="font-medium text-red-600">-Rp
                                                {{ number_format($order->discount_amount, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endif

                                    <div>
                                        Shipping:
                                        <span class="font-medium">
                                            Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="font-semibold text-mocha-burgundy">
                                        Total:
                                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                    </div>
                                </div>

                            </div>
                        @empty
                            <div class="p-6 text-center text-mocha-medium">
                                You have no orders yet.
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="p-6 border-t border-mocha-light">
                        {{ $orders->withQueryString()->links() }}
                    </div>


                    <!-- Reviews Tab Content -->
                    <div id="content-reviews" class="tab-content hidden">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="p-6 border-b border-mocha-light">
                                <h2 class="font-playfair text-xl font-semibold text-mocha-dark mb-2">My Reviews</h2>
                                <p class="text-mocha-medium">View and manage your product reviews</p>
                            </div>

                            <!-- Review Filters -->
                            <div class="p-4 border-b border-mocha-light bg-mocha-cream/30">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <div class="flex items-center space-x-2">
                                        <label for="review-rating" class="text-sm text-mocha-dark">Filter by
                                            rating:</label>
                                        <select id="review-rating"
                                            class="border border-mocha-light rounded-md px-3 py-1 text-sm">
                                            <option value="all">All Ratings</option>
                                            <option value="5">5 Stars</option>
                                            <option value="4">4 Stars</option>
                                            <option value="3">3 Stars</option>
                                            <option value="2">2 Stars</option>
                                            <option value="1">1 Star</option>
                                        </select>
                                    </div>
                                    <div class="relative">
                                        <input type="text" placeholder="Search reviews..."
                                            class="border border-mocha-light rounded-md pl-8 pr-3 py-1 w-full focus:outline-none focus:ring-1 focus:ring-mocha-burgundy">
                                        <i
                                            class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-mocha-medium"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Reviews List -->
                            <div class="divide-y divide-mocha-light">
                                <!-- Review 1 -->
                                <div class="p-6 hover:bg-mocha-cream/10 transition">
                                    <div class="flex flex-col md:flex-row md:items-start justify-between mb-4">
                                        <div class="flex items-start mb-4 md:mb-0">
                                            <div class="w-16 h-16 rounded-md overflow-hidden mr-4">
                                                <img src="/api/placeholder/100/100" alt="Bunga 1"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <h3 class="font-medium mb-1">Bunga 1</h3>
                                                <div class="flex text-amber-500 mb-1">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                                <p class="text-sm text-mocha-medium">Reviewed on: April 18, 2025</p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button class="text-mocha-burgundy hover:text-mocha-dark transition">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-mocha-medium hover:text-mocha-dark transition">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bg-mocha-cream/30 p-4 rounded-md">
                                        <p class="text-mocha-dark mb-3">These flowers were absolutely beautiful! The
                                            arrangement was exactly as pictured and they stayed fresh for over a week.
                                            I'm very
                                            satisfied with my purchase and will definitely order again.</p>
                                        <div class="flex flex-wrap gap-2 mt-3">
                                            <div class="w-16 h-16 rounded-md overflow-hidden">
                                                <img src="/api/placeholder/100/100" alt="Review image"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <div class="w-16 h-16 rounded-md overflow-hidden">
                                                <img src="/api/placeholder/100/100" alt="Review image"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Review 2 -->
                                <div class="p-6 hover:bg-mocha-cream/10 transition">
                                    <div class="flex flex-col md:flex-row md:items-start justify-between mb-4">
                                        <div class="flex items-start mb-4 md:mb-0">
                                            <div class="w-16 h-16 rounded-md overflow-hidden mr-4">
                                                <img src="/api/placeholder/100/100" alt="Bunga 2"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <h3 class="font-medium mb-1">Bunga 2</h3>
                                                <div class="flex text-amber-500 mb-1">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <p class="text-sm text-mocha-medium">Reviewed on: April 2, 2025</p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button class="text-mocha-burgundy hover:text-mocha-dark transition">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-mocha-medium hover:text-mocha-dark transition">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bg-mocha-cream/30 p-4 rounded-md">
                                        <p class="text-mocha-dark">Very satisfied with my purchase. The flowers were
                                            fresh and
                                            beautifully arranged. Would have given 5 stars but delivery was a bit later
                                            than
                                            expected. Otherwise perfect!</p>
                                    </div>
                                </div>

                                <!-- Review 3 -->
                                <div class="p-6 hover:bg-mocha-cream/10 transition">
                                    <div class="flex flex-col md:flex-row md:items-start justify-between mb-4">
                                        <div class="flex items-start mb-4 md:mb-0">
                                            <div class="w-16 h-16 rounded-md overflow-hidden mr-4">
                                                <img src="/api/placeholder/100/100" alt="Bunga 3"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <h3 class="font-medium mb-1">Bunga 3</h3>
                                                <div class="flex text-amber-500 mb-1">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                    <i class="far fa-star"></i>
                                                </div>
                                                <p class="text-sm text-mocha-medium">Reviewed on: March 25, 2025</p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button class="text-mocha-burgundy hover:text-mocha-dark transition">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-mocha-medium hover:text-mocha-dark transition">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bg-mocha-cream/30 p-4 rounded-md">
                                        <p class="text-mocha-dark">Nice arrangement overall, but the wrapping was a bit
                                            crumpled. Still a good experience!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="editProfileModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden">
                    <div class="bg-white rounded-lg w-full max-w-lg p-6 relative">
                        <!-- Close button -->
                        <button id="closeEditModal"
                            class="absolute top-4 right-4 text-gray-500 hover:text-gray-900 text-2xl">&times;</button>

                        <h2 class="text-xl font-semibold mb-4">Edit Profile</h2>

                        <!-- Tabs -->
                        <div class="flex border-b border-mocha-light mb-6">
                            <button id="tabInfoBtn" class="px-4 py-2 font-medium border-b-2 border-b-mocha-burgundy">
                                Profile Info
                            </button>
                            <button id="tabPassBtn" class="px-4 py-2 font-medium text-mocha-medium">
                                Change Password
                            </button>
                        </div>

                        <!-- Profile Info Form -->
                        <div id="tabInfo">
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf @method('PUT')
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" value="{{ $user->name }}"
                                        required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="bg-mocha-burgundy text-white px-4 py-2 rounded-md hover:bg-opacity-90">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Change Password Form -->
                        <div id="tabPass" class="hidden">
                            <form action="{{ route('profile.password.update') }}" method="POST">
                                @csrf @method('PUT')
                                <div class="mb-4">
                                    <label for="current_password"
                                        class="block text-sm font-medium text-gray-700">Current
                                        Password</label>
                                    <input type="password" name="current_password" id="current_password" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">New
                                        Password</label>
                                    <input type="password" name="password" id="password" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                                </div>
                                <div class="mb-4">
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700">Confirm
                                        Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="bg-mocha-burgundy text-white px-4 py-2 rounded-md hover:bg-opacity-90">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Order Detail Modal -->
                <div id="order-detail-modal"
                    class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center overflow-y-auto z-50">
                    <!-- This wrapper gives us vertical centering & padding -->
                    <div class="flex items-start sm:items-center justify-center min-h-screen p-4 w-full">
                        <!-- The actual panel -->
                        <div
                            class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-auto
             max-h-[90vh] flex flex-col overflow-hidden">
                            <!-- Header -->
                            <div class="flex justify-between items-center p-4 border-b">
                                <h3 class="text-lg font-semibold">
                                    Order <span id="modal-order-id"></span>
                                </h3>
                                <button class="close-modal text-mocha-dark hover:text-mocha-burgundy">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <!-- Body (scrollable) -->
                            <div class="overflow-y-auto p-6 flex-1">
                                <!-- Customer & Order Info -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <!-- Customer Info -->
                                    <div>
                                        <h4 class="text-sm font-medium mb-3">Customer Information</h4>
                                        <div class="bg-mocha-cream/20 p-4 rounded-lg">
                                            <p class="text-sm mb-2"><span class="font-medium">Name:</span> <span
                                                    id="modal-customer-name"></span></p>
                                            <p class="text-sm mb-2"><span class="font-medium">Email:</span> <span
                                                    id="modal-customer-email"></span></p>
                                            <p class="text-sm mb-2"><span class="font-medium">Phone:</span> <span
                                                    id="modal-recipient-phone"></span></p>
                                            <p class="text-sm"><span class="font-medium">Address:</span> <span
                                                    id="modal-recipient-address"></span></p>
                                        </div>
                                    </div>
                                    <!-- Order Info -->
                                    <div>
                                        <h4 class="text-sm font-medium mb-3">Order Information</h4>
                                        <div class="bg-mocha-cream/20 p-4 rounded-lg">
                                            <p class="text-sm mb-2"><span class="font-medium">Order Date:</span> <span
                                                    id="modal-order-date"></span></p>
                                            <p class="text-sm mb-2">
                                                <span class="font-medium">Payment Screenshot:</span><br>
                                                <img id="modal-payment-screenshot" src="" alt="Payment"
                                                    class="w-32 h-auto hidden rounded" />
                                            </p>
                                            <p class="text-sm mb-2"><span class="font-medium">Status:</span> <span
                                                    id="modal-current-status" class="font-semibold"></span></p>
                                            <p class="text-sm"><span class="font-medium">Location:</span> <span
                                                    id="modal-location"></span></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Items Table -->
                                <h4 class="text-sm font-medium mb-3">Order Items</h4>
                                <div class="overflow-x-auto mb-6">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="border-b border-mocha-light/30">
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                                    Image</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                                    Product</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                                    Unit Price</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                                    Quantity</th>
                                                <th
                                                    class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase">
                                                    Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="modal-order-items">

                                        </tbody>
                                        <tfoot>
                                            <tr class="border-b border-mocha-light/20">
                                                <td colspan="4" class="px-4 py-3 text-right font-medium">Subtotal
                                                </td>
                                                <td class="px-4 py-3 text-right" id="modal-subtotal"></td>
                                            </tr>
                                            <tr id="modal-discount-row" class="border-b border-mocha-light/20 hidden">
                                                <td colspan="4" class="px-4 py-3 text-right font-medium">Discount
                                                </td>
                                                <td class="px-4 py-3 text-right text-red-600" id="modal-discount">
                                                </td>
                                            </tr>
                                            <tr class="border-b border-mocha-light/20">
                                                <td colspan="4" class="px-4 py-3 text-right font-medium">Shipping
                                                </td>
                                                <td class="px-4 py-3 text-right" id="modal-shipping"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="px-4 py-3 text-right font-medium">Total</td>
                                                <td class="px-4 py-3 text-right font-bold" id="modal-total"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Notes -->
                                <div class="mb-6">
                                    <h4 class="text-sm font-medium mb-3">Order Notes</h4>
                                    <div class="bg-mocha-cream/20 p-4 rounded-lg h-32 overflow-y-auto">
                                        <p class="text-sm" id="modal-notes"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </main>

    <!-- JavaScript for Tabs -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // — 1) NAV TABS & MOBILE MENU —
            const tabOrders = document.getElementById('tab-orders');
            const tabReviews = document.getElementById('tab-reviews');
            const contentOrders = document.getElementById('content-orders');
            const contentReviews = document.getElementById('content-reviews');
            const menuToggle = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');

            tabOrders?.addEventListener('click', () => {
                tabOrders.classList.add('tab-active');
                tabReviews.classList.remove('tab-active');
                contentOrders.classList.remove('hidden');
                contentReviews.classList.add('hidden');
            });
            tabReviews?.addEventListener('click', () => {
                tabReviews.classList.add('tab-active');
                tabOrders.classList.remove('tab-active');
                contentReviews.classList.remove('hidden');
                contentOrders.classList.add('hidden');
            });
            menuToggle?.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });

            // — 2) EDIT PROFILE MODAL & TABS —
            const openEdit = document.getElementById('openEditModal');
            const closeEdit = document.getElementById('closeEditModal');
            const editModal = document.getElementById('editProfileModal');
            const infoBtn = document.getElementById('tabInfoBtn');
            const passBtn = document.getElementById('tabPassBtn');
            const infoTab = document.getElementById('tabInfo');
            const passTab = document.getElementById('tabPass');

            if (openEdit && closeEdit && editModal) {
                openEdit.addEventListener('click', () => editModal.classList.remove('hidden'));
                closeEdit.addEventListener('click', () => editModal.classList.add('hidden'));
                editModal.addEventListener('click', e => {
                    if (e.target === editModal) editModal.classList.add('hidden');
                });
                infoBtn.addEventListener('click', () => {
                    infoTab.classList.remove('hidden');
                    passTab.classList.add('hidden');
                    infoBtn.classList.add('border-b-2', 'border-b-mocha-burgundy');
                    passBtn.classList.remove('border-b-2', 'border-b-mocha-burgundy');
                });
                passBtn.addEventListener('click', () => {
                    passTab.classList.remove('hidden');
                    infoTab.classList.add('hidden');
                    passBtn.classList.add('border-b-2', 'border-b-mocha-burgundy');
                    infoBtn.classList.remove('border-b-2', 'border-b-mocha-burgundy');
                });
            }

            // — 3) TOAST NOTIFICATIONS —
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

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showToast("{{ $error }}");
                @endforeach
            @endif

            @if (session('status'))
                showToast("{{ session('status') }}");
            @endif

            // — 4) ORDER DETAIL MODAL —
            const detailBtns = document.querySelectorAll('.details-btn');
            const odModal = document.getElementById('order-detail-modal');

            detailBtns.forEach(btn => btn.addEventListener('click', () => {
                if (!odModal) return;
                const fmt = n => `Rp ${Number(n).toLocaleString('id-ID')}`;
                const getEl = id => document.getElementById(id);

                // Header
                getEl('modal-order-id').textContent = btn.dataset.orderId;
                getEl('modal-order-date').textContent = btn.dataset.orderDate;
                getEl('modal-current-status').textContent = btn.dataset.status;
                getEl('modal-location').textContent = btn.dataset.location;

                // Customer
                getEl('modal-customer-name').textContent = btn.dataset.recipientName;
                getEl('modal-customer-email').textContent = btn.dataset.senderEmail;
                getEl('modal-recipient-phone').textContent = btn.dataset.recipientPhone;
                getEl('modal-recipient-address').textContent = btn.dataset.recipientAddress;
                getEl('modal-notes').textContent = btn.dataset.senderNote || '';

                // Payment screenshot
                const ps = getEl('modal-payment-screenshot');
                if (btn.dataset.paymentUrl) {
                    ps.src = `/payment/${btn.dataset.paymentUrl}`;
                    ps.classList.remove('hidden');
                } else {
                    ps.classList.add('hidden');
                }

                // Items
                const items = JSON.parse(btn.dataset.items || '[]');
                const tbody = getEl('modal-order-items');
                tbody.innerHTML = '';
                items.forEach(it => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
        <td class="px-4 py-2">
      <img src="${it.image}" alt="${it.name}" class="w-12 h-12 object-cover rounded" />
    </td>
    <td class="px-4 py-2">${it.name}</td>
    <td class="px-4 py-2">${fmt(it.unit_price)}</td>
    <td class="px-4 py-2">${it.quantity}</td>
    <td class="px-4 py-2 text-right">${fmt(it.total)}</td>
      `;
                    tbody.appendChild(tr);
                });

                // Totals
                getEl('modal-subtotal').textContent = fmt(btn.dataset.subtotal);
                const discRow = getEl('modal-discount-row');
                if (Number(btn.dataset.discount) > 0) {
                    discRow.classList.remove('hidden');
                    getEl('modal-discount').textContent = `-${fmt(btn.dataset.discount)}`;
                } else {
                    discRow.classList.add('hidden');
                }
                getEl('modal-shipping').textContent = fmt(btn.dataset.deliveryFee);
                getEl('modal-total').textContent = fmt(btn.dataset.grandTotal);

                // Show modal
                odModal.classList.remove('hidden');
            }));

            // Close order-detail modal
            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    btn.closest('.modal')?.classList.add('hidden');
                });
            });
            odModal?.addEventListener('click', e => {
                if (e.target.id === 'order-detail-modal') {
                    odModal.classList.add('hidden');
                }
            });
        });
    </script>

</body>

</html>
