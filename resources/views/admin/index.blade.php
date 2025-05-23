<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rustella Floristry - Admin Dashboard</title>
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
                        },
                        status: {
                            ok: '#10B981', // green-500
                            warn: '#F59E0B', // yellow-500
                            err: '#EF4444', // red-500
                            gray: '#9CA3AF', // gray-400
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <style>
        .sidebar {
            position: relative;
            z-index: 20;
            /* sit above the main-content when collapsed */
        }

        /* when collapsed, limit width *and* hide anything that sticks out */
        .sidebar.collapsed {
            width: 5rem;
            overflow: hidden;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: #322D29;
            background-color: #f8f8f8;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .sidebar {
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 5rem;
        }

        .main-content {
            transition: all 0.3s ease;
        }

        .table-hover tr:hover {
            background-color: rgba(209, 199, 189, 0.2);
        }

        .form-input:focus {
            border-color: #741D29;
            box-shadow: 0 0 0 3px rgba(116, 29, 41, 0.2);
        }

        .badge-success {
            background-color: #10B981;
        }

        .badge-pending {
            background-color: #F59E0B;
        }

        .badge-cancelled {
            background-color: #EF4444;
        }

        .badge-processing {
            background-color: #3B82F6;
        }

        .badge-onprogress {
            /* blue-500 */
            background-color: #3B82F6;
        }

        .badge-readytodeliver {
            /* teal-500 */
            background-color: #14B8A6;
        }

        .badge-delivery {
            /* violet-500 */
            background-color: #8B5CF6;
        }

        .badge-pending,
        .badge-onprogress,
        .badge-readytodeliver,
        .badge-delivery,
        .badge-success,
        .badge-cancelled {
            width: 130px;
            text-align: center;
            display: inline-block;
        }


        .tab-active {
            border-bottom: 2px solid #741D29;
            color: #741D29;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            z-index: 1;
            border-radius: 0.375rem;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .modal {
            transition: opacity 0.25s ease;
        }

        .toast {
            animation: slideIn 0.5s, fadeOut 0.5s 2.5s;
            animation-fill-mode: forwards;
        }

        @keyframes slideIn {
            from {
                bottom: -100px;
                opacity: 0;
            }

            to {
                bottom: 20px;
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #AC9C8D;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #741D29;
        }

        /* Fix for mobile sidebar */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 40;
                height: 100vh;
                transform: translateX(-100%);
            }

            .sidebar.show-mobile {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar bg-mocha-dark text-white w-64 min-h-screen">
        <div class="p-5">
            <div class="flex items-center space-x-2 mb-8">
                <div class="text-mocha-cream">
                    <i class="fas fa-spa text-2xl"></i>
                </div>
                <span class="sidebar-title font-playfair text-xl font-bold">Rustella<span
                        class="text-mocha-cream">Admin</span></span>
            </div>


            <nav class="space-y-1">
                <a href="#dashboard"
                    class="nav-link flex items-center space-x-3 p-3 rounded-lg bg-mocha-burgundy bg-opacity-50 mb-1"
                    data-page="dashboard">
                    <i class="fas fa-tachometer-alt w-5 text-center"></i>
                    <span class="sidebar-link-text">Dashboard</span>
                </a>
                <a href="#flowers"
                    class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 mb-1"
                    data-page="flowers">
                    <i class="fas fa-spa w-5 text-center"></i>
                    <span class="sidebar-link-text">Flowers</span>
                </a>
                <a href="#packagings"
                    class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 mb-1"
                    data-page="packagings">
                    <i class="fas fa-box w-5 text-center"></i>
                    <span class="sidebar-link-text">Packaging</span>
                </a>
                <a href="#orders"
                    class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 mb-1"
                    data-page="orders">
                    <i class="fas fa-shopping-cart w-5 text-center"></i>
                    <span class="sidebar-link-text">Orders</span>
                </a>
                <a href="#suggestions"
                    class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 mb-1"
                    data-page="suggestions">
                    <i class="fas fa-lightbulb w-5 text-center"></i>
                    <span class="sidebar-link-text">Suggestions</span>
                </a>
                <a href="#discounts"
                    class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 mb-1"
                    data-page="discounts">
                    <i class="fas fa-percent w-5 text-center"></i>
                    <span class="sidebar-link-text">Discounts</span>
                </a>
                <a href="#products"
                    class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 mb-1"
                    data-page="products">
                    <i class="fas fa-tags w-5 text-center"></i>
                    <span class="sidebar-link-text">Products</span>
                </a>
                <a href="#stocklogs"
                    class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 mb-1"
                    data-page="stocklogs">
                    <i class="fas fa-warehouse w-5 text-center"></i>
                    <span class="sidebar-link-text">Stock Logs</span>
                </a>
                <a href="#deliveries"
                    class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 mb-1"
                    data-page="deliveries">
                    <i class="fas fa-warehouse w-5 text-center"></i>
                    <span class="sidebar-link-text">Deliveries</span>
                </a>
            </nav>
        </div>
        <div class="absolute bottom-0 w-full p-5">
            <a href="#" id="toggle-sidebar"
                class="flex items-center justify-center space-x-2 p-2 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30">
                <i class="fas fa-chevron-left sidebar-toggle-icon"></i>
                <span class="sidebar-toggle-text">Collapse</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit"
                    class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 text-left">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span class="sidebar-link-text">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content flex-1 overflow-hidden">
        <!-- Topbar -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between px-6 py-3">
                <!-- left: hamburger + page title -->
                <div class="flex items-center space-x-4">
                    <button id="mobile-menu-button" class="md:hidden text-mocha-dark">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-semibold" id="page-title">Dashboard</h1>
                </div>

                <!-- right: clock + (optional) search -->
                <div class="flex items-center space-x-6">
                    <!-- live clock -->
                    <div id="clock" class="text-mocha-dark font-medium"></div>

                    <!-- Live system status -->
                    <div id="sys-status" class="flex items-center cursor-default select-none" title="Checking…">
                        <span id="sys-dot" class="inline-block w-3 h-3 rounded-full bg-gray-400 mr-2"></span>
                        <span class="text-sm text-mocha-medium">System</span>
                    </div>
                </div>
            </div>
        </header>


        <!-- Page Content -->
        <main class="p-6 overflow-y-auto h-[calc(100vh-64px)]">
            <!-- Dashboard Page -->
            <div id="dashboard-page" class="page">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Total Sales Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-mocha-medium mb-1">This Month's Sales</p>
                                <h3 class="text-2xl font-bold">
                                    Rp {{ number_format($totalSales, 0, ',', '.') }}
                                </h3>
                                <p class="text-xs mt-1 {{ $salesUp ? 'text-green-500' : 'text-red-500' }}">
                                    <i class="fas fa-arrow-{{ $salesUp ? 'up' : 'down' }} mr-1"></i>
                                    {{ $salesChange }}% from last month
                                </p>
                            </div>
                            <div class="rounded-full bg-green-100 p-3">
                                <i class="fas fa-dollar-sign text-green-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Orders Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-mocha-medium mb-1">This Month's Orders</p>
                                <h3 class="text-2xl font-bold">
                                    {{ $totalOrders }}
                                </h3>
                                <p class="text-xs mt-1 {{ $ordersUp ? 'text-green-500' : 'text-red-500' }}">
                                    <i class="fas fa-arrow-{{ $ordersUp ? 'up' : 'down' }} mr-1"></i>
                                    {{ $ordersChange }}% from last month
                                </p>
                            </div>
                            <div class="rounded-full bg-blue-100 p-3">
                                <i class="fas fa-shopping-bag text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Profit Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-mocha-medium mb-1">Profit (Month-to-Date)</p>
                                <h3 class="text-2xl font-bold">
                                    Rp {{ number_format($totalProfit, 0, ',', '.') }}
                                </h3>
                                <p class="text-xs mt-1 {{ $profitUp ? 'text-green-500' : 'text-red-500' }}">
                                    <i class="fas fa-arrow-{{ $profitUp ? 'up' : 'down' }} mr-1"></i>
                                    {{ $profitUp ? 'Up' : 'Down' }} {{ $profitChange }}% from last month
                                </p>
                            </div>
                            <div class="rounded-full bg-emerald-100 p-3">
                                <i class="fas fa-coins text-emerald-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Products Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-mocha-medium mb-1">This Month's COGS</p>
                                <h3 class="text-2xl font-bold">Rp {{ number_format($totalCogs, 0, ',', '.') }}</h3>
                                <p class="text-xs {{ $cogsUp ? 'text-green-500' : 'text-red-500' }} mt-1">
                                    <i class="fas fa-arrow-{{ $cogsUp ? 'up' : 'down' }} mr-1"></i>
                                    {{ $cogsChange }}% from last month
                                </p>
                            </div>
                            <div class="rounded-full bg-amber-100 p-3">
                                <i class="fas fa-spa text-amber-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Sales Chart -->
                    <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-lg">Sales Overview</h3>
                            <div class="relative">
                                <select id="sales-range"
                                    class="bg-mocha-cream/30 border-transparent rounded-md text-sm py-1 px-3 focus:outline-none focus:ring-2 focus:ring-mocha-burgundy">
                                    <option value="7">Last 7 Days</option>
                                    <option value="30">Last 30 Days</option>
                                    <option value="90">Last 90 Days</option>
                                </select>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <!-- Best Sellers -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-semibold text-lg mb-4">Best Sellers</h3>
                        <div class="space-y-4">
                            @foreach ($bestSellers as $prod)
                                <div class="flex items-center">
                                    <img src="{{ asset('images/' . $prod->image_url) }}" alt="{{ $prod->name }}"
                                        class="w-12 h-12 rounded object-cover mr-4">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium">{{ $prod->name }}</h4>
                                        <p class="text-xs text-mocha-medium">{{ $prod->sold_qty }} sales</p>
                                    </div>
                                    <span class="text-mocha-burgundy font-semibold">
                                        Rp {{ number_format($prod->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <hr class="my-6 border-mocha-light/30">
                        <a href="#flowers"
                            class="nav-link block text-center text-mocha-burgundy text-sm mt-4 hover:underline"
                            data-page="products">
                            View All Products
                        </a>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg">Recent Orders</h3>
                        <a href="#orders" class="nav-link text-mocha-burgundy text-sm hover:underline"
                            data-page="orders">
                            View All
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full table-hover">
                            <thead>
                                <tr class="border-b border-mocha-light/30">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Order ID
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Username
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Order Date
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Sales
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                        {{-- Order ID --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            #{{ $order->id }}
                                        </td>

                                        {{-- Username --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $order->user->name }}
                                        </td>

                                        {{-- Order Date --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $order->created_at->format('F j, Y') }}
                                        </td>

                                        {{-- Sales --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            Rp {{ number_format($order->sales, 0, ',', '.') }}
                                        </td>

                                        {{-- Status / Progress --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @php
                                                $map = [
                                                    'Pending Payment' => 'badge-pending',
                                                    'On Progress' => 'badge-onprogress',
                                                    'Delivery' => 'badge-delivery',
                                                    'Ready to Deliver' => 'badge-readytodeliver',
                                                    'Completed' => 'badge-success',
                                                    'Cancelled' => 'badge-cancelled',
                                                ];
                                                $cls = $map[$order->progress] ?? 'badge-pending';
                                            @endphp

                                            <span
                                                class="px-2 py-1 text-xs rounded-full text-white {{ $cls }}">
                                                {{ $order->progress }}
                                            </span>
                                        </td>

                                        {{-- Action --}}
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <button class="view-order-btn text-mocha-burgundy hover:underline"
                                                data-id="{{ $order->id }}">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-sm text-gray-500">
                                            No recent orders.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Flowers Page (dynamic data with Add Stock column) -->
            <div id="flowers-page" class="page hidden">
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6 border-b border-mocha-light/20">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h2 class="text-xl font-semibold mb-4 md:mb-0">Flower Management</h2>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <!-- Add New Flower (could open a modal or link to a create form) -->
                                <button type="button"
                                    class="add-flower-btn bg-mocha-burgundy text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition flex items-center justify-center">
                                    <i class="fas fa-plus mr-2"></i> Add New Flower
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full table-hover">
                            <thead>
                                <tr class="border-b border-mocha-light/30">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Quantity</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Price/Qty</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Add Stock</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($flowers as $flower)
                                    <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                        <td class="px-4 py-3 whitespace-nowrap">#{{ $flower->id }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $flower->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $flower->quantity }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            Rp {{ number_format($flower->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <form action="{{ route('admin.flowers.stockupdate', $flower) }}"
                                                method="POST" class="flex items-center space-x-2">
                                                @csrf
                                                <input name="quantity" type="number" min="1"
                                                    placeholder="Qty" class="w-16 px-2 py-1 border rounded-md text-sm"
                                                    required>
                                                <input name="price" type="number" min="0"
                                                    placeholder="Total Rp"
                                                    class="w-24 px-2 py-1 border rounded-md text-sm" required>
                                                <button type="submit"
                                                    class="bg-green-500 text-white px-3 py-1 rounded-md text-sm">
                                                    Add
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-right">
                                            <button data-id="{{ $flower->id }}"
                                                class="edit-flower-btn text-blue-600 hover:text-blue-800 mr-3">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.flowers.destroy', $flower) }}"
                                                method="POST" onsubmit="return confirm('Delete this flower?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Packaging Page -->
            <div id="packagings-page" class="page hidden">
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6 border-b border-mocha-light/20 flex items-center justify-between">
                        <h2 class="text-xl font-semibold">Packaging Options</h2>
                        <button
                            class="add-packaging-btn bg-mocha-burgundy text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition flex items-center">
                            <i class="fas fa-plus mr-2"></i> Add New Packaging
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full table-hover">
                            <thead>
                                <tr class="border-b border-mocha-light/30">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($packagings as $pkg)
                                    <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                        <td class="px-4 py-3 whitespace-nowrap">#{{ $pkg->id }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $pkg->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <button class="edit-packaging-btn text-blue-600 hover:text-blue-800 mr-3"
                                                data-id="{{ $pkg->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.packagings.destroy', $pkg) }}"
                                                method="POST" onsubmit="return confirm('Delete this packaging?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">
                                            No packaging options found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Orders Page -->
            <div id="orders-page" class="page hidden">
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6 border-b border-mocha-light/20">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h2 class="text-xl font-semibold mb-4 md:mb-0">Order Management</h2>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <form method="GET" action="{{ route('admin.index') }}"
                                    class="flex flex-col sm:flex-row gap-3">
                                    <input type="hidden" name="tab" value="orders">
                                    <div class="relative">
                                        <input type="text" name="order_search"
                                            value="{{ request('order_search') }}" placeholder="Search by Order ID..."
                                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy">
                                        <button type="submit" class="absolute right-3 top-3 text-mocha-medium">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div>
                                        <select name="order_status"
                                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy"
                                            onchange="this.form.submit()">
                                            <option value="">All Orders</option>
                                            <option value="Payment Pending"
                                                {{ request('order_status') == 'Payment Pending' ? 'selected' : '' }}>
                                                Payment Pending</option>
                                            <option value="On Progress"
                                                {{ request('order_status') == 'On Progress' ? 'selected' : '' }}>On
                                                Progress</option>
                                            <option value="Ready to Deliver"
                                                {{ request('order_status') == 'Ready to Deliver' ? 'selected' : '' }}>
                                                Ready to Deliver</option>
                                            <option value="Delivery"
                                                {{ request('order_status') == 'Delivery' ? 'selected' : '' }}>Delivery
                                            </option>
                                            <option value="Completed"
                                                {{ request('order_status') == 'Completed' ? 'selected' : '' }}>
                                                Completed
                                            </option>
                                            <option value="Cancelled"
                                                {{ request('order_status') == 'Cancelled' ? 'selected' : '' }}>
                                                Cancelled</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full table-hover">
                            <thead>
                                <tr class="border-b border-mocha-light/30">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Order ID</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Username</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Order Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Sales</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                        <td class="px-4 py-3 whitespace-nowrap">#{{ $order->id }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $order->user->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $order->created_at->format('F j, Y') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">Rp
                                            {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @php
                                                $map = [
                                                    'Pending Payment' => 'badge-pending',
                                                    'On Progress' => 'badge-onprogress',
                                                    'Delivery' => 'badge-delivery',
                                                    'Ready to Deliver' => 'badge-readytodeliver',
                                                    'Completed' => 'badge-success',
                                                    'Cancelled' => 'badge-cancelled',
                                                ];
                                                $cls = $map[$order->progress] ?? 'badge-pending';
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs rounded-full text-white {{ $cls }}">
                                                {{ $order->progress }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <button class="details-btn text-mocha-burgundy hover:underline"
                                                data-order-id="{{ $order->id }}">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-sm text-gray-500">
                                            No orders found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4">
                        {{ $orders->appends(['order_search' => request('order_search'), 'order_status' => request('order_status')])->fragment('orders')->links() }}
                    </div>
                </div>
            </div>


            <!-- Suggestions Page -->
            <div id="suggestions-page" class="page hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Site Suggestions --}}
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-mocha-light/20">
                            <h2 class="text-xl font-semibold">Site Feedback</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full table-hover">
                                <thead>
                                    <tr class="border-b border-mocha-light/30">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Date</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Message</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Rating</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($siteSuggestions as $sug)
                                        <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                            <td class="px-4 py-3">#{{ $sug->id }}</td>
                                            <td class="px-4 py-3">{{ $sug->created_at->format('F j, Y') }}</td>
                                            <td class="px-4 py-3">
                                                <details class="cursor-pointer">
                                                    {{-- Summary shows a truncated preview --}}
                                                    <summary class="truncate w-48" title="{{ $sug->message }}">
                                                        {{ Str::limit($sug->message, 50) }}
                                                    </summary>

                                                    {{-- Full message revealed on click --}}
                                                    <p class="mt-1 text-sm">
                                                        {{ $sug->message }}
                                                    </p>
                                                </details>
                                            </td>
                                            <td class="px-4 py-3">{{ $sug->rating ?? '—' }}</td>
                                            <td class="px-4 py-3 text-right">
                                                <form action="{{ route('admin.suggestions.destroy', $sug) }}"
                                                    method="POST" onsubmit="return confirm('Delete this feedback?');"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-3 text-center text-sm text-gray-500">
                                                No site feedback yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Product Suggestions --}}
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-mocha-light/20">
                            <h2 class="text-xl font-semibold">Product Suggestions</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full table-hover">
                                <thead>
                                    <tr class="border-b border-mocha-light/30">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Customer</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Product</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Date</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Message</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Rating</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($productSuggestions as $sug)
                                        <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                            <td class="px-4 py-3">#{{ $sug->id }}</td>
                                            <td class="px-4 py-3">{{ $sug->user->name }}</td>
                                            <td class="px-4 py-3">{{ $sug->product?->name ?? 'Product Deleted' }}</td>
                                            <td class="px-4 py-3">{{ $sug->created_at->format('F j, Y') }}</td>
                                            <td class="px-4 py-3">
                                                <details class="cursor-pointer">
                                                    {{-- Summary shows a truncated preview --}}
                                                    <summary class="truncate w-48" title="{{ $sug->message }}">
                                                        {{ Str::limit($sug->message, 50) }}
                                                    </summary>

                                                    {{-- Full message revealed on click --}}
                                                    <p class="mt-1 text-sm">
                                                        {{ $sug->message }}
                                                    </p>
                                                </details>
                                            </td>
                                            <td class="px-4 py-3">{{ $sug->rating ?? '—' }}</td>
                                            <td class="px-4 py-3 text-right">
                                                <form action="{{ route('admin.suggestions.destroy', $sug) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Delete this suggestion?');"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-4 py-3 text-center text-sm text-gray-500">
                                                No product suggestions yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Logs Page -->
            <div id="stocklogs-page" class="page hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- FO Table -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-mocha-light/20">
                            <h2 class="text-xl font-semibold">FO (Stock Out - Order Fulfillment)</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full table-hover">
                                <thead>
                                    <tr class="border-b border-mocha-light/30">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Date</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Quantity</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Order ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Flower ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Price/Qty</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stockLogsFO as $log)
                                        <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                            <td class="px-4 py-3">#{{ $log->id }}</td>
                                            <td class="px-4 py-3">{{ $log->created_at->format('F j, Y') }}</td>
                                            <td class="px-4 py-3">{{ $log->quantity }}</td>
                                            <td class="px-4 py-3">{{ $log->order_id ?? '-' }}</td>
                                            <td class="px-4 py-3">{{ $log->flower_id ?? '-' }}</td>
                                            <td class="px-4 py-3">Rp {{ number_format($log->price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-3 text-center text-sm text-gray-500">
                                                No FO logs found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-2">
                            {{ $stockLogsFO->links() }}
                        </div>
                    </div>
                    <!-- FI Table -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-mocha-light/20">
                            <h2 class="text-xl font-semibold">FI (Stock In - Purchase/Restock)</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full table-hover">
                                <thead>
                                    <tr class="border-b border-mocha-light/30">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Date</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Quantity</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Order ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Flower ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Price/Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stockLogsFI as $log)
                                        <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                            <td class="px-4 py-3">#{{ $log->id }}</td>
                                            <td class="px-4 py-3">{{ $log->created_at->format('F j, Y') }}</td>
                                            <td class="px-4 py-3">{{ $log->quantity }}</td>
                                            <td class="px-4 py-3">{{ $log->order_id }}</td>
                                            <td class="px-4 py-3">{{ $log->flower_id ?? '-' }}</td>
                                            <td class="px-4 py-3">Rp {{ number_format($log->price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">
                                                No FI logs found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-2">
                            {{ $stockLogsFI->links() }}
                        </div>
                    </div>
                    <!-- PO Table -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-mocha-light/20">
                            <h2 class="text-xl font-semibold">PO (Stock Out - Manual Adjustment)</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full table-hover">
                                <thead>
                                    <tr class="border-b border-mocha-light/30">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Date</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Quantity</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Order ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Packaging ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Price/Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stockLogsPO as $log)
                                        <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                            <td class="px-4 py-3">#{{ $log->id }}</td>
                                            <td class="px-4 py-3">{{ $log->created_at->format('F j, Y') }}</td>
                                            <td class="px-4 py-3">{{ $log->quantity }}</td>
                                            <td class="px-4 py-3">{{ $log->order_id ?? '-' }}</td>
                                            <td class="px-4 py-3">{{ $log->packaging_id ?? '-' }}</td>
                                            <td class="px-4 py-3">Rp {{ number_format($log->price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">
                                                No PO logs found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-2">
                            {{ $stockLogsPO->links() }}
                        </div>
                    </div>
                    <!-- PI Table -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-mocha-light/20">
                            <h2 class="text-xl font-semibold">PI (Stock In - Manual Adjustment)</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full table-hover">
                                <thead>
                                    <tr class="border-b border-mocha-light/30">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Date</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Quantity</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Order ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Packaging ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                            Price/Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stockLogsPI as $log)
                                        <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                            <td class="px-4 py-3">#{{ $log->id }}</td>
                                            <td class="px-4 py-3">{{ $log->created_at->format('F j, Y') }}</td>
                                            <td class="px-4 py-3">{{ $log->quantity }}</td>
                                            <td class="px-4 py-3">{{ $log->order_id ?? '-' }}</td>
                                            <td class="px-4 py-3">{{ $log->packaging_id ?? '-' }}</td>
                                            <td class="px-4 py-3">Rp {{ number_format($log->price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">
                                                No PI logs found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-2">
                            {{ $stockLogsPI->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discounts Page -->
            <div id="discounts-page" class="page hidden">
                <div class="bg-white rounded-lg shadow mb-6">
                    <div
                        class="p-6 border-b border-mocha-light/20 flex flex-col md:flex-row md:items-center md:justify-between">
                        <h2 class="text-xl font-semibold mb-4 md:mb-0">Discount Management</h2>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button
                                class="add-discount-btn bg-mocha-burgundy text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition flex items-center justify-center">
                                <i class="fas fa-plus mr-2"></i> Add New Discount
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full table-hover">
                            <thead>
                                <tr class="border-b border-mocha-light/30">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Code</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Percent</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Max Value</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Min Purchase</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Usage Limit</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Usage Count</th>
                                    <!-- New headers -->
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Start Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        End Date</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($discounts as $d)
                                    <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $d->code }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $d->percent }}%</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            Rp {{ number_format($d->max_value, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            Rp {{ number_format($d->min_purchase, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $d->usage_limit ?: '∞' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $d->usage_counter }}</td>
                                        <!-- New data cells -->
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $d->start_date ? $d->start_date->format('F j, Y') : '—' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $d->end_date ? $d->end_date->format('F j, Y') : '—' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right space-x-2">
                                            <button class="edit-discount-btn text-blue-600 hover:text-blue-800"
                                                data-id="{{ $d->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.discounts.destroy', $d) }}" method="POST"
                                                onsubmit="return confirm('Delete this discount?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="delete-discount-btn text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Products Page -->
            <div id="products-page" class="page hidden">
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6 border-b border-mocha-light/20 flex items-center justify-between">
                        <h2 class="text-xl font-semibold">Product Management</h2>
                        <button class="add-product-btn bg-mocha-burgundy text-white px-4 py-2 rounded-md">
                            <i class="fas fa-plus mr-2"></i> Add New Product
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full table-hover">
                            <thead>
                                <tr class="border-b border-mocha-light/30">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                        Image</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                        Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                        Description</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                        Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                        Packaging</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">In
                                        Stock</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                        Recipe</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $prod)
                                    <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10 align-top">
                                        <!-- Image -->
                                        <td class="px-4 py-3">
                                            <img src="{{ asset('images/' . $prod->image_url) }}"
                                                alt="{{ $prod->name }}" class="w-16 h-16 object-cover rounded">
                                        </td>

                                        <!-- Name -->
                                        <td class="px-4 py-3">{{ $prod->name }}</td>

                                        <!-- Description -->
                                        <td class="px-4 py-3">
                                            <details>
                                                <summary
                                                    class="cursor-pointer text-sm text-mocha-dark hover:text-mocha-burgundy">
                                                    {{ Str::limit($prod->description, 30) }}
                                                </summary>
                                                <p class="mt-2 text-sm text-mocha-medium">
                                                    {{ $prod->description }}
                                                </p>
                                            </details>
                                        </td>
                                        <!-- Price -->
                                        <td class="px-4 py-3">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>

                                        <!-- Packaging -->
                                        <td class="px-4 py-3">{{ $prod->packaging->name }}</td>

                                        <!-- In Stock -->
                                        <td class="px-4 py-3">
                                            @if ($prod->in_stock)
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Yes</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">No</span>
                                            @endif
                                        </td>

                                        <!-- Recipe -->
                                        <td class="px-4 py-3">
                                            <details>
                                                <summary class="cursor-pointer text-sm text-mocha-dark">
                                                    {{ $prod->flowerProducts->count() }} flowers…
                                                </summary>
                                                <table class="mt-2 w-full text-sm">
                                                    <thead>
                                                        <tr>
                                                            <th class="pb-1 text-left">Flower</th>
                                                            <th class="pb-1 text-left">Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($prod->flowerProducts as $fp)
                                                            <tr>
                                                                <td class="py-1">{{ $fp->flower->name }}</td>
                                                                <td class="py-1">{{ $fp->quantity }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </details>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-4 py-3 text-right">
                                            <button class="edit-product-btn text-blue-600 hover:text-blue-800 mr-3"
                                                data-id="{{ $prod->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.products.destroy', $prod) }}"
                                                method="POST" onsubmit="return confirm('Delete this product?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-3 text-center text-sm text-gray-500">
                                            No products found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Deliveries Page -->
            <div id="deliveries-page" class="page hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($sectionOrder as $section)
                        @php
                            $deliveries = $deliveriesBySection[$section] ?? collect();
                        @endphp

                        <div class="bg-white rounded-lg shadow">
                            <div class="p-6 border-b border-mocha-light/20">
                                <h2 class="text-xl font-semibold">{{ $section }}</h2>
                            </div>

                            <div class="overflow-x-auto">
                                @if ($deliveries->isEmpty())
                                    <p class="p-4 text-sm text-gray-500">No deliveries found for this area.</p>
                                @else
                                    <table class="w-full table-hover">
                                        <thead>
                                            <tr class="border-b border-mocha-light/30">
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase">
                                                    Subdistrict
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase">
                                                    Fee
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($deliveries as $delivery)
                                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                                    <td class="px-4 py-3">{{ $delivery->subdistrict }}</td>
                                                    <td class="px-4 py-3 text-right">
                                                        Rp {{ number_format($delivery->fee, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-right space-x-2">
                                                        <button type="button"
                                                            class="text-blue-600 hover:text-blue-800 edit-delivery-btn"
                                                            data-id="{{ $delivery->id }}"
                                                            data-subdistrict="{{ $delivery->subdistrict }}"
                                                            data-fee="{{ $delivery->fee }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <!-- Delete Form -->
                                                        <form
                                                            action="{{ route('admin.deliveries.destroy', $delivery) }}"
                                                            method="POST" class="inline"
                                                            onsubmit="return confirm('Delete this delivery?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-red-600 hover:text-red-800">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Modals -->
            <!-- Flower Modal -->
            <div id="flower-modal"
                class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="flower-modal-title" class="text-lg font-semibold">Add New Flower</h3>
                        <button class="close-modal text-mocha-dark hover:text-mocha-burgundy">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form id="flower-form" action="{{ route('admin.flowers.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="flower-form-method" value="POST">

                        <div class="space-y-4">
                            <div>
                                <label for="flower-name" class="block text-sm font-medium text-mocha-dark mb-1">Flower
                                    Name</label>
                                <input type="text" name="name" id="flower-name"
                                    class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                    value="" required />
                            </div>
                            <div>
                                <label for="flower-price" class="block text-sm font-medium text-mocha-dark mb-1">Total
                                    Cost
                                    (Rp)</label>
                                <input type="number" name="price" id="flower-price"
                                    class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                    value="" required />
                            </div>
                            <div>
                                <label for="flower-stock"
                                    class="block text-sm font-medium text-mocha-dark mb-1">Quantity</label>
                                <input type="number" name="quantity" id="flower-stock"
                                    class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                    value="" required />
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button"
                                class="close-modal px-4 py-2 border border-mocha-light rounded-md">Cancel</button>
                            <button type="submit" id="flower-form-submit"
                                class="px-4 py-2 bg-mocha-burgundy text-white rounded-md">
                                Add Flower
                            </button>
                        </div>
                    </form>
                </div>
            </div>




            <!-- Add/Edit Packaging Modal -->
            <div id="packaging-modal"
                class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
                    <!-- header -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="packaging-modal-title" class="text-lg font-semibold">Add New Packaging</h3>
                        <button class="close-modal text-mocha-dark hover:text-mocha-burgundy">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- form -->
                    <form id="packaging-form" action="{{ route('admin.packagings.store') }}" method="POST"
                        class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" id="packaging-form-method" value="POST">

                        <div>
                            <label for="packaging-name" class="block text-sm font-medium text-mocha-dark mb-1">
                                Name
                            </label>
                            <input id="packaging-name" name="name" type="text" required
                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy">
                        </div>

                        <div>
                            <label for="packaging-price" class="block text-sm font-medium text-mocha-dark mb-1">
                                Price (Rp)
                            </label>
                            <input id="packaging-price" name="price" type="number" min="0" required
                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy">
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button"
                                class="close-modal px-4 py-2 border border-mocha-light rounded-md text-mocha-dark hover:bg-mocha-cream/50">
                                Cancel
                            </button>
                            <button type="submit" id="packaging-form-submit"
                                class="px-4 py-2 bg-mocha-burgundy text-white rounded-md hover:bg-mocha-dark">
                                Add Packaging
                            </button>
                        </div>
                    </form>
                </div>
            </div>



            <!-- Discount Modal -->
            <div id="discount-modal"
                class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="discount-modal-title" class="text-lg font-semibold">Add New Discount</h3>
                        <button class="close-modal text-mocha-dark hover:text-mocha-burgundy">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="discount-form" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" id="discount-form-method" value="POST">

                        <div>
                            <label for="discount-code"
                                class="block text-sm font-medium text-mocha-dark mb-1">Code</label>
                            <input id="discount-code" name="code" type="text" maxlength="20" required
                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md form-input">
                        </div>

                        <div>
                            <label for="discount-percent"
                                class="block text-sm font-medium text-mocha-dark mb-1">Percent</label>
                            <input id="discount-percent" name="percent" type="number" min="0"
                                max="100" required
                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md form-input">
                        </div>

                        <div>
                            <label for="discount-maxvalue" class="block text-sm font-medium text-mocha-dark mb-1">
                                Max Value (Rp)
                            </label>
                            <input id="discount-maxvalue" name="max_value" type="number" min="0" required
                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md form-input">
                        </div>

                        <div>
                            <label for="discount-minpurchase" class="block text-sm font-medium text-mocha-dark mb-1">
                                Min Purchase (Rp)
                            </label>
                            <input id="discount-minpurchase" name="min_purchase" type="number" min="0"
                                required class="w-full px-4 py-2 border border-mocha-light/30 rounded-md form-input">
                        </div>

                        <div>
                            <label for="discount-usagelimit" class="block text-sm font-medium text-mocha-dark mb-1">
                                Usage Limit
                            </label>
                            <input id="discount-usagelimit" name="usage_limit" type="number" min="0"
                                placeholder="0 for infinite"
                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md form-input">
                        </div>

                        <div>
                            <label for="discount-usagecounter" class="block text-sm font-medium text-mocha-dark mb-1">
                                Usage Counter
                            </label>
                            <input id="discount-usagecounter" name="usage_counter" type="number" min="0"
                                value="0"
                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md form-input">
                        </div>

                        <!-- New Start/End Date Fields -->
                        <div>
                            <label for="discount-start-date" class="block text-sm font-medium text-mocha-dark mb-1">
                                Start Date
                            </label>
                            <input id="discount-start-date" name="start_date" type="date"
                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md form-input">
                        </div>

                        <div>
                            <label for="discount-end-date" class="block text-sm font-medium text-mocha-dark mb-1">
                                End Date
                            </label>
                            <input id="discount-end-date" name="end_date" type="date"
                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md form-input">
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" class="close-modal px-4 py-2 border border-mocha-light rounded-md">
                                Cancel
                            </button>
                            <button id="discount-form-submit" type="submit"
                                class="px-4 py-2 bg-mocha-burgundy text-white rounded-md">
                                Save Discount
                            </button>
                        </div>
                    </form>
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
                                        <p class="text-sm mb-2">
                                            <span class="font-medium">Delivery Time:</span>
                                            <span id="modal-delivery-time"></span>
                                        </p>
                                        </p>
                                        <p class="text-sm mb-2"><span class="font-medium">Status:</span> <span
                                                id="modal-current-status" class="font-semibold"></span></p>
                                        <p class="text-sm"><span class="font-medium">Location:</span> <span
                                                id="modal-location"></span></p>
                                        <!-- New: Payment Proof row, hidden by default -->
                                        <p id="modal-payment-row" class="text-sm mb-2 hidden">
                                            <span class="font-medium">Payment Proof:</span>
                                            <a id="modal-payment-link" class="text-mocha-burgundy hover:underline"
                                                href="#" target="_blank" rel="noopener noreferrer">
                                                View Screenshot
                                            </a>
                                        </p>
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
                                        <!-- injected by JS -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-b border-mocha-light/20">
                                            <td colspan="3" class="px-4 py-3 text-right font-medium">Subtotal</td>
                                            <td class="px-4 py-3 text-right" id="modal-subtotal"></td>
                                        </tr>
                                        {{-- Discount row — hidden by default --}}
                                        <tr id="modal-discount-row" class="border-b border-mocha-light/20 hidden">
                                            <td colspan="3" class="px-4 py-3 text-right font-medium">Discount</td>
                                            <td class="px-4 py-3 text-right text-red-600" id="modal-discount"></td>
                                        </tr>
                                        <tr class="border-b border-mocha-light/20">
                                            <td colspan="3" class="px-4 py-3 text-right font-medium">Shipping</td>
                                            <td class="px-4 py-3 text-right" id="modal-shipping"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="px-4 py-3 text-right font-medium">Total</td>
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

                        <!-- Footer -->
                        <div class="p-4 border-t flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm">Change Status:</span>
                                <select id="modal-status-select"
                                    class="px-3 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy text-sm">
                                    <option>Payment Pending</option>
                                    <option>On Progress</option>
                                    <option>Ready to Deliver</option>
                                    <option>Delivery</option>
                                    <option>Completed</option>
                                    <option>Cancelled</option>
                                </select>
                                <button id="modal-save-status"
                                    class="px-3 py-2 bg-mocha-burgundy text-white rounded-md text-sm">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Product Modal -->
            <div id="product-modal"
                class="modal fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4 z-50">
                <div
                    class="
      bg-white rounded-lg shadow-lg p-6
      w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl
      max-h-full overflow-y-auto
    ">
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="product-modal-title" class="text-lg font-semibold">
                            Add New Product
                        </h3>
                        <button class="close-modal text-mocha-dark hover:text-mocha-burgundy">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="product-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="product-form-method" value="POST">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Name</label>
                                <input name="name" type="text" required
                                    class="w-full px-4 py-2 border rounded-md form-input" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Description</label>
                                <textarea name="description" rows="3" required class="w-full px-4 py-2 border rounded-md form-input"></textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Price (Rp)</label>
                                    <input name="price" type="number" min="0" required
                                        class="w-full px-4 py-2 border rounded-md form-input" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Image</label>
                                    <input type="file" name="photo" accept="image/*"
                                        class="w-full border rounded-md form-input" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Packaging</label>
                                    <select name="packaging_id" required
                                        class="w-full px-4 py-2 border rounded-md form-input">
                                        @foreach ($packagings as $pkg)
                                            <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input name="in_stock" type="checkbox" id="product-in-stock"
                                        class="form-checkbox" />
                                    <label for="product-in-stock" class="text-sm">
                                        In Stock
                                    </label>
                                </div>
                            </div>

                            <!-- Flower Recipe Section -->
                            <div class="border-t pt-4">
                                <h4 class="text-sm font-medium mb-2">Recipe (Flowers)</h4>
                                <div id="flower-list">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-48 overflow-y-auto">
                                        @foreach ($flowers as $flower)
                                            <div class="flex items-center space-x-2">
                                                <input type="checkbox" name="flowers[{{ $flower->id }}]"
                                                    value="1" id="flower-{{ $flower->id }}"
                                                    class="form-checkbox" />
                                                <label for="flower-{{ $flower->id }}" class="flex-1 truncate">
                                                    {{ $flower->name }}
                                                </label>
                                                <input type="number" name="quantities[{{ $flower->id }}]"
                                                    min="1" placeholder="Qty"
                                                    id="quantity-{{ $flower->id }}" disabled
                                                    class="w-20 px-2 py-1 border rounded-md form-input" />
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button"
                                    class="close-modal px-4 py-2 border rounded-md hover:bg-gray-100 transition">
                                    Cancel
                                </button>
                                <button type="submit" id="product-form-submit"
                                    class="px-4 py-2 bg-mocha-burgundy text-white rounded-md hover:bg-opacity-90 transition">
                                    Save Product
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Update Delivery Fee Modal --}}
            <div id="update-delivery-modal"
                class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center grid place-items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-sm mx-4 p-6 relative">
                    <!-- Close button -->
                    <button type="button"
                        class="close-modal absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                        &times;
                    </button>

                    <h3 class="text-lg font-semibold mb-4">
                        Edit Fee for <span id="update-delivery-subdistrict"></span>
                    </h3>

                    <form id="edit-delivery-form" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="update-delivery-fee" class="block text-sm font-medium text-gray-700">
                                Fee
                            </label>
                            <input type="number" name="fee" id="update-delivery-fee" min="0"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button type="button" class="px-4 py-2 border rounded-lg close-modal">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-mocha-burgundy text-white rounded-lg">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>



        <!-- Toast Notification -->
        <div id="toast"
            class="toast fixed bottom-0 right-0 m-6 p-4 bg-mocha-dark text-white rounded-lg shadow-lg hidden flex items-center">
            <i id="toast-icon" class="fa-solid fa-check-circle text-green-400 mr-3"></i>
            <span id="toast-message">Changes saved successfully!</span>
            <button class="ml-4 text-white/70 hover:text-white"
                onclick="document.getElementById('toast').classList.add('hidden')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        @php
            // Build a plain PHP array so Blade doesn’t mis-parse it
            $discountDataMap = $discounts
                ->mapWithKeys(function ($d) {
                    return [
                        $d->id => [
                            'code' => $d->code,
                            'percent' => $d->percent,
                            'min_purchase' => $d->min_purchase,
                            'max_value' => $d->max_value,
                            'usage_limit' => $d->usage_limit,
                            'usage_counter' => $d->usage_counter,
                            'start_date' => $d->start_date ? $d->start_date->format('Y-m-d') : null,
                            'end_date' => $d->end_date ? $d->end_date->format('Y-m-d') : null,
                        ],
                    ];
                })
                ->toArray();
        @endphp

        <!-- Scripts -->
        <script>
            function showToast(msg, type = 'success') {
                const toast = document.getElementById('toast');
                const toastMsg = document.getElementById('toast-message');
                const toastIcon = document.getElementById('toast-icon');
                toast.classList.remove('flex');
                toast.classList.add('hidden');
                toastMsg.textContent = msg;


                // Replace the icon element entirely
                const newIcon = document.createElement('i');
                if (type === 'error') {
                    newIcon.className = 'fa-solid fa-circle-xmark text-red-400 mr-3';
                } else {
                    newIcon.className = 'fa-solid fa-check-circle text-green-400 mr-3';
                }
                newIcon.id = 'toast-icon';
                toastIcon.replaceWith(newIcon);

                setTimeout(() => {
                    toast.classList.remove('hidden');
                    toast.classList.add('flex');
                }, 10);

                setTimeout(() => {
                    toast.classList.remove('flex');
                    toast.classList.add('hidden');
                }, 3000);
            }
            // ——— Data injections from Blade ——————————————————————————————————————————————————
            const salesChartData = @json($salesChartData);
            const recentOrders = @json($recentOrders);
            const allOrders = @json($orders->items());
            const flowerDataMap = @json($flowers->mapWithKeys(fn($f) => [$f->id => ['name' => $f->name, 'price' => $f->price, 'quantity' => $f->quantity]]));

            function populateFlowerList(selectedQuantities = {}) {
                const container = document.getElementById('flower-list');
                container.innerHTML = ''; // clear existing
                for (const [id, f] of Object.entries(flowerDataMap)) {
                    // wrapper
                    const line = document.createElement('div');
                    line.className = 'flex items-center space-x-2';
                    // checkbox
                    const cb = document.createElement('input');
                    cb.type = 'checkbox';
                    cb.name = `flowers[${id}]`;
                    cb.id = `flower-cb-${id}`;
                    cb.value = '1';
                    // label
                    const lbl = document.createElement('label');
                    lbl.htmlFor = cb.id;
                    lbl.textContent = `${f.name} (in stock: ${f.quantity})`;
                    // qty input
                    const qty = document.createElement('input');
                    qty.type = 'number';
                    qty.name = `quantities[${id}]`;
                    qty.min = 1;
                    qty.placeholder = 'Qty';
                    qty.disabled = true;
                    qty.className = 'w-16 px-2 py-1 border rounded-md text-sm';
                    // checkbox toggle enables/disables qty
                    cb.addEventListener('change', () => {
                        qty.disabled = !cb.checked;
                        if (!cb.checked) qty.value = '';
                    });
                    // if we're editing, pre-check & fill qty
                    if (selectedQuantities[id]) {
                        cb.checked = true;
                        qty.disabled = false;
                        qty.value = selectedQuantities[id];
                    }
                    // assemble
                    line.append(cb, lbl, qty);
                    container.append(line);
                }
            }
            const packagingDataMap = @json($packagings->mapWithKeys(fn($p) => [$p->id => ['name' => $p->name, 'price' => $p->price]]));
            const discountDataMap = @json($discountDataMap);

            document.addEventListener('DOMContentLoaded', () => {
                // ——— Helpers ———————————————————————————————————————————————————————————————
                const show = el => el.classList.remove('hidden') && el.classList.add('flex');
                const hide = el => el.classList.remove('flex') && el.classList.add('hidden');

                // ——— Flower Modal (Add & Edit) ———————————————————————————————————————————————
                const flowerModal = document.getElementById('flower-modal');
                if (flowerModal) {
                    const flowerForm = document.getElementById('flower-form');
                    const flowerTitle = document.getElementById('flower-modal-title');
                    const flowerSubmit = document.getElementById('flower-form-submit');
                    const flowerMethod = document.getElementById('flower-form-method');
                    const flowerNameInput = document.getElementById('flower-name');
                    const flowerPriceInput = document.getElementById('flower-price');
                    const flowerStockInput = document.getElementById('flower-stock');

                    // Add New
                    document.querySelector('.add-flower-btn')?.addEventListener('click', () => {
                        flowerTitle.textContent = 'Add New Flower';
                        flowerSubmit.textContent = 'Add Flower';
                        flowerForm.action = "{{ route('admin.flowers.store') }}";
                        flowerMethod.value = 'POST';
                        flowerForm.reset();
                        flowerModal.classList.remove('hidden');
                        flowerModal.classList.add('flex');
                    });

                    // Edit Existing
                    document.querySelectorAll('.edit-flower-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const id = btn.dataset.id;
                            const f = flowerDataMap[id];
                            flowerTitle.textContent = 'Edit Flower';
                            flowerSubmit.textContent = 'Save Changes';
                            flowerForm.action = `/admin/flowers/${id}`;
                            flowerMethod.value = 'PUT';
                            flowerNameInput.value = f.name;
                            flowerPriceInput.value = f.price;
                            flowerStockInput.value = f.quantity;
                            flowerModal.classList.remove('hidden');
                            flowerModal.classList.add('flex');
                        });
                    });
                }


                // ——— Packaging Modal (Add & Edit) ————————————————————————————————————————
                const pkgModal = document.getElementById('packaging-modal');
                if (pkgModal) {
                    const pkgForm = document.getElementById('packaging-form');
                    const pkgMethod = document.getElementById('packaging-form-method');
                    const pkgTitle = document.getElementById('packaging-modal-title');
                    const pkgSubmit = document.getElementById('packaging-form-submit');
                    const pkgName = document.getElementById('packaging-name');
                    const pkgPrice = document.getElementById('packaging-price');

                    // 1) ADD NEW PACKAGING
                    document.querySelector('.add-packaging-btn')?.addEventListener('click', () => {
                        pkgTitle.textContent = 'Add New Packaging';
                        pkgSubmit.textContent = 'Add Packaging';
                        pkgForm.action = "{{ route('admin.packagings.store') }}";
                        pkgMethod.value = 'POST';
                        pkgForm.reset();
                        show(pkgModal);
                    });

                    // 2) EDIT EXISTING PACKAGING
                    document.querySelectorAll('.edit-packaging-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const id = btn.dataset.id;
                            const data = packagingDataMap[id];

                            pkgTitle.textContent = 'Edit Packaging';
                            pkgSubmit.textContent = 'Save Changes';
                            pkgForm.action = `/admin/packagings/${id}`;
                            pkgMethod.value = 'PUT';
                            pkgName.value = data.name;
                            pkgPrice.value = data.price;
                            show(pkgModal);
                        });
                    });

                    // 3) CLOSE HANDLERS (✕ and backdrop)
                    pkgModal.querySelectorAll('.close-modal').forEach(b =>
                        b.addEventListener('click', () => hide(pkgModal))
                    );
                    pkgModal.addEventListener('click', e => {
                        if (e.target === pkgModal) hide(pkgModal);
                    });

                }

                // ——— Discount Modal (Add & Edit) ————————————————————————————————————————
                const discountModal = document.getElementById('discount-modal');
                if (discountModal) {
                    const discountForm = document.getElementById('discount-form');
                    const discountMethod = document.getElementById('discount-form-method');
                    const discountTitle = document.getElementById('discount-modal-title');
                    const discountSubmit = document.getElementById('discount-form-submit');
                    const discountCode = document.getElementById('discount-code');
                    const discountPercent = document.getElementById('discount-percent');
                    const discountMaxValue = document.getElementById('discount-maxvalue');
                    const discountMinPurchase = document.getElementById('discount-minpurchase');
                    const discountUsageLimit = document.getElementById('discount-usagelimit');
                    const discountUsageCounter = document.getElementById('discount-usagecounter');
                    const discountStartDate = document.getElementById('discount-start-date');
                    const discountEndDate = document.getElementById('discount-end-date');

                    // 1) ADD NEW DISCOUNT
                    document.querySelector('.add-discount-btn')?.addEventListener('click', () => {
                        discountTitle.textContent = 'Add New Discount';
                        discountSubmit.textContent = "Add Discount";
                        discountForm.action = "{{ route('admin.discounts.store') }}";
                        discountMethod.value = 'POST';
                        discountForm.reset();
                        show(discountModal);
                    });

                    // 2) EDIT EXISTING DISCOUNT
                    document.querySelectorAll('.edit-discount-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const id = btn.dataset.id;
                            const data = discountDataMap[id];

                            discountTitle.textContent = 'Edit Discount';
                            discountSubmit.textContent = "Save Changes";
                            discountForm.action = `/admin/discounts/${id}`;
                            discountMethod.value = 'PUT';
                            discountCode.value = data.code;
                            discountPercent.value = data.percent;
                            discountMinPurchase.value = data.min_purchase;
                            discountMaxValue.value = data.max_value;
                            discountUsageLimit.value = data.usage_limit;
                            discountUsageCounter.value = data.usage_counter;
                            discountStartDate.value = data.start_date;
                            discountEndDate.value = data.end_date;
                            show(discountModal);
                        });
                    });
                    // 3) CLOSE HANDLERS (✕ and backdrop)
                    discountModal.querySelectorAll('.close-modal').forEach(b =>
                        b.addEventListener('click', () => hide(discountModal))
                    );
                    discountModal.addEventListener('click', e => {
                        if (e.target === discountModal) hide(discountModal);
                    });
                }


                // ——— Product Modal (Add & Edit via AJAX) —————————————————————————————————————
                const productModal = document.getElementById('product-modal');
                if (productModal) {
                    const productForm = document.getElementById('product-form');
                    productForm.addEventListener('submit', function(e) {
                        const checkboxes = productForm.querySelectorAll(
                            'input[type="checkbox"][name^="flowers"]');
                        const atLeastOneChecked = Array.from(checkboxes).some(cb => cb.checked);

                        const flowerList = document.getElementById('flower-list');
                        flowerList.classList.remove('ring-2', 'ring-red-500', 'rounded-md'); // cleanup first

                        if (!atLeastOneChecked) {
                            e.preventDefault();
                            flowerList.classList.add('ring-2', 'ring-red-500', 'rounded-md');
                            showToast('Please select at least one flower for the recipe.', 'error');
                        }
                    });
                    const productMethod = document.getElementById('product-form-method');
                    const productTitle = document.getElementById('product-modal-title');
                    const productSubmit = document.getElementById('product-form-submit');

                    // 1) ADD NEW PRODUCT
                    document.querySelector('.add-product-btn')?.addEventListener('click', () => {
                        productTitle.textContent = 'Add New Product';
                        productSubmit.textContent = 'Add Product';
                        productMethod.value = 'POST';
                        productForm.action = "{{ route('admin.products.store') }}";
                        productForm.reset();
                        populateFlowerList();
                        show(productModal);
                    });

                    // 2) EDIT EXISTING PRODUCT
                    document.querySelectorAll('.edit-product-btn').forEach(btn => {
                        btn.addEventListener('click', async () => {
                            const id = btn.dataset.id;
                            try {
                                // fetch the product JSON
                                const res = await fetch(`/admin/products/${id}`);
                                const data = await res.json();

                                // switch form into “edit” mode
                                productTitle.textContent = 'Edit Product';
                                productSubmit.textContent = 'Save Changes';
                                productMethod.value = 'PUT';
                                productForm.action = `/admin/products/${id}`;

                                // populate fields
                                productForm.name.value = data.name;
                                productForm.description.value = data.description;
                                productForm.price.value = data.price;
                                productForm.packaging_id.value = data.packaging_id;
                                productForm.in_stock.checked = data.in_stock;

                                // optional: image preview
                                const preview = document.getElementById('product-image-preview');
                                if (preview) preview.src = `/storage/${data.image_url}`;
                                const sel = data.recipe.reduce((acc, fp) => {
                                    acc[fp.flower_id] = fp.quantity;
                                    return acc;
                                }, {});
                                populateFlowerList(sel);
                                show(productModal);
                            } catch (err) {
                                console.error('Failed to load product:', err);
                                showToast('Could not load product details.', 'error');
                            }
                        });
                    });

                    // 3) CLOSE HANDLERS for Product Modal
                    if (productModal) {
                        productModal.querySelectorAll('.close-modal').forEach(b =>
                            b.addEventListener('click', () => hide(productModal))
                        );
                        productModal.addEventListener('click', e => {
                            if (e.target === productModal) hide(productModal);
                        });
                    }
                }


                // ——— View Order Modal 5 Recent & All——————————————————————————————————————————————————

                //Helper to format currency
                function fmt(v) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(v);
                }

                //Modal references
                const orderModal = document.getElementById('order-detail-modal');
                const closeButtons = orderModal.querySelectorAll('.close-modal');
                const discountRow = document.getElementById('modal-discount-row');
                const statusSelect = document.getElementById('modal-status-select');
                const saveStatusBtn = document.getElementById('modal-save-status');

                //Function fills up modals
                function populateModal(o) {
                    // header
                    document.getElementById('modal-order-id').textContent = '#' + o.id;
                    document.getElementById('modal-order-date').textContent = new Date(o.created_at).toLocaleString();
                    document.getElementById('modal-delivery-time').textContent = o.delivery_time;
                    document.getElementById('modal-current-status').textContent = o.progress;
                    statusSelect.value = o.progress;
                    document.getElementById('modal-location').textContent =
                        `${o.delivery?.city} — ${o.delivery?.subdistrict}`;

                    // customer
                    document.getElementById('modal-customer-name').textContent = o.user.name;
                    document.getElementById('modal-customer-email').textContent = o.user.email;
                    document.getElementById('modal-recipient-phone').textContent = o.recipient_phone;
                    document.getElementById('modal-recipient-address').textContent = o.recipient_address;
                    const payLink = document.getElementById('modal-payment-link');
                    if (o.payment_url) {
                        window.PAYMENT_BASE_URL = "{{ asset('payment') }}/";
                        payLink.href = window.PAYMENT_BASE_URL + o
                            .payment_url; // actually point the anchor at the image
                        document.getElementById('modal-payment-row').classList.remove('hidden');
                    } else {
                        document.getElementById('modal-payment-row').classList.add('hidden');
                    }

                    // items
                    const tbody = document.getElementById('modal-order-items');
                    tbody.innerHTML = '';
                    let subtotal = 0;
                    o.order_products.forEach(item => {
                        const p = item.product;
                        const tr = document.createElement('tr');
                        tr.className = 'border-b border-mocha-light/20';
                        tr.innerHTML = `
        <td class="px-4 py-3 flex items-center">
          <img src="/images/${p.image_url}" class="w-12 h-12 rounded mr-3">
          <h5 class="text-sm font-medium">${p.name}</h5>
        </td>
        <td class="px-4 py-3">${fmt(item.price/item.quantity)}</td>
        <td class="px-4 py-3">${item.quantity}</td>
        <td class="px-4 py-3 text-right">${fmt(item.price)}</td>
      `;
                        tbody.appendChild(tr);
                        subtotal += item.price;
                    });

                    // totals
                    document.getElementById('modal-subtotal').textContent = fmt(subtotal);
                    const shippingAmt = o.delivery_fee || 0;
                    document.getElementById('modal-shipping').textContent = fmt(shippingAmt);

                    // discount
                    let discountAmt = 0;
                    if (o.discount && subtotal >= o.discount.min_purchase) {
                        discountAmt = subtotal * (o.discount.percent / 100);
                        if (o.discount.max_value && discountAmt > o.discount.max_value) {
                            discountAmt = o.discount.max_value;
                        }
                    }
                    if (discountAmt > 0) {
                        discountRow.classList.remove('hidden');
                        document.getElementById('modal-discount').textContent = fmt(discountAmt);
                    } else {
                        discountRow.classList.add('hidden');
                    }

                    // grand total
                    document.getElementById('modal-total').textContent = fmt(subtotal + shippingAmt - discountAmt);

                    // notes
                    document.getElementById('modal-notes').textContent = o.sender_note || '';

                    orderModal.classList.remove('hidden');
                    orderModal.classList.add('flex');
                }

                // ⑤ wire “Recent Orders” buttons
                document.querySelectorAll('.view-order-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        const o = recentOrders.find(x => x.id == id);
                        if (o) populateModal(o);
                    });
                });

                // ⑥ wire “All Orders” buttons
                document.querySelectorAll('.details-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.orderId;
                        const o = allOrders.find(x => x.id == id);
                        if (o) populateModal(o);
                    });
                });



                closeButtons.forEach(btn =>
                    btn.addEventListener('click', () => {
                        orderModal.classList.remove('flex');
                        orderModal.classList.add('hidden');
                    })
                );
                orderModal.addEventListener('click', e => {
                    // If the click target isn't inside the white panel, close
                    if (!e.target.closest('.bg-white')) {
                        hide(orderModal);
                    }
                });


                saveStatusBtn.addEventListener('click', () => {
                    const orderId = document.getElementById('modal-order-id').textContent.slice(1);
                    const newStatus = document.getElementById('modal-status-select').value;

                    fetch(`/admin/orders/${orderId}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                progress: newStatus
                            })
                        })
                        .then(async res => {
                            if (!res.ok) {
                                const text = await res.text();
                                throw new Error(`HTTP ${res.status}: ${text}`);
                            }
                            return res.json();
                        })
                        .then(json => {
                            // update rows & modal
                            document
                                .querySelectorAll(
                                    `.view-order-btn[data-id="${orderId}"], .details-btn[data-order-id ="${orderId}"]`
                                )
                                .forEach(btn => {
                                    const row = btn.closest('tr');
                                    const badge = row.querySelector('span');
                                    badge.textContent = json.new_status;
                                    badge.className =
                                        'px-2 py-1 text-xs rounded-full text-white ' +
                                        ({
                                            'Payment Pending': 'badge-pending',
                                            'On Progress': 'badge-onprogress',
                                            'Ready to Deliver': 'badge-readytodeliver',
                                            'Delivery': 'badge-delivery',
                                            'Completed': 'badge-success',
                                            'Cancelled': 'badge-cancelled'
                                        } [json.new_status] || 'badge-pending');
                                });

                            document.getElementById('modal-current-status').textContent = json.new_status;
                            document.getElementById('modal-status-select').value = json.new_status;

                            // 3) Update both in-memory arrays
                            let o = recentOrders.find(x => x.id == orderId);
                            if (o) o.progress = json.new_status;
                            let a = allOrders.find(x => x.id == orderId);
                            if (a) a.progress = json.new_status;



                            showToast(`Status updated to “${json.new_status}”`);
                            setTimeout(() => {
                                orderModal.classList.remove('flex');
                                orderModal.classList.add('hidden');
                                if (o.progress === "Cancelled") {
                                    window.location.reload();
                                }
                            }, 150);
                        })
                        .catch(err => {
                            console.error('Order-status update failed:', err);
                            showToast('Couldn’t update status. Order already cancelled!', 'error');
                            orderModal.classList.remove('flex');
                            orderModal.classList.add('hidden');
                        });
                });

                // ——— Delivery Fee Modal (Add & Edit) ——————————————————————————————————————
                const deliveryModal = document.getElementById('update-delivery-modal');
                if (deliveryModal) {
                    const deliveryForm = document.getElementById('edit-delivery-form');

                    // EDIT EXISTING DELIVERY
                    document
                        .querySelectorAll('.edit-delivery-btn')
                        .forEach(btn => {
                            btn.addEventListener('click', () => {
                                const id = btn.dataset.id;
                                const subdistrict = btn.dataset.subdistrict;
                                const fee = btn.dataset.fee;

                                // populate modal
                                document.getElementById('update-delivery-subdistrict')
                                    .textContent = subdistrict;
                                document.getElementById('update-delivery-fee').value = fee;
                                deliveryForm.action = `/admin/deliveries/${id}/fee`;

                                // show it
                                show(deliveryModal);
                            });
                        });
                }

                // ——— Toast on ANY other modal form submit —————————————————————————————————
                document.querySelectorAll(
                        '.modal form:not(#flower-form):not(#packaging-form):not(#discount-form):not(#product-form):not(#edit-delivery-form)')
                    .forEach(f => {
                        f.addEventListener('submit', e => {
                            e.preventDefault();
                            document.querySelectorAll('.modal.flex').forEach(m => hide(m));
                            showToast('Changes saved successfully!');
                        });
                    });


                // ——— Page Navigation & Hash on Load ————————————————————————————————
                const navLinks = document.querySelectorAll('.nav-link');
                const pages = document.querySelectorAll('.page');
                const pageTitle = document.getElementById('page-title');

                navLinks.forEach(link => {
                    link.addEventListener('click', e => {
                        e.preventDefault();
                        navLinks.forEach(l => l.classList.remove('bg-mocha-burgundy', 'bg-opacity-50'));
                        link.classList.add('bg-mocha-burgundy', 'bg-opacity-50');
                        pages.forEach(p => p.classList.add('hidden'));
                        document.getElementById(`${link.dataset.page}-page`).classList.remove('hidden');
                        pageTitle.textContent = link.querySelector('.sidebar-link-text').textContent;
                    });
                });

                // read both fragment and tab param
                const hash = window.location.hash.slice(1);
                const urlParams = new URLSearchParams(window.location.search);
                const tab = urlParams.get('tab');

                // 1) if there’s a #fragment, open that tab
                if (hash) {
                    document.querySelector(`.nav-link[data-page="${hash}"]`)?.click();
                }
                // 2) else if there’s a tab=orders (from your hidden input), open orders
                else if (tab) {
                    document.querySelector(`.nav-link[data-page="${tab}"]`)?.click();
                }
                // 3) else if it’s one of the stocklogs paginations, open stocklogs
                else if (
                    urlParams.has('fo_page') ||
                    urlParams.has('fi_page') ||
                    urlParams.has('po_page') ||
                    urlParams.has('pi_page')
                ) {
                    document.querySelector('.nav-link[data-page="stocklogs"]')?.click();
                }

                // 4) optional: clean the URL so you don’t carry stale params
                const cleanPath = window.location.pathname;
                const cleanHash = hash ? `#${hash}` : '';
                window.history.replaceState(null, '', cleanPath + cleanHash);

                // ——— Sales Chart —————————————————————————————————————————————————————
                (function initSalesChart() {
                    const ctx = document.getElementById('salesChart').getContext('2d');
                    const select = document.getElementById('sales-range');
                    const fmtC = v => {
                        if (v >= 1e6) return 'Rp ' + (v / 1e6).toFixed(1) + 'M';
                        if (v >= 1e3) return 'Rp ' + (v / 1e3).toFixed(0) + 'K';
                        return 'Rp ' + v;
                    };

                    function config(period) {
                        const raw = salesChartData[period];
                        return {
                            type: 'line',
                            data: {
                                labels: Object.keys(raw),
                                datasets: [{
                                    label: `Last ${period} Days`,
                                    data: Object.values(raw),
                                    borderColor: '#741D29',
                                    backgroundColor: 'rgba(116,29,41,0.1)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    pointBackgroundColor: '#741D29',
                                    pointRadius: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: {
                                            usePointStyle: true,
                                            font: {
                                                family: 'Montserrat'
                                            }
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: c => `${c.dataset.label}: ${fmtC(c.raw)}`
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: {
                                            font: {
                                                family: 'Montserrat'
                                            }
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: fmtC,
                                            font: {
                                                family: 'Montserrat'
                                            }
                                        },
                                        grid: {
                                            borderDash: [2, 4],
                                            color: '#e5e7eb'
                                        }
                                    }
                                }
                            }
                        };
                    }
                    let chart = new Chart(ctx, config('7'));
                    select.addEventListener('change', () => {
                        chart.destroy();
                        chart = new Chart(ctx, config(select.value));
                    });
                })();

                // ——— Sidebar Toggle ————————————————————————————————————————————————————
                const sidebar = document.getElementById('sidebar');
                const toggleSidebarBtn = document.getElementById('toggle-sidebar');
                const icon = document.querySelector('.sidebar-toggle-icon');
                const text = document.querySelector('.sidebar-toggle-text');
                const linkTexts = document.querySelectorAll('.sidebar-link-text');
                const sidebarTitleElem = document.querySelector('.sidebar-title');

                toggleSidebarBtn.addEventListener('click', e => {
                    e.preventDefault();
                    sidebar.classList.toggle('collapsed');
                    if (sidebar.classList.contains('collapsed')) {
                        icon.classList.replace('fa-chevron-left', 'fa-chevron-right');
                        text.style.display = 'none';
                        linkTexts.forEach(t => t.style.display = 'none');
                        sidebarTitleElem.style.display = 'none';
                    } else {
                        icon.classList.replace('fa-chevron-right', 'fa-chevron-left');
                        text.style.display = 'block';
                        linkTexts.forEach(t => t.style.display = 'block');
                        sidebarTitleElem.style.display = 'block';
                    }
                });

                // ——— Mobile Menu —————————————————————————————————————————————————————
                document.getElementById('mobile-menu-button').addEventListener('click', () => {
                    sidebar.classList.toggle('show-mobile');
                });

                // ——— File Browse Buttons —————————————————————————————————————————————————
                document.querySelectorAll('.browse-btn').forEach(b => {
                    b.addEventListener('click', () => {
                        b.parentElement.querySelector('input[type="file"]').click();
                    });
                });
            });
        </script>

        <script>
            // universal “×” button closer
            document.querySelectorAll('.modal .close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    const m = btn.closest('.modal');
                    m.classList.remove('flex');
                    m.classList.add('hidden');
                });
            });

            // click-outside (backdrop) closer
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', e => {
                    // if you click directly on the backdrop (not on the inner content)
                    if (e.target === modal) {
                        modal.classList.remove('flex');
                        modal.classList.add('hidden');
                    }
                });
            });
        </script>
        <script>
            function updateClock() {
                const clockEl = document.getElementById('clock');
                if (!clockEl) return;
                const now = new Date();
                // e.g. "14:05:09"
                const timeString = now.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                clockEl.textContent = timeString;
            }
            // init + tick every second
            updateClock();
            setInterval(updateClock, 1000);
        </script>

        <script>
            async function checkHealth() {
                const dot = document.getElementById('sys-dot');
                const wrap = document.getElementById('sys-status');
                if (!dot || !wrap)
                    return; // not index.blade.php, so skip ~ ada issue entah kenapa suka nongol @ first login of the day
                try {
                    const res = await fetch('{{ route('admin.health') }}', {
                        credentials: 'same-origin'
                    });
                    if (!res.ok) throw new Error(res.statusText);

                    const {
                        healthy,
                        checks,
                        timestamp
                    } = await res.json();

                    if (healthy) {
                        dot.className = 'inline-block w-3 h-3 rounded-full bg-status-ok mr-2';
                        wrap.title = `All systems OK — ${timestamp}`;
                    } else {
                        // find first failed service
                        const failed = Object.entries(checks).filter(([, ok]) => !ok).map(([k]) => k).join(', ');
                        dot.className = 'inline-block w-3 h-3 rounded-full bg-status-err mr-2';
                        wrap.title = `Issue: ${failed} — ${timestamp}`;
                    }
                } catch (e) {
                    console.warn('Health check failed', e);
                }
            }
            // Initial check + interval
            checkHealth();
            setInterval(checkHealth, 300_000); // every 1hr
        </script>

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast(@json($errors->first()), 'error');
                });
            </script>
        @endif
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast(@json(session('success')), 'success');
                });
            </script>
        @endif
        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast(@json(session('error')), 'error');
                });
            </script>
        @endif

</body>
