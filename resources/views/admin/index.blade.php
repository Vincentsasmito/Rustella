<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

            <div class="mb-4">
                <div class="flex items-center p-3 rounded-lg mb-2">
                    <img src="/api/placeholder/48/48" alt="Admin" class="w-10 h-10 rounded-full mr-3">
                    <div>
                        <h3 class="text-sm font-semibold">Admin User</h3>
                        <p class="text-xs text-mocha-light">Administrator</p>
                    </div>
                </div>
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
                <a href="#packaging"
                    class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 mb-1"
                    data-page="packaging">
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
                <a href="#settings"
                    class="nav-link flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 mb-1"
                    data-page="settings">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span class="sidebar-link-text">Settings</span>
                </a>
            </nav>
        </div>
        <div class="absolute bottom-0 w-full p-5">
            <a href="#" id="toggle-sidebar"
                class="flex items-center justify-center space-x-2 p-2 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30">
                <i class="fas fa-chevron-left sidebar-toggle-icon"></i>
                <span class="sidebar-toggle-text">Collapse</span>
            </a>
            <a href="#"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-mocha-burgundy hover:bg-opacity-30 mt-3">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                <span class="sidebar-link-text">Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content flex-1 overflow-hidden">
        <!-- Topbar -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between px-6 py-3">
                <div class="flex items-center">
                    <button id="mobile-menu-button" class="md:hidden text-mocha-dark mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-semibold" id="page-title">Dashboard</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="text-mocha-dark hover:text-mocha-burgundy">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-mocha-burgundy"></span>
                        </button>
                    </div>
                    <div class="dropdown">
                        <button class="flex items-center space-x-2">
                            <img src="/api/placeholder/48/48" alt="Admin" class="w-8 h-8 rounded-full">
                            <span class="hidden md:block">Admin</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="dropdown-content mt-2 py-2">
                            <a href="#"
                                class="block px-4 py-2 text-sm text-mocha-dark hover:bg-mocha-cream">Profile</a>
                            <a href="#"
                                class="block px-4 py-2 text-sm text-mocha-dark hover:bg-mocha-cream">Settings</a>
                            <div class="border-t border-gray-200 my-1"></div>
                            <a href="#"
                                class="block px-4 py-2 text-sm text-mocha-burgundy hover:bg-mocha-cream">Logout</a>
                        </div>
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

                    <!-- Customers Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-mocha-medium mb-1">Customers (Month-to-Date)</p>
                                <h3 class="text-2xl font-bold">
                                    {{ $totalCustomers }}
                                </h3>
                                <p class="text-xs mt-1 {{ $customersUp ? 'text-green-500' : 'text-red-500' }}">
                                    <i class="fas fa-arrow-{{ $customersUp ? 'up' : 'down' }} mr-1"></i>
                                    {{ $customersChange }}% from last month
                                </p>
                            </div>
                            <div class="rounded-full bg-purple-100 p-3">
                                <i class="fas fa-users text-purple-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Products Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-mocha-medium mb-1">Products</p>
                                <h3 class="text-2xl font-bold">{{ $totalProducts }}</h3>
                                <p class="text-xs {{ $productsUp ? 'text-green-500' : 'text-red-500' }} mt-1">
                                    <i class="fas fa-arrow-{{ $productsUp ? 'up' : 'down' }} mr-1"></i>
                                    {{ $productsChange }}% from last month
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
                            <div class="flex items-center">
                                <img src="/api/placeholder/60/60" alt="Product 1"
                                    class="w-12 h-12 rounded object-cover mr-4">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium">Bunga 1</h4>
                                    <p class="text-xs text-mocha-medium">124 sales</p>
                                </div>
                                <span class="text-mocha-burgundy font-semibold">Rp.350.000</span>
                            </div>
                            <div class="flex items-center">
                                <img src="/api/placeholder/60/60" alt="Product 2"
                                    class="w-12 h-12 rounded object-cover mr-4">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium">Bunga 2</h4>
                                    <p class="text-xs text-mocha-medium">98 sales</p>
                                </div>
                                <span class="text-mocha-burgundy font-semibold">Rp.750.000</span>
                            </div>
                            <div class="flex items-center">
                                <img src="/api/placeholder/60/60" alt="Product 3"
                                    class="w-12 h-12 rounded object-cover mr-4">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium">Melati</h4>
                                    <p class="text-xs text-mocha-medium">87 sales</p>
                                </div>
                                <span class="text-mocha-burgundy font-semibold">Rp.200.000</span>
                            </div>
                            <div class="flex items-center">
                                <img src="/api/placeholder/60/60" alt="Product 4"
                                    class="w-12 h-12 rounded object-cover mr-4">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium">Mawar</h4>
                                    <p class="text-xs text-mocha-medium">76 sales</p>
                                </div>
                                <span class="text-mocha-burgundy font-semibold">Rp.150.000</span>
                            </div>
                        </div>
                        <a href="#flowers"
                            class="block text-center text-mocha-burgundy text-sm mt-4 hover:underline">View All
                            Products</a>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg">Recent Orders</h3>
                        <a href="#orders" class="text-mocha-burgundy text-sm hover:underline">View All</a>
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
                                        Customer</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#ORD-5123</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Sarah Johnson</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 8, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 350.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full text-white badge-success">Completed</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="view-order-btn text-mocha-burgundy hover:underline"
                                            data-id="ORD-5123">View</button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#ORD-5122</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Michael Tan</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 7, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 750.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full text-white badge-processing">Processing</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="view-order-btn text-mocha-burgundy hover:underline"
                                            data-id="ORD-5122">View</button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#ORD-5121</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Jessica Lee</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 7, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 200.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full text-white badge-pending">Pending</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="view-order-btn text-mocha-burgundy hover:underline"
                                            data-id="ORD-5121">View</button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#ORD-5120</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Robert Chen</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 6, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 150.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full text-white badge-cancelled">Cancelled</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="view-order-btn text-mocha-burgundy hover:underline"
                                            data-id="ORD-5120">View</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Flowers Page -->
            <div id="flowers-page" class="page hidden">
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6 border-b border-mocha-light/20">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h2 class="text-xl font-semibold mb-4 md:mb-0">Flower Management</h2>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div class="relative">
                                    <input type="text" placeholder="Search flowers..."
                                        class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy">
                                    <i class="fas fa-search absolute right-3 top-3 text-mocha-medium"></i>
                                </div>
                                <button
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
                                        Image</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Category</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Price</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Stock</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#FL001</td>
                                    <td class="px-4 py-3">
                                        <img src="/api/placeholder/80/60" alt="Bunga 1"
                                            class="w-12 h-12 rounded object-cover">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">Bunga 1</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Bouquets</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 350.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">25</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="edit-flower-btn text-blue-600 hover:text-blue-800 mr-3"
                                            data-id="FL001">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-flower-btn text-red-600 hover:text-red-800"
                                            data-id="FL001">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#FL002</td>
                                    <td class="px-4 py-3">
                                        <img src="/api/placeholder/80/60" alt="Bunga 2"
                                            class="w-12 h-12 rounded object-cover">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">Bunga 2</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Bouquets</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 750.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">18</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="edit-flower-btn text-blue-600 hover:text-blue-800 mr-3"
                                            data-id="FL002">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-flower-btn text-red-600 hover:text-red-800"
                                            data-id="FL002">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#FL003</td>
                                    <td class="px-4 py-3">
                                        <img src="/api/placeholder/80/60" alt="Melati"
                                            class="w-12 h-12 rounded object-cover">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">Melati</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Single Flowers</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 200.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">30</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="edit-flower-btn text-blue-600 hover:text-blue-800 mr-3"
                                            data-id="FL003">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-flower-btn text-red-600 hover:text-red-800"
                                            data-id="FL003">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Packaging Page -->
            <div id="packaging-page" class="page hidden">
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6 border-b border-mocha-light/20">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h2 class="text-xl font-semibold mb-4 md:mb-0">Packaging Options</h2>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div class="relative">
                                    <input type="text" placeholder="Search packaging..."
                                        class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy">
                                    <i class="fas fa-search absolute right-3 top-3 text-mocha-medium"></i>
                                </div>
                                <button
                                    class="add-packaging-btn bg-mocha-burgundy text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition flex items-center justify-center">
                                    <i class="fas fa-plus mr-2"></i> Add New Packaging
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
                                        Image</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Type</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Price</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Stock</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#PK001</td>
                                    <td class="px-4 py-3">
                                        <img src="/api/placeholder/80/60" alt="Elegant Box"
                                            class="w-12 h-12 rounded object-cover">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">Elegant Box</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Box</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 45.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">60</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="edit-packaging-btn text-blue-600 hover:text-blue-800 mr-3"
                                            data-id="PK001">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-packaging-btn text-red-600 hover:text-red-800"
                                            data-id="PK001">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#PK002</td>
                                    <td class="px-4 py-3">
                                        <img src="/api/placeholder/80/60" alt="Premium Wrap"
                                            class="w-12 h-12 rounded object-cover">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">Premium Wrap</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Wrapping</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 25.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">42</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="edit-packaging-btn text-blue-600 hover:text-blue-800 mr-3"
                                            data-id="PK002">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-packaging-btn text-red-600 hover:text-red-800"
                                            data-id="PK002">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#PK003</td>
                                    <td class="px-4 py-3">
                                        <img src="/api/placeholder/80/60" alt="Kraft Paper"
                                            class="w-12 h-12 rounded object-cover">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">Kraft Paper</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Wrapping</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 15.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">38</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="edit-packaging-btn text-blue-600 hover:text-blue-800 mr-3"
                                            data-id="PK003">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-packaging-btn text-red-600 hover:text-red-800"
                                            data-id="PK003">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#PK004</td>
                                    <td class="px-4 py-3">
                                        <img src="/api/placeholder/80/60" alt="Glass Vase"
                                            class="w-12 h-12 rounded object-cover">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">Glass Vase</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Container</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 85.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">12</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Low
                                            Stock</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="edit-packaging-btn text-blue-600 hover:text-blue-800 mr-3"
                                            data-id="PK004">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-packaging-btn text-red-600 hover:text-red-800"
                                            data-id="PK004">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
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
                                <div class="relative">
                                    <input type="text" placeholder="Search orders..."
                                        class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy">
                                    <i class="fas fa-search absolute right-3 top-3 text-mocha-medium"></i>
                                </div>
                                <div>
                                    <select
                                        class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy">
                                        <option value="">Filter by Status</option>
                                        <option value="completed">Completed</option>
                                        <option value="processing">Processing</option>
                                        <option value="pending">Pending</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <button
                                    class="export-orders-btn bg-mocha-dark text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition flex items-center justify-center">
                                    <i class="fas fa-download mr-2"></i> Export
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
                                        Order ID</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Customer</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Products</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#ORD-5123</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Sarah Johnson</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 8, 2025</td>
                                    <td class="px-4 py-3">Bunga 1 (x1)</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 350.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full text-white badge-success">Completed</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="view-order-btn text-mocha-burgundy hover:text-opacity-80 mr-3"
                                            data-id="ORD-5123">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="print-order-btn text-blue-600 hover:text-blue-800"
                                            data-id="ORD-5123">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#ORD-5122</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Michael Tan</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 7, 2025</td>
                                    <td class="px-4 py-3">Bunga 2 (x1)</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 750.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full text-white badge-processing">Processing</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="view-order-btn text-mocha-burgundy hover:text-opacity-80 mr-3"
                                            data-id="ORD-5122">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="print-order-btn text-blue-600 hover:text-blue-800"
                                            data-id="ORD-5122">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#ORD-5121</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Jessica Lee</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 7, 2025</td>
                                    <td class="px-4 py-3">Melati (x1)</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 200.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full text-white badge-pending">Pending</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="view-order-btn text-mocha-burgundy hover:text-opacity-80 mr-3"
                                            data-id="ORD-5121">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="print-order-btn text-blue-600 hover:text-blue-800"
                                            data-id="ORD-5121">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#ORD-5120</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Robert Chen</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 6, 2025</td>
                                    <td class="px-4 py-3">Mawar (x1)</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 150.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full text-white badge-cancelled">Cancelled</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="view-order-btn text-mocha-burgundy hover:text-opacity-80 mr-3"
                                            data-id="ORD-5120">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="print-order-btn text-blue-600 hover:text-blue-800"
                                            data-id="ORD-5120">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between p-6">
                        <div class="flex items-center text-sm text-mocha-medium">
                            Showing <span class="font-medium mx-1">1</span> to <span class="font-medium mx-1">4</span>
                            of <span class="font-medium mx-1">24</span> results
                        </div>
                        <div class="flex space-x-1">
                            <button
                                class="px-3 py-1 rounded border border-mocha-light/30 text-mocha-medium hover:bg-mocha-cream/30 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            <button
                                class="px-3 py-1 rounded border border-mocha-burgundy bg-mocha-burgundy text-white">1</button>
                            <button
                                class="px-3 py-1 rounded border border-mocha-light/30 text-mocha-medium hover:bg-mocha-cream/30">2</button>
                            <button
                                class="px-3 py-1 rounded border border-mocha-light/30 text-mocha-medium hover:bg-mocha-cream/30">3</button>
                            <button
                                class="px-3 py-1 rounded border border-mocha-light/30 text-mocha-medium hover:bg-mocha-cream/30 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suggestions Page -->
            <div id="suggestions-page" class="page hidden">
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6 border-b border-mocha-light/20">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h2 class="text-xl font-semibold mb-4 md:mb-0">Customer Suggestions</h2>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div class="relative">
                                    <input type="text" placeholder="Search suggestions..."
                                        class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy">
                                    <i class="fas fa-search absolute right-3 top-3 text-mocha-medium"></i>
                                </div>
                                <div>
                                    <select
                                        class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy">
                                        <option value="">Filter by Status</option>
                                        <option value="new">New</option>
                                        <option value="under-review">Under Review</option>
                                        <option value="implemented">Implemented</option>
                                        <option value="declined">Declined</option>
                                    </select>
                                </div>
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
                                        Customer</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Subject</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Type</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#SUG-001</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Sarah Johnson</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 8, 2025</td>
                                    <td class="px-4 py-3">More seasonal bouquet options</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Product</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">New</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button
                                            class="view-suggestion-btn text-mocha-burgundy hover:text-opacity-80 mr-3"
                                            data-id="SUG-001">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="update-suggestion-btn text-blue-600 hover:text-blue-800"
                                            data-id="SUG-001">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#SUG-002</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Michael Tan</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 7, 2025</td>
                                    <td class="px-4 py-3">Package recycling program</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Environment</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Under
                                            Review</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button
                                            class="view-suggestion-btn text-mocha-burgundy hover:text-opacity-80 mr-3"
                                            data-id="SUG-002">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="update-suggestion-btn text-blue-600 hover:text-blue-800"
                                            data-id="SUG-002">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#SUG-003</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Jessica Lee</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 5, 2025</td>
                                    <td class="px-4 py-3">Loyalty program for repeat customers</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Marketing</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Implemented</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button
                                            class="view-suggestion-btn text-mocha-burgundy hover:text-opacity-80 mr-3"
                                            data-id="SUG-003">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="update-suggestion-btn text-blue-600 hover:text-blue-800"
                                            data-id="SUG-003">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">#SUG-004</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Robert Chen</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 3, 2025</td>
                                    <td class="px-4 py-3">Bulk ordering for events</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Service</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Declined</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button
                                            class="view-suggestion-btn text-mocha-burgundy hover:text-opacity-80 mr-3"
                                            data-id="SUG-004">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="update-suggestion-btn text-blue-600 hover:text-blue-800"
                                            data-id="SUG-004">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Discounts Page -->
            <div id="discounts-page" class="page hidden">
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6 border-b border-mocha-light/20">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h2 class="text-xl font-semibold mb-4 md:mb-0">Discount Management</h2>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div class="relative">
                                    <input type="text" placeholder="Search discounts..."
                                        class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy">
                                    <i class="fas fa-search absolute right-3 top-3 text-mocha-medium"></i>
                                </div>
                                <button
                                    class="add-discount-btn bg-mocha-burgundy text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition flex items-center justify-center">
                                    <i class="fas fa-plus mr-2"></i> Add New Discount
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
                                        Code</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Type</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Start Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        End Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Usage Limit</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">WELCOME25</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Percentage</td>
                                    <td class="px-4 py-3 whitespace-nowrap">25%</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 1, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 31, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Unlimited</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="edit-discount-btn text-blue-600 hover:text-blue-800 mr-3"
                                            data-id="WELCOME25">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-discount-btn text-red-600 hover:text-red-800"
                                            data-id="WELCOME25">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">SUMMER50</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Percentage</td>
                                    <td class="px-4 py-3 whitespace-nowrap">50%</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Jun 1, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Aug 31, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">100</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Scheduled</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="edit-discount-btn text-blue-600 hover:text-blue-800 mr-3"
                                            data-id="SUMMER50">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-discount-btn text-red-600 hover:text-red-800"
                                            data-id="SUMMER50">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">FREESHIP</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Fixed</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Rp 25.000</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 1, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">May 31, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">50</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="edit-discount-btn text-blue-600 hover:text-blue-800 mr-3"
                                            data-id="FREESHIP">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-discount-btn text-red-600 hover:text-red-800"
                                            data-id="FREESHIP">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-mocha-light/20 hover:bg-mocha-light/10">
                                    <td class="px-4 py-3 whitespace-nowrap">SPRING15</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Percentage</td>
                                    <td class="px-4 py-3 whitespace-nowrap">15%</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Apr 1, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">Apr 30, 2025</td>
                                    <td class="px-4 py-3 whitespace-nowrap">200</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Expired</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <button class="edit-discount-btn text-blue-600 hover:text-blue-800 mr-3"
                                            data-id="SPRING15">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-discount-btn text-red-600 hover:text-red-800"
                                            data-id="SPRING15">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Settings Page -->
            <div id="settings-page" class="page hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- General Settings -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow mb-6">
                            <div class="p-6 border-b border-mocha-light/20">
                                <h2 class="text-xl font-semibold">General Settings</h2>
                            </div>
                            <div class="p-6">
                                <form id="general-settings-form">
                                    <div class="space-y-6">
                                        <div>
                                            <label for="store-name"
                                                class="block text-sm font-medium text-mocha-dark mb-1">Store
                                                Name</label>
                                            <input type="text" id="store-name" value="Rustella Floristry"
                                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                                required>
                                        </div>
                                        <div>
                                            <label for="store-email"
                                                class="block text-sm font-medium text-mocha-dark mb-1">Store
                                                Email</label>
                                            <input type="email" id="store-email" value="info@rustellafloristry.com"
                                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                                required>
                                        </div>
                                        <div>
                                            <label for="store-phone"
                                                class="block text-sm font-medium text-mocha-dark mb-1">Store
                                                Phone</label>
                                            <input type="tel" id="store-phone" value="+62 812-3456-7890"
                                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                                required>
                                        </div>
                                        <div>
                                            <label for="store-address"
                                                class="block text-sm font-medium text-mocha-dark mb-1">Store
                                                Address</label>
                                            <textarea id="store-address" rows="3"
                                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                                required>Jl. Merpati No. 123, Jakarta Selatan, 12345</textarea>
                                        </div>
                                        <div>
                                            <label for="store-currency"
                                                class="block text-sm font-medium text-mocha-dark mb-1">Currency</label>
                                            <select id="store-currency"
                                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                                required>
                                                <option value="IDR" selected>Indonesian Rupiah (Rp)</option>
                                                <option value="USD">US Dollar ($)</option>
                                                <option value="EUR">Euro (€)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="store-timezone"
                                                class="block text-sm font-medium text-mocha-dark mb-1">Timezone</label>
                                            <select id="store-timezone"
                                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                                required>
                                                <option value="Asia/Jakarta" selected>Asia/Jakarta (GMT+7)</option>
                                                <option value="Asia/Singapore">Asia/Singapore (GMT+8)</option>
                                                <option value="Asia/Tokyo">Asia/Tokyo (GMT+9)</option>
                                            </select>
                                        </div>
                                        <div class="pt-4 border-t border-mocha-light/20">
                                            <button type="submit"
                                                class="px-4 py-2 bg-mocha-burgundy text-white rounded-md hover:bg-opacity-90 transition">Save
                                                Settings</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Settings -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow mb-6">
                            <div class="p-6 border-b border-mocha-light/20">
                                <h2 class="text-xl font-semibold">Profile Settings</h2>
                            </div>
                            <div class="p-6">
                                <form id="profile-settings-form">
                                    <div class="space-y-6">
                                        <div class="flex flex-col items-center">
                                            <div class="w-24 h-24 rounded-full overflow-hidden mb-4 relative">
                                                <img src="/api/placeholder/96/96" alt="Admin"
                                                    class="w-full h-full object-cover">
                                                <div
                                                    class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition cursor-pointer">
                                                    <span class="text-white text-sm">Change</span>
                                                </div>
                                                <input type="file" id="profile-image" class="hidden">
                                            </div>
                                            <button type="button" class="text-sm text-mocha-burgundy hover:underline"
                                                id="upload-profile-pic">Change Profile Picture</button>
                                        </div>
                                        <div>
                                            <label for="admin-name"
                                                class="block text-sm font-medium text-mocha-dark mb-1">Name</label>
                                            <input type="text" id="admin-name" value="Admin User"
                                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                                required>
                                        </div>
                                        <div>
                                            <label for="admin-email"
                                                class="block text-sm font-medium text-mocha-dark mb-1">Email</label>
                                            <input type="email" id="admin-email"
                                                value="admin@rustellafloristry.com"
                                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                                required>
                                        </div>
                                        <div>
                                            <label for="admin-password"
                                                class="block text-sm font-medium text-mocha-dark mb-1">New
                                                Password</label>
                                            <input type="password" id="admin-password"
                                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                                placeholder="Leave empty to keep current">
                                        </div>
                                        <div>
                                            <label for="admin-confirm-password"
                                                class="block text-sm font-medium text-mocha-dark mb-1">Confirm New
                                                Password</label>
                                            <input type="password" id="admin-confirm-password"
                                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                                placeholder="Leave empty to keep current">
                                        </div>
                                        <div class="pt-4 border-t border-mocha-light/20">
                                            <button type="submit"
                                                class="px-4 py-2 bg-mocha-burgundy text-white rounded-md hover:bg-opacity-90 transition">Update
                                                Profile</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <!-- Add Flower Modal -->
    <div id="add-flower-modal"
        class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Add New Flower</h3>
                <button class="close-modal text-mocha-dark hover:text-mocha-burgundy">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="add-flower-form">
                <div class="space-y-4">
                    <div>
                        <label for="flower-name" class="block text-sm font-medium text-mocha-dark mb-1">Flower
                            Name</label>
                        <input type="text" id="flower-name"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            required>
                    </div>
                    <div>
                        <label for="flower-category"
                            class="block text-sm font-medium text-mocha-dark mb-1">Category</label>
                        <select id="flower-category"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            required>
                            <option value="">Select Category</option>
                            <option value="Bouquets">Bouquets</option>
                            <option value="Arrangements">Arrangements</option>
                            <option value="Single Flowers">Single Flowers</option>
                            <option value="Wedding">Wedding</option>
                            <option value="Special Occasions">Special Occasions</option>
                        </select>
                    </div>
                    <div>
                        <label for="flower-price" class="block text-sm font-medium text-mocha-dark mb-1">Price
                            (Rp)</label>
                        <input type="number" id="flower-price"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            required>
                    </div>
                    <div>
                        <label for="flower-stock" class="block text-sm font-medium text-mocha-dark mb-1">Stock</label>
                        <input type="number" id="flower-stock"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            required>
                    </div>
                    <div>
                        <label for="flower-image" class="block text-sm font-medium text-mocha-dark mb-1">Image</label>
                        <div class="border border-dashed border-mocha-light/50 rounded-md p-4 text-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-mocha-medium mb-2"></i>
                            <p class="text-sm text-mocha-medium">Drop your image here or click to browse</p>
                            <input type="file" id="flower-image" class="hidden">
                            <button type="button" class="browse-btn mt-2 text-sm text-mocha-burgundy">Browse
                                Files</button>
                        </div>
                    </div>
                    <div>
                        <label for="flower-description"
                            class="block text-sm font-medium text-mocha-dark mb-1">Description</label>
                        <textarea id="flower-description" rows="3"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button"
                        class="close-modal px-4 py-2 border border-mocha-light rounded-md text-mocha-dark hover:bg-mocha-cream/50 transition">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-mocha-burgundy text-white rounded-md hover:bg-opacity-90 transition">Add
                        Packaging</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Add Packaging Modal -->
    <div id="add-packaging-modal"
        class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Add New Packaging</h3>
                <button class="close-modal text-mocha-dark hover:text-mocha-burgundy">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="add-packaging-form">
                <div class="space-y-4">
                    <div>
                        <label for="packaging-name" class="block text-sm font-medium text-mocha-dark mb-1">Packaging
                            Name</label>
                        <input type="text" id="packaging-name"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            required>
                    </div>
                    <div>
                        <label for="packaging-type"
                            class="block text-sm font-medium text-mocha-dark mb-1">Type</label>
                        <select id="packaging-type"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            required>
                            <option value="">Select Type</option>
                            <option value="Box">Box</option>
                            <option value="Wrapping">Wrapping</option>
                            <option value="Container">Container</option>
                            <option value="Ribbon">Ribbon</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="packaging-price" class="block text-sm font-medium text-mocha-dark mb-1">Price
                            (Rp)</label>
                        <input type="number" id="packaging-price"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            required>
                    </div>
                    <div>
                        <label for="packaging-stock"
                            class="block text-sm font-medium text-mocha-dark mb-1">Stock</label>
                        <input type="number" id="packaging-stock"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            required>
                    </div>
                    <div>
                        <label for="packaging-image"
                            class="block text-sm font-medium text-mocha-dark mb-1">Image</label>
                        <div
                            class="border border-dashed border-mocha-light/50 rounded-md p-4 text-center cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-3xl text-mocha-medium mb-2"></i>
                            <p class="text-sm text-mocha-medium">Drop your image here or click to browse</p>
                            <input type="file" id="packaging-image" accept="image/*" class="hidden">
                            <button type="button" class="browse-btn mt-2 text-sm text-mocha-burgundy">Browse
                                Files</button>
                        </div>
                    </div>
                    <div>
                        <label for="packaging-description"
                            class="block text-sm font-medium text-mocha-dark mb-1">Description</label>
                        <textarea id="packaging-description" rows="3"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button"
                        class="close-modal px-4 py-2 border border-mocha-light rounded-md text-mocha-dark hover:bg-mocha-cream/50 transition">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-mocha-burgundy text-white rounded-md hover:bg-mocha-dark transition">Add
                        Packaging</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Discount Modal -->
    <div id="discount-modal"
        class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Add New Discount</h3>
                <button class="close-modal text-mocha-dark hover:text-mocha-burgundy">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="discount-form">
                <div class="space-y-4">
                    <div>
                        <label for="discount-code" class="block text-sm font-medium text-mocha-dark mb-1">Discount
                            Code</label>
                        <input type="text" id="discount-code"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            required>
                    </div>
                    <div>
                        <label for="discount-type" class="block text-sm font-medium text-mocha-dark mb-1">Discount
                            Type</label>
                        <select id="discount-type"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            required>
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                    <div>
                        <label for="discount-amount" class="block text-sm font-medium text-mocha-dark mb-1">Discount
                            Amount</label>
                        <input type="number" id="discount-amount"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start-date" class="block text-sm font-medium text-mocha-dark mb-1">Start
                                Date</label>
                            <input type="date" id="start-date"
                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                required>
                        </div>
                        <div>
                            <label for="end-date" class="block text-sm font-medium text-mocha-dark mb-1">End
                                Date</label>
                            <input type="date" id="end-date"
                                class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="usage-limit" class="block text-sm font-medium text-mocha-dark mb-1">Usage
                            Limit</label>
                        <input type="number" id="usage-limit"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            placeholder="Leave empty for unlimited">
                    </div>
                    <div>
                        <label for="min-purchase" class="block text-sm font-medium text-mocha-dark mb-1">Minimum
                            Purchase (Rp)</label>
                        <input type="number" id="min-purchase"
                            class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                            placeholder="Leave empty for no minimum">
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-mocha-dark">
                            <input type="checkbox" id="active-status"
                                class="rounded border-mocha-light/30 text-mocha-burgundy focus:ring-mocha-burgundy mr-2">
                            Active Status
                        </label>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button"
                        class="close-modal px-4 py-2 border border-mocha-light rounded-md text-mocha-dark hover:bg-mocha-cream/50 transition">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-mocha-burgundy text-white rounded-md hover:bg-opacity-90 transition">Save
                        Discount</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Order Detail Modal -->
    <div id="order-detail-modal"
        class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Order #ORD-5123</h3>
                <button class="close-modal text-mocha-dark hover:text-mocha-burgundy">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="text-sm font-medium mb-3">Customer Information</h4>
                    <div class="bg-mocha-cream/20 p-4 rounded-lg">
                        <p class="text-sm mb-2"><span class="font-medium">Name:</span> Sarah Johnson</p>
                        <p class="text-sm mb-2"><span class="font-medium">Email:</span> sarah.j@example.com</p>
                        <p class="text-sm mb-2"><span class="font-medium">Phone:</span> +62 812-3456-7890</p>
                        <p class="text-sm"><span class="font-medium">Address:</span> Jl. Merpati No. 123, Jakarta
                            Selatan, 12345</p>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium mb-3">Order Information</h4>
                    <div class="bg-mocha-cream/20 p-4 rounded-lg">
                        <p class="text-sm mb-2"><span class="font-medium">Order Date:</span> May 8, 2025 - 10:23 AM
                        </p>
                        <p class="text-sm mb-2"><span class="font-medium">Payment Method:</span> Credit Card</p>
                        <p class="text-sm mb-2"><span class="font-medium">Payment Status:</span> <span
                                class="text-green-600">Paid</span></p>
                        <p class="text-sm"><span class="font-medium">Shipping Method:</span> Same Day Delivery</p>
                    </div>
                </div>
            </div>

            <h4 class="text-sm font-medium mb-3">Order Items</h4>
            <div class="overflow-x-auto mb-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-mocha-light/30">
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                Product</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                Unit Price</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                Quantity</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-mocha-medium uppercase tracking-wider">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-mocha-light/20">
                            <td class="px-4 py-3 flex items-center">
                                <img src="/api/placeholder/80/60" alt="Bunga 1"
                                    class="w-12 h-12 rounded object-cover mr-3">
                                <div>
                                    <h5 class="text-sm font-medium">Bunga 1</h5>
                                    <p class="text-xs text-mocha-medium">Bouquets</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">Rp 350,000</td>
                            <td class="px-4 py-3">1</td>
                            <td class="px-4 py-3 text-right">Rp 350,000</td>
                        </tr>
                        <tr class="border-b border-mocha-light/20">
                            <td class="px-4 py-3 flex items-center">
                                <img src="/api/placeholder/80/60" alt="Premium Wrap"
                                    class="w-12 h-12 rounded object-cover mr-3">
                                <div>
                                    <h5 class="text-sm font-medium">Premium Wrap</h5>
                                    <p class="text-xs text-mocha-medium">Packaging</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">Rp 25,000</td>
                            <td class="px-4 py-3">1</td>
                            <td class="px-4 py-3 text-right">Rp 25,000</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="border-b border-mocha-light/20">
                            <td colspan="3" class="px-4 py-3 text-right font-medium">Subtotal</td>
                            <td class="px-4 py-3 text-right">Rp 375,000</td>
                        </tr>
                        <tr class="border-b border-mocha-light/20">
                            <td colspan="3" class="px-4 py-3 text-right font-medium">Shipping</td>
                            <td class="px-4 py-3 text-right">Rp 25,000</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right font-medium">Total</td>
                            <td class="px-4 py-3 text-right font-bold">Rp 400,000</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="flex flex-col md:flex-row gap-6 mb-6">
                <div class="flex-1">
                    <h4 class="text-sm font-medium mb-3">Order Notes</h4>
                    <div class="bg-mocha-cream/20 p-4 rounded-lg h-32 overflow-y-auto">
                        <p class="text-sm">Please include a birthday card with the message "Happy Birthday Mom, love
                            you
                            always. - Sarah"</p>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-medium mb-3">Order Timeline</h4>
                    <div class="bg-mocha-cream/20 p-4 rounded-lg h-32 overflow-y-auto">
                        <div class="flex items-start mb-3">
                            <div class="w-2 h-2 rounded-full bg-mocha-burgundy mt-1.5 mr-2"></div>
                            <div>
                                <p class="text-xs text-mocha-medium">May 8, 2025 - 10:23 AM</p>
                                <p class="text-sm">Order placed</p>
                            </div>
                        </div>
                        <div class="flex items-start mb-3">
                            <div class="w-2 h-2 rounded-full bg-mocha-burgundy mt-1.5 mr-2"></div>
                            <div>
                                <p class="text-xs text-mocha-medium">May 8, 2025 - 10:25 AM</p>
                                <p class="text-sm">Payment confirmed</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-2 h-2 rounded-full bg-mocha-burgundy mt-1.5 mr-2"></div>
                            <div>
                                <p class="text-xs text-mocha-medium">May 8, 2025 - 11:30 AM</p>
                                <p class="text-sm">Order completed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <button
                        class="px-4 py-2 bg-mocha-dark text-white rounded-md hover:bg-opacity-90 transition flex items-center">
                        <i class="fas fa-print mr-2"></i> Print Invoice
                    </button>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm">Change Status:</span>
                        <select
                            class="px-3 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy text-sm">
                            <option value="completed" selected>Completed</option>
                            <option value="processing">Processing</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Suggestion Modal -->
    <div id="view-suggestion-modal"
        class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Suggestion #SUG-001</h3>
                <button class="close-modal text-mocha-dark hover:text-mocha-burgundy">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="text-sm font-medium mb-3">Customer Information</h4>
                    <div class="bg-mocha-cream/20 p-4 rounded-lg">
                        <p class="text-sm mb-2"><span class="font-medium">Name:</span> Sarah Johnson</p>
                        <p class="text-sm mb-2"><span class="font-medium">Email:</span> sarah.j@example.com</p>
                        <p class="text-sm"><span class="font-medium">Customer Since:</span> January 15, 2025</p>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium mb-3">Suggestion Information</h4>
                    <div class="bg-mocha-cream/20 p-4 rounded-lg">
                        <p class="text-sm mb-2"><span class="font-medium">Date Submitted:</span> May 8, 2025</p>
                        <p class="text-sm mb-2"><span class="font-medium">Type:</span> Product</p>
                        <p class="text-sm mb-2"><span class="font-medium">Subject:</span> More seasonal bouquet
                            options
                        </p>
                        <p class="text-sm"><span class="font-medium">Status:</span> <span
                                class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">New</span></p>
                    </div>
                </div>
            </div>

            <h4 class="text-sm font-medium mb-3">Suggestion Details</h4>
            <div class="bg-mocha-cream/20 p-4 rounded-lg mb-6">
                <p class="text-sm">I would love to see more seasonal bouquet options that highlight flowers currently
                    in
                    bloom. It would be great to have a rotating selection that changes with the seasons to showcase
                    what's freshest and most beautiful at any given time. Perhaps you could create a "Seasonal Special"
                    section on your website that updates every few months?</p>
            </div>

            <h4 class="text-sm font-medium mb-3">Admin Notes</h4>
            <div class="mb-6">
                <textarea rows="4"
                    class="w-full px-4 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy form-input"
                    placeholder="Add internal notes here..."></textarea>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <button class="px-4 py-2 bg-mocha-burgundy text-white rounded-md hover:bg-opacity-90 transition">
                        Send Response
                    </button>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm">Update Status:</span>
                        <select
                            class="px-3 py-2 border border-mocha-light/30 rounded-md focus:outline-none focus:border-mocha-burgundy text-sm">
                            <option value="new" selected>New</option>
                            <option value="under-review">Under Review</option>
                            <option value="implemented">Implemented</option>
                            <option value="declined">Declined</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast"
        class="toast fixed bottom-0 right-0 m-6 p-4 bg-mocha-dark text-white rounded-lg shadow-lg hidden flex items-center">
        <i class="fas fa-check-circle text-green-400 mr-3"></i>
        <span id="toast-message">Changes saved successfully!</span>
        <button class="ml-4 text-white/70 hover:text-white"
            onclick="document.getElementById('toast').classList.add('hidden')">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Scripts -->
    <script>
        const salesChartData = @json($salesChartData);
        console.log('salesChartData:', salesChartData);
    </script>

    <script>
        //initialize sales chart data
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const select = document.getElementById('sales-range');

            const formatCurrency = v => {
                if (v >= 1e6) return 'Rp ' + (v / 1e6).toFixed(1) + 'M';
                if (v >= 1e3) return 'Rp ' + (v / 1e3).toFixed(0) + 'K';
                return 'Rp ' + v;
            };

            function buildConfig(period) {
                const raw = salesChartData[period];
                const labels = Object.keys(raw);
                const data = Object.values(raw);

                return {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: `Last ${period} Days`,
                            data,
                            borderColor: '#741D29',
                            backgroundColor: 'rgba(116, 29, 41, 0.1)',
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
                                    label: ctx => `${ctx.dataset.label}: ${formatCurrency(ctx.raw)}`
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
                                    callback: formatCurrency,
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

            // initialize with last 7 days
            let chart = new Chart(ctx, buildConfig('7'));

            // switch on dropdown
            select.addEventListener('change', () => {
                chart.destroy();
                chart = new Chart(ctx, buildConfig(select.value));
            });
        });


        // Toggle Sidebar
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        const toggleSidebar = document.getElementById('toggle-sidebar');
        const sidebarToggleIcon = document.querySelector('.sidebar-toggle-icon');
        const sidebarToggleText = document.querySelector('.sidebar-toggle-text');
        const sidebarLinkTexts = document.querySelectorAll('.sidebar-link-text');
        const sidebarTitle = document.querySelector('.sidebar-title');

        toggleSidebar.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.toggle('collapsed');

            if (sidebar.classList.contains('collapsed')) {
                sidebarToggleIcon.classList.remove('fa-chevron-left');
                sidebarToggleIcon.classList.add('fa-chevron-right');
                sidebarToggleText.style.display = 'none';
                sidebarLinkTexts.forEach(text => text.style.display = 'none');
                sidebarTitle.style.display = 'none';
            } else {
                sidebarToggleIcon.classList.remove('fa-chevron-right');
                sidebarToggleIcon.classList.add('fa-chevron-left');
                sidebarToggleText.style.display = 'block';
                sidebarLinkTexts.forEach(text => text.style.display = 'block');
                sidebarTitle.style.display = 'block';
            }
        });

        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');

        mobileMenuButton.addEventListener('click', function() {
            sidebar.classList.toggle('show-mobile');
        });

        // Page Navigation
        const navLinks = document.querySelectorAll('.nav-link');
        const pages = document.querySelectorAll('.page');
        const pageTitle = document.getElementById('page-title');

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const targetPage = this.getAttribute('data-page');

                // Update active link
                navLinks.forEach(navLink => {
                    navLink.classList.remove('bg-mocha-burgundy', 'bg-opacity-50');
                    navLink.classList.add('hover:bg-mocha-burgundy',
                        'hover:bg-opacity-30');
                });
                this.classList.add('bg-mocha-burgundy', 'bg-opacity-50');
                this.classList.remove('hover:bg-mocha-burgundy', 'hover:bg-opacity-30');

                // Show target page
                pages.forEach(page => {
                    page.classList.add('hidden');
                });
                document.getElementById(`${targetPage}-page`).classList.remove('hidden');

                // Update page title
                pageTitle.textContent = this.querySelector('.sidebar-link-text').textContent;
            });
        });

        // File Upload Buttons
        const browseButtons = document.querySelectorAll('.browse-btn');

        browseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const fileInput = this.parentElement.querySelector('input[type="file"]');
                fileInput.click();
            });
        });

        document.getElementById('upload-profile-pic').addEventListener('click', function() {
            document.getElementById('profile-image').click();
        });

        // Modal Handlers
        const modals = document.querySelectorAll('.modal');
        const closeModalButtons = document.querySelectorAll('.close-modal');

        // Open modal functions
        const addFlowerBtn = document.querySelector('.add-flower-btn');
        const addPackagingBtn = document.querySelector('.add-packaging-btn');
        const addDiscountBtn = document.querySelector('.add-discount-btn');
        const viewOrderBtns = document.querySelectorAll('.view-order-btn');
        const viewSuggestionBtns = document.querySelectorAll('.view-suggestion-btn');

        if (addFlowerBtn) {
            addFlowerBtn.addEventListener('click', function() {
                document.getElementById('add-flower-modal').classList.remove('hidden');
                document.getElementById('add-flower-modal').classList.add('flex');
            });
        }

        if (addPackagingBtn) {
            addPackagingBtn.addEventListener('click', function() {
                document.getElementById('add-packaging-modal').classList.remove('hidden');
                document.getElementById('add-packaging-modal').classList.add('flex');
            });
        }

        if (addDiscountBtn) {
            addDiscountBtn.addEventListener('click', function() {
                document.getElementById('discount-modal').classList.remove('hidden');
                document.getElementById('discount-modal').classList.add('flex');
            });
        }

        viewOrderBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('order-detail-modal').classList.remove('hidden');
                document.getElementById('order-detail-modal').classList.add('flex');
            });
        });

        viewSuggestionBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('view-suggestion-modal').classList.remove('hidden');
                document.getElementById('view-suggestion-modal').classList.add('flex');
            });
        });

        // Close modals
        closeModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            });
        });

        // Close modal when clicking outside
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }
            });
        });

        // Form Submissions with Toast Notification
        const forms = document.querySelectorAll('form');
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Close any open modals
                modals.forEach(modal => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                });

                // Show toast notification
                toastMessage.textContent = 'Changes saved successfully!';
                toast.classList.remove('hidden');
                toast.classList.add('flex');

                // Auto hide toast after 3 seconds
                setTimeout(function() {
                    toast.classList.add('hidden');
                    toast.classList.remove('flex');
                }, 3000);
            });
        });

        // Add delete confirmation for delete buttons
        const deleteButtons = document.querySelectorAll(
            '.delete-flower-btn, .delete-packaging-btn, .delete-discount-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-id');
                const itemType = this.classList.contains('delete-flower-btn') ? 'flower' :
                    this.classList.contains('delete-packaging-btn') ? 'packaging' : 'discount';

                if (confirm(`Are you sure you want to delete this ${itemType}?`)) {
                    // Show toast notification
                    toastMessage.textContent =
                        `${itemType.charAt(0).toUpperCase() + itemType.slice(1)} deleted successfully!`;
                    toast.classList.remove('hidden');
                    toast.classList.add('flex');

                    // Auto hide toast after 3 seconds
                    setTimeout(function() {
                        toast.classList.add('hidden');
                        toast.classList.remove('flex');
                    }, 3000);
                }
            });
        });

        // Initialize all edit buttons to show appropriate modals and pre-fill data
        const editFlowerBtns = document.querySelectorAll('.edit-flower-btn');
        const editPackagingBtns = document.querySelectorAll('.edit-packaging-btn');
        const editDiscountBtns = document.querySelectorAll('.edit-discount-btn');

        editFlowerBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = document.getElementById('add-flower-modal');
                modal.querySelector('h3').textContent = 'Edit Flower';
                modal.querySelector('button[type="submit"]').textContent = 'Save Changes';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                // Here you would pre-fill form data based on the flower ID
            });
        });

        editPackagingBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = document.getElementById('add-packaging-modal');
                modal.querySelector('h3').textContent = 'Edit Packaging';
                modal.querySelector('button[type="submit"]').textContent = 'Save Changes';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                // Here you would pre-fill form data based on the packaging ID
            });
        });

        editDiscountBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = document.getElementById('discount-modal');
            modal.querySelector('h3').textContent = 'Edit Discount';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Here you would pre-fill form data based on the discount code
        });
        });
    </script>
</body>
