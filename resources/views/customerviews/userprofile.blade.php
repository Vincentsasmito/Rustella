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

    /* Admin badge styles */
    .badge-pending {
        background-color: #F59E0B;
    }

    .badge-onprogress {
        background-color: #3B82F6;
    }

    .badge-readytodeliver {
        background-color: #3B82F6;
    }

    .badge-delivery {
        background-color: #3B82F6;
    }

    .badge-success {
        background-color: #10B981;
    }

    .badge-cancelled {
        background-color: #EF4444;
    }

    .badge-pending,
    .badge-onprogress,
    .badge-readytodeliver,
    .badge-delivery,
    .badge-success,
    .badge-cancelled {
        width: 130px;
        /* Adjust as needed */
        text-align: center;
        display: inline-block;
    }
</style>
</head>

<body class="bg-mocha-cream/30">
    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full z-10">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex items-center justify-between py-4">

                <!-- 1) Logo / Site Name on the left -->
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('/WebsiteStockImage/Rustella.png') }}" alt="Rustella Logo" class="h-8 w-auto">
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

                <!-- 2) Nav items centered -->
                <div class="hidden md:flex flex-1 justify-center space-x-8">
                    <a href="home" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Home</a>
                    <a href="home#bestsellers" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Best
                        Sellers</a>
                    <a href="home#catalog" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Catalog</a>
                    <a href="home#about" class="text-mocha-dark hover:text-mocha-burgundy font-medium">About Us</a>
                    <a href="home#contact" class="text-mocha-dark hover:text-mocha-burgundy font-medium">Suggestion</a>
                </div>

                <!-- 3) Icons on the right -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="profile" class="text-mocha-burgundy hover:text-mocha-dark transition-colors duration-300">
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
                    @auth
                        <span class="text-mocha-medium font-medium">
                            Welcome back,&nbsp;{{ Auth::user()->name }}!
                        </span>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="menu-toggle" class="text-mocha-dark focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-white">
                <div class="container mx-auto px-4 py-2 space-y-3">
                    <a href="home" class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Home</a>
                    <a href="home#bestsellers"
                        class="block text-mocha-dark hover:text-mocha-burgundy font-medium py-2">Best
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
        </div>
    </nav>

    <!-- Main Content -->
    @if (session('success'))
        <div id="toast"
            class="fixed left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2
           bg-green-600 text-white px-6 py-3 rounded shadow-lg z-50 animate-fade-in text-lg font-semibold text-center">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                document.getElementById('toast')?.remove();
            }, 3000);
        </script>
    @endif
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

                    <!-- Edit & Logout Buttons -->
                    <div class="mt-6 md:mt-0 md:ml-auto flex space-x-2">
                        <!-- Edit Profile -->
                        <button id="openEditModal"
                            class="bg-mocha-light/80 text-mocha-dark py-2 px-4 rounded-md hover:bg-mocha-light transition flex items-center">
                            <i class="fas fa-edit mr-2"></i> Edit Profile
                        </button>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition flex items-center">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Tabs -->
            <div class="border-b border-mocha-light mb-8">
                <div class="flex flex-wrap">
                    <button id="tab-orders" class="tab-active py-3 px-6 font-medium text-lg">Order History</button>
                    <button id="tab-reviews" class="py-3 px-6 font-medium text-lg text-mocha-medium">My
                        Reviews</button>
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
                                $badgeMap = [
                                    'Payment Pending' => 'badge-pending',
                                    'On Progress' => 'badge-onprogress',
                                    'Ready to Deliver' => 'badge-readytodeliver',
                                    'Delivery' => 'badge-delivery',
                                    'Completed' => 'badge-success',
                                    'Cancelled' => 'badge-cancelled',
                                ];
                                $badgeClass = $badgeMap[$order->progress] ?? 'badge-pending';
                            @endphp

                            <div class="p-6 hover:bg-mocha-cream/10 transition">

                                {{-- Header --}}
                                <div class="flex flex-wrap items-center mb-4 gap-y-2">
                                    <!-- 1) Order Info -->
                                    <div class="w-full lg:w-1/4">
                                        <p class="text-sm text-mocha-medium mb-1">Order #{{ $order->id }}</p>
                                        <p class="font-semibold">{{ $order->created_at->format('F j, Y') }}</p>
                                    </div>

                                    <!-- 2) Status Badge -->
                                    <div class="w-full lg:w-1/4 flex justify-start lg:justify-center">
                                        <span class="px-2 py-1 text-xs rounded-full text-white {{ $badgeClass }}">
                                            {{ $order->progress }}
                                        </span>
                                    </div>

                                    <!-- 3) Grand Total -->
                                    <div class="w-full lg:w-1/4 text-left lg:text-center">
                                        <p class="font-bold text-mocha-burgundy">
                                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <!-- 4) Actions -->
                                    <div class="w-full lg:w-1/4 flex justify-start lg:justify-end space-x-2">
                                        <button type="button"
                                            class="details-btn bg-mocha-light/50 text-mocha-dark text-sm py-1 px-3 rounded hover:bg-mocha-light transition"
                                            data-order-id="{{ $order->id }}">
                                            Details
                                        </button>

                                        @if ($order->progress === 'Payment Pending')
                                            <form action="{{ route('orders.uploadPayment', $order) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <input type="file" name="photo" class="hidden js-upload-input"
                                                    onchange="this.form.submit()">
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
                                                <img src="{{ asset('images/' . $op->product->image_url) }}"
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
                                        <span class="underline">
                                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                        </span>
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

                </div>
            </div>

            <!-- Reviews Tab Content -->
            <div id="content-reviews" class="tab-content hidden">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6 border-b border-mocha-light">
                        <h2 class="font-playfair text-xl font-semibold text-mocha-dark mb-2">My Reviews</h2>
                        <p class="text-mocha-medium">View your product reviews</p>
                    </div>

                    <div class="divide-y divide-mocha-light">
                        @forelse($reviews as $review)
                            <div class="p-6 hover:bg-mocha-cream/10 transition">
                                <div class="flex flex-col md:flex-row md:items-start justify-between mb-4">
                                    <div class="flex items-start mb-4 md:mb-0">
                                        <div class="w-16 h-16 rounded-md overflow-hidden mr-4">
                                            <img src="{{ 'images/' . $review->product->image_url ?? '/api/placeholder/100/100' }}"
                                                alt="{{ $review->product->name }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="font-medium mb-1">{{ $review->product->name }}</h3>
                                            <div class="flex text-amber-500 mb-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p class="text-sm text-mocha-medium">
                                                Reviewed on: {{ $review->created_at->format('F j, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-mocha-cream/30 p-4 rounded-md">
                                    <p class="text-mocha-dark mb-3">{{ $review->message }}</p>

                                    @if (!empty($review->images) && $review->images->count())
                                        <div class="flex flex-wrap gap-2 mt-3">
                                            @foreach ($review->images as $image)
                                                <div class="w-16 h-16 rounded-md overflow-hidden">
                                                    <img src="{{ $image->url }}" alt="Review image"
                                                        class="w-full h-full object-cover">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="p-6 text-mocha-medium">You haven’t written any reviews yet.</p>
                        @endforelse
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
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current
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
                                            <span class="font-medium">Delivery Time:</span>
                                            <span id="modal-delivery-time"></span>
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
                                <table class="w-full table-fixed border-collapse">
                                    <thead>
                                        <tr class="border-b border-mocha-light/30">
                                            <th
                                                class="w-1/5 px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                                Image
                                            </th>
                                            <th
                                                class="w-1/5 px-4 py-3 text-left   text-xs font-medium text-mocha-medium uppercase">
                                                Product
                                            </th>
                                            <th
                                                class="w-1/5 px-4 py-3 text-right  text-xs font-medium text-mocha-medium uppercase">
                                                Unit Price
                                            </th>
                                            <th
                                                class="w-1/5 px-4 py-3 text-center text-xs font-medium text-mocha-medium uppercase">
                                                Quantity
                                            </th>
                                            <th
                                                class="w-1/5 px-4 py-3 text-right  text-xs font-medium text-mocha-medium uppercase">
                                                Total
                                            </th>
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
                                <h4 class="text-sm font-medium mb-3">Notes for Recipient</h4>
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
            const allOrders = @json($ordersForJs);
            console.log(allOrders);
            const detailBtns = document.querySelectorAll('.details-btn');
            const odModal = document.getElementById('order-detail-modal');

            document.querySelectorAll('.details-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = parseInt(btn.dataset.orderId, 10);
                    const order = allOrders.find(o => o.id === id);
                    if (!order) return console.error('Order not found', id);

                    const fmt = n => `Rp ${Number(n).toLocaleString('id-ID')}`;
                    const set = (el, v) => document.getElementById(el).textContent = v;

                    // populate the modal exactly as before…
                    set('modal-order-id', order.id);
                    set('modal-order-date', order.date);
                    set('modal-delivery-time', order.deliveryTime);
                    set('modal-current-status', order.status);
                    set('modal-location', order.location);

                    set('modal-customer-name', order.recipientName);
                    set('modal-customer-email', order.senderEmail);
                    set('modal-recipient-phone', order.recipientPhone);
                    set('modal-recipient-address', order.recipientAddress);
                    set('modal-notes', order.note);

                    const tbody = document.getElementById('modal-order-items');
                    tbody.innerHTML = '';
                    order.items.forEach(it => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
         <td class="w-1/5 px-4 py-2 text-left">
       <div class="flex justify-start">
      <img src="${it.image}" class="w-12 h-12 rounded"/>
    </div>
    </td>
    <td class="w-1/5 px-4 py-2 text-left">${it.name}</td>
    <td class="w-1/5 px-4 py-2 text-right">${fmt(it.unitPrice)}</td>
    <td class="w-1/5 px-4 py-2 text-center">${it.quantity}</td>
    <td class="w-1/5 px-4 py-2 text-right">${fmt(it.total)}</td>
      `;
                        tbody.appendChild(tr);
                    });

                    set('modal-subtotal', fmt(order.subtotal));
                    const discRow = document.getElementById('modal-discount-row');
                    if (order.discount > 0) {
                        discRow.classList.remove('hidden');
                        set('modal-discount', `-${fmt(order.discount)}`);
                    } else {
                        discRow.classList.add('hidden');
                    }
                    set('modal-shipping', fmt(order.deliveryFee));
                    set('modal-total', fmt(order.grandTotal));

                    document.getElementById('order-detail-modal').classList.remove('hidden');
                });
            });

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
