<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Delivery;
use App\Models\Discount;
use App\Models\Flower;
use App\Models\FlowerProduct;
use App\Models\Packaging;
use App\Models\StockTransaction;
use App\Models\Suggestion;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    //PROD
    public function index()
    {
        //__This Month Sales Section__
        //Date ranges
        $now       = Carbon::now('Asia/Jakarta');
        $thisStart = $now->copy()->startOfMonth();
        $thisEnd   = $now->copy()->endOfMonth();
        $lastStart = $thisStart->copy()->subMonth()->startOfMonth();
        $lastEnd   = $thisStart->copy()->subMonth()->endOfMonth();

        //Load orders with products AND discount
        $ordersThisMonth = Order::with(['orderProducts', 'discount', 'delivery'])
            ->whereBetween('created_at', [$thisStart, $thisEnd])->where('progress', '!=', 'Cancelled')
            ->get();

        $ordersLastMonth = Order::with(['orderProducts', 'discount', 'delivery'])
            ->whereBetween('created_at', [$lastStart, $lastEnd])->where('progress', '!=', 'Cancelled')
            ->get();
        //Load Deliveries

        //Function to compute net total for a collection of orders
        $computeNet = function ($orders) {
            return $orders->reduce(function ($carry, $order) {
                //Subtotal = sum(qty * price)
                $subtotal = $order->orderProducts
                    ->sum(fn($item) => $item->quantity * $item->price);

                //Default no discount
                $discAmt = 0;

                //If a discount exists AND subtotal meets the minimum
                if ($order->discount && $subtotal >= $order->discount->min_purchase) {
                    //Raw discount = order value * percent
                    $rawDisc = $subtotal * ($order->discount->percent / 100);

                    //Cap by max_value (if max_value is set)
                    $discAmt = $order->discount->max_value
                        ? min($rawDisc, $order->discount->max_value)
                        : $rawDisc;
                }

                // 3) Delivery fee (might be null)
                $fee = $order->delivery->fee ?? 0;

                //Net = subtotal minus discount
                return $carry + ($subtotal - $discAmt + $fee);
            }, 0);
        };

        //Compute totals
        $totalThis = $computeNet($ordersThisMonth);
        $totalLast = $computeNet($ordersLastMonth);

        //Percent change
        if ($totalLast > 0) {
            $change = round((($totalThis - $totalLast) / $totalLast) * 100, 1);
        } else {
            $change = $totalThis > 0 ? 100 : 0;
        }


        //__This Month Orders Section__
        //Count orders this month & last month
        $orderCountThis  = $ordersThisMonth->count();
        $orderCountLast  = $ordersLastMonth->count();

        //Compute orders % change
        if ($orderCountLast > 0) {
            $orderChange = round((($orderCountThis - $orderCountLast) / $orderCountLast) * 100, 1);
        } else {
            $orderChange = $orderCountThis > 0 ? 100 : 0;
        }

        //__New Customers Section__
        //Unique customers this month & last month
        $customersThis = $ordersThisMonth
            ->pluck('user_id')    // or 'customer_id' if you use a different FK
            ->unique()
            ->count();

        $customersLast = $ordersLastMonth
            ->pluck('user_id')
            ->unique()
            ->count();

        //Compute customers % change
        if ($customersLast > 0) {
            $custChange = round((($customersThis - $customersLast) / $customersLast) * 100, 1);
        } else {
            $custChange = $customersThis > 0 ? 100 : 0;
        }

        //── COGS Section ────────────────────────────────────────────────────────────
        // these are your Order::cost fields (assumes you’re storing recalc’d COGS per order)
        $cogsThis  = $ordersThisMonth->sum('cost');
        $cogsLast  = $ordersLastMonth->sum('cost');

        if ($cogsLast > 0) {
            $cogsChange = round((($cogsThis - $cogsLast) / $cogsLast) * 100, 1);
        } else {
            $cogsChange = $cogsThis > 0 ? 100 : 0;
        }

        //__Sales Chart Data Section__
        $salesData = [
            '7'  => $this->getSalesDataForPeriod($now->copy()->subDays(6), $now),
            '30' => $this->getSalesDataForPeriod($now->copy()->subDays(29), $now),
            '90' => $this->getSalesDataForPeriod($now->copy()->subDays(89), $now),
        ];

        //__Top 4 Best Seller Section__
        $bestSellers = Product::withCount([
            // Count only those orderProducts whose order falls in this month:
            'orderProducts as sold_qty' => function ($q) use ($thisStart, $thisEnd) {
                $q->whereHas('order', function ($q2) use ($thisStart, $thisEnd) {
                    $q2->whereBetween('created_at', [$thisStart, $thisEnd])->where('progress', '!=', 'Cancelled');
                });
            }
        ])
            ->orderByDesc('sold_qty')
            ->take(4)
            ->get(['id', 'name', 'price', 'sold_qty']);  // limit the selected columns

        //__5 Recent Orders Section__
        $recentOrders = Order::with([
            'user',
            'delivery',
            'discount',
            'orderProducts.product'
        ])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                // 1) subtotal = sum of already-net orderProducts.price
                $subtotal = $order->orderProducts
                    ->sum('price');

                // 2) discount
                $discountAmt = 0;
                if ($order->discount && $subtotal >= $order->discount->min_purchase) {
                    $rawDisc = $subtotal * ($order->discount->percent / 100);
                    $discountAmt = $order->discount->max_value
                        ? min($rawDisc, $order->discount->max_value)
                        : $rawDisc;
                }

                // 3) delivery fee
                $fee = $order->delivery_fee ?? 0;

                // 4) attach a 'sales' attribute to each order
                $order->sales = $subtotal - $discountAmt + $fee;

                return $order;
            });

        //__Flowers Subpage__
        $flowers = Flower::all();


        //__Orders Subpage__
        // Build the query
        $ordersQuery = Order::with([
            'user',
            'delivery',
            'discount',
            'orderProducts.product',
        ]);

        // Apply search and filter helpers
        $ordersQuery = $this->searchOrders($ordersQuery, request('order_search'));
        $ordersQuery = $this->filterOrders($ordersQuery, request('order_status'));

        // Paginate and transform
        $orders = $ordersQuery
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString()
            ->through(function ($order) {
                // calculate subtotal
                $subtotal = $order->orderProducts->sum('price');

                // calculate discount
                $discountAmt = 0;
                if ($order->discount && $subtotal >= $order->discount->min_purchase) {
                    $raw    = $subtotal * ($order->discount->percent / 100);
                    $discountAmt = $order->discount->max_value
                        ? min($raw, $order->discount->max_value)
                        : $raw;
                }

                // delivery fee
                $fee = $order->delivery_fee ?? 0;

                // attach your custom fields
                $order->subtotal        = $subtotal;
                $order->discount_amount = $discountAmt;
                $order->delivery_fee    = $fee;
                $order->grand_total     = $subtotal - $discountAmt + $fee;

                return $order;
            });
        //__Packagings Subpage__
        $packagings = Packaging::all();

        //__Discounts Subpage__
        $discounts = Discount::all();

        //__Suggestions Subpage__
        $siteSuggestions    = Suggestion::where('type', 'site')->latest()->get();
        $productSuggestions = Suggestion::where('type', 'product')->with(['user', 'product'])->latest()->get();

        //__Products Subpage__
        $products = Product::with([
            'packaging',
            'flowerProducts.flower'
        ])->get();

        // Calculate profit for this month and last month
        $totalProfitThis = $totalThis - $cogsThis;
        $totalProfitLast = $totalLast - $cogsLast;

        // Calculate profit change percentage
        if ($totalProfitLast != 0) {
            $profitChange = round((($totalProfitThis - $totalProfitLast) / abs($totalProfitLast)) * 100, 1);
        } else {
            $profitChange = $totalProfitThis > 0 ? 100 : 0;
        }
        $profitUp = $profitChange >= 0;

        //Stock Logs
        $stockLogsFO = StockTransaction::where('type', 'FO')->latest()->paginate(10, ['*'], 'fo_page');
        $stockLogsFI = StockTransaction::where('type', 'FI')->latest()->paginate(10, ['*'], 'fi_page');
        $stockLogsPO = StockTransaction::where('type', 'PO')->latest()->paginate(10, ['*'], 'po_page');
        $stockLogsPI = StockTransaction::where('type', 'PI')->latest()->paginate(10, ['*'], 'pi_page');

        //Deliveries Subpage
        $allDeliveries = Delivery::all();

        // Group by the `city` column
        $deliveriesBySection = $allDeliveries->groupBy('city');

        // data partitioning
        $sectionOrder = [
            'Jakarta Pusat',
            'Jakarta Barat',
            'Jakarta Selatan',
            'Jakarta Timur',
            'Jakarta Utara',
            'Kabupaten Tangerang',
            'Kota Tangerang',
            'Tangerang Selatan',
        ];

        return view('admin.index', [
            // sales data
            'totalSales'   => $totalThis,
            'salesChange'  => abs($change),
            'salesUp'      => ($change >= 0),

            // orders data
            'totalOrders'  => $orderCountThis,
            'ordersChange' => abs($orderChange),
            'ordersUp'     => ($orderChange >= 0),

            // customers data
            'totalCustomers'   => $customersThis,
            'customersChange'  => abs($custChange),
            'customersUp'      => ($custChange >= 0),

            //cogs data
            'totalCogs'     => $cogsThis,
            'cogsChange'    => abs($cogsChange),
            'cogsUp'        => ($cogsChange >= 0),

            //sales chart data
            'salesChartData' => $salesData,

            //best seller data
            'bestSellers'   => $bestSellers,
            'salesChartData' => $salesData,

            //recent orders data
            'recentOrders' => $recentOrders,

            //flowers subpage data
            'flowers' => $flowers,

            //orders subpage data
            'orders' => $orders,

            //packagings subpage data
            'packagings' => $packagings,

            //discounts subpage data
            'discounts' => $discounts,

            //suggestions subpage data
            'siteSuggestions' => $siteSuggestions,
            'productSuggestions' => $productSuggestions,

            //products subpage data
            'products' => $products,

            //profit data
            'totalProfit'   => $totalProfitThis,
            'profitChange'  => abs($profitChange),
            'profitUp'      => $profitUp,

            //stock logs
            'stockLogsFO' => $stockLogsFO,
            'stockLogsFI' => $stockLogsFI,
            'stockLogsPO' => $stockLogsPO,
            'stockLogsPI' => $stockLogsPI,

            //deliverys subpage data
            'deliveriesBySection' => $deliveriesBySection,
            'sectionOrder' => $sectionOrder,
        ]);
    }


    private function getSalesDataForPeriod(Carbon $startDate, Carbon $endDate): array
    {
        // 1. Build a list of every date between start...end
        $period = CarbonPeriod::create(
            $startDate->copy()->startOfDay(),
            '1 day',
            $endDate->copy()->startOfDay()
        );

        // 2. Initialize all days to zero
        $dailyTotals = [];
        foreach ($period as $day) {
            $dailyTotals[$day->format('Y-m-d')] = 0;
        }

        // 3. Fetch orders once
        $orders = Order::with(['orderProducts', 'discount'])
            ->whereBetween('created_at', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay()
            ])->where('progress', '!=', 'Cancelled')
            ->get();

        // 4. Accumulate each order into its day’s bucket
        foreach ($orders as $order) {
            $key = $order->created_at->format('Y-m-d');

            $subtotal = $order->orderProducts
                ->sum(fn($item) => $item->price);

            $discount = 0;
            if ($order->discount && $subtotal >= $order->discount->min_purchase) {
                $raw = $subtotal * ($order->discount->percent / 100);
                $discount = $order->discount->max_value < $raw
                    ? $order->discount->max_value
                    : $raw;
            }
            $fee = $order->delivery->fee ?? 0;
            $dailyTotals[$key] += ($subtotal - $discount + $fee);
        }

        // 5. Return a plain PHP array keyed by date
        return $dailyTotals;
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        // 0) Disallow any change if already cancelled
        if ($order->progress === 'Cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update a cancelled order.'
            ], 422);
        }

        // 1) Validate incoming status
        $data = $request->validate([
            'progress' => [
                'required',
                'in:Payment Pending,On Progress,Ready to Deliver,Delivery,Completed,Cancelled',
            ],
        ]);

        $newStatus = $data['progress'];

        DB::transaction(function () use ($order, $newStatus) {
            // 2) If we’re moving *to* “Cancelled”, reverse all stock transactions
            if ($newStatus === 'Cancelled') {
                $transactions = StockTransaction::where('order_id', $order->id)->get();
                foreach ($transactions as $tx) {
                    if ($tx->type === 'FO') {
                        // Flower refund
                        $flower = Flower::findOrFail($tx->flower_id);

                        $oldQty   = $flower->quantity;
                        $oldPrice = $flower->price;

                        $refundQty   = $tx->quantity;
                        $refundPrice = $tx->price;

                        // compute new quantity & weighted‐average price
                        $newQty   = $oldQty + $refundQty;
                        $newPrice = ($oldPrice * $oldQty + $refundPrice * $refundQty) / $newQty;

                        // write them back
                        $flower->quantity = $newQty;
                        $flower->price    = $newPrice;
                        $flower->save();

                        // log the reversal
                        StockTransaction::create([
                            'order_id'     => $order->id,
                            'flower_id'    => $flower->id,
                            'flower_name'  => $flower->name,
                            'type'         => 'FI',             // Flower In
                            'quantity'     => $refundQty,
                            'price'        => $refundPrice,
                        ]);
                    } elseif ($tx->type === 'PO') {
                        // Packaging refund
                        $packaging = Packaging::findOrFail($tx->packaging_id);


                        // log the reversal
                        StockTransaction::create([
                            'order_id'      => $order->id,
                            'packaging_id'  => $packaging->id,
                            'packaging_name' => $packaging->name,
                            'type'          => 'PI',          // Packaging In
                            'quantity'      => $tx->quantity,
                            'price'         => $tx->price,
                        ]);
                    }
                }
            }

            // 3) Update order status
            $order->progress = $newStatus;
            $order->save();
        });

        // 4) Return success JSON
        return response()->json([
            'success'    => true,
            'new_status' => $order->progress,
        ]);
    }



    //Flowers Subpage Handlers
    //Get All Flowers
    public function flowers()
    {
        $flowers = Flower::all();
        return view('admin.index', compact('flowers'));
    }
    // Store Flower
    public function storeFlower(Request $request)
    {
        try {
            $validInput = $request->validate([
                'name'     => 'required|string|max:100',
                'quantity' => 'required|numeric|min:1',
                'price'    => 'required|numeric|min:0',
            ]);
        } catch (ValidationException $e) {
            return redirect()->route('admin.index')->withFragment('#flowers')
                ->withErrors($e->validator);
        }

        $validInput['price'] = $request->price / $request->quantity;

        Flower::create($validInput);

        return redirect()->back()
            ->with('success', 'Flower created.')->withFragment('flowers');
    }
    // Update Flower
    public function updateFlower(Request $request, Flower $flower)
    {
        try {
            $validInput = $request->validate([
                'name'     => 'required|string|max:100',
                'quantity' => 'required|numeric|min:1',
                'price'    => 'required|numeric|min:0',
            ]);
        } catch (ValidationException $e) {
            return redirect()->route('admin.index')->withFragment('#flowers')
                ->withErrors($e->validator);
        }

        $flower->update($validInput);

        return redirect()->back()
            ->with('success', 'Flower updated.')->withFragment('flowers');
    }

    // Add Flower Stock
    public function stockUpdateFlower(Request $request, Flower $flower)
    {
        // 1. Validate inputs
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price'    => 'required|numeric|min:0',
        ]);

        $addedQty       = $data['quantity'];
        $addedTotalCost = $data['price'];

        // 2. Compute totals
        $currentQty   = $flower->quantity;
        $currentPrice = $flower->price;
        $currentTotalCost = $currentQty * $currentPrice;

        $newQty       = $currentQty + $addedQty;
        $newTotalCost = $currentTotalCost + $addedTotalCost;

        // 3. Weighted average price
        $newAvgPrice = $newTotalCost / $newQty;

        DB::transaction(function () use ($flower, $newQty, $newAvgPrice, $addedQty, $addedTotalCost) {
            // 1. Update the flower
            $flower->update([
                'quantity' => $newQty,
                'price'    => round($newAvgPrice),
            ]);

            // 2. Log the stock‐in
            StockTransaction::create([
                'order_id'     => null,
                'flower_id'    => $flower->id,
                'flower_name'  => $flower->name,
                'type'         => 'FI',             // Flower In
                'quantity'     => $addedQty,
                'price'        => round($addedTotalCost / $addedQty),
            ]);
        });

        // 5. Redirect back
        return redirect()
            ->route('admin.index')         // → GET /admin, runs AdminController@index
            ->with('success', 'Stock updated successfully!')
            ->withFragment('flowers');     // ← adds `#flowers` onto the URL
    }

    // Delete Flower
    public function destroyFlower(Flower $flower)
    {
        $flower->delete();

        return redirect()->back()
            ->with('success', 'Flower deleted successfully.')->withFragment('flowers');
    }

    //Packagings Subpage Helper
    //Get All Packagings
    public function packagings()
    {
        $packagings = Packaging::all();
        return view('admin.index', compact('packagings'));
    }
    // Store Packagings
    public function storePackaging(Request $request)
    {
        try {
            $validInput = $request->validate([
                'name'     => 'required|string|max:100',
                'price'    => 'required|numeric|min:0',
            ]);
        } catch (ValidationException $e) {
            // Redirect to discounts page with errors and old input
            return redirect()->route('admin.index')->withFragment('#packagings')
                ->withErrors($e->validator);
        }
        Packaging::create($validInput);

        return redirect()->back()
            ->with('success', 'Packaging created.')->withFragment('packagings');
    }
    // Update Packagings
    public function updatePackaging(Request $request, Packaging $packaging)
    {
        try {
            $validInput = $request->validate([
                'name'     => 'required|string|max:100',
                'price'    => 'required|numeric|min:0',
            ]);
        } catch (ValidationException $e) {
            // Redirect to discounts page with errors and old input
            return redirect()->route('admin.index')->withFragment('#packagings')
                ->withErrors($e->validator);
        }

        $packaging->update($validInput);

        return redirect()->back()
            ->with('success', 'Packaging updated.')->withFragment('packagings');
    }
    // Delete Packaging
    public function destroyPackaging(Packaging $packaging)
    {
        $packaging->delete();

        return redirect()->back()
            ->with('success', 'Packaging deleted successfully.')->withFragment('packagings');
    }

    //Discount Subpage Helper
    //Get All Discounts
    public function discounts()
    {
        $discounts = Discount::all();
        return view('admin.index', compact('discounts'));
    }
    // Store Discounts
    public function storeDiscount(Request $request)
    {
        try {
            $validInput = $request->validate([
                'code'           => 'required|string|max:20|unique:discounts,code',
                'percent'        => 'required|integer|between:0,100',
                'max_value'      => 'required|integer|min:0',
                'min_purchase'   => 'required|integer|min:0',
                'usage_limit'    => 'required|integer|min:0',
                'usage_counter'  => 'required|integer|min:0',
                'start_date'     => 'nullable|date',
                'end_date'       => 'nullable|date|after_or_equal:start_date',
            ]);
        } catch (ValidationException $e) {
            // Redirect to discounts page with errors and old input
            return redirect()->route('admin.index')->withFragment('#discounts')
                ->withErrors($e->validator);
        }

        try {
            Discount::create($validInput);
            return redirect()->route('admin.index', request()->query())
                ->with('success', 'Discount created.')->withFragment('discounts');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create Discount: ' . $e->getMessage())->withFragment('#discounts');
        }
    }
    // Update Discounts
    public function updateDiscount(Request $request, Discount $discount)
    {
        try {
            $validInput = $request->validate([
                'percent'        => 'required|integer|between:0,100',
                'max_value'      => 'required|integer|min:0',
                'min_purchase'   => 'required|integer|min:0',
                'usage_limit'    => 'required|integer|min:0',
                'usage_counter'  => 'required|integer|min:0',
                'start_date'     => 'nullable|date',
                'end_date'       => 'nullable|date|after_or_equal:start_date',
            ]);
        } catch (ValidationException $e) {
            // Redirect to discounts page with errors and old input
            return redirect()->route('admin.index')->withFragment('#discounts')
                ->withErrors($e->validator);
        }

        try {
            $discount->update($validInput);
            return redirect()->back()
                ->with('success', 'Discount updated.')->withFragment('discounts');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Discount: ' . $e->getMessage())->withFragment('#discounts');
        }
    }
    // Delete Packaging
    public function destroyDiscount(Discount $discount)
    {
        try {
            $discount->delete();
            return redirect()->back()
                ->with('success', 'Discount deleted successfully.')->withFragment('discounts');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete discount: ' . $e->getMessage())->withFragment('discounts');
        }
    }

    //Suggestions Helper Function
    //Delete Suggestion
    public function destroySuggestion(Suggestion $suggestion)
    {
        $suggestion->delete();
        return redirect()->back()->with('success', 'Suggestion deleted successfully.')->withFragment('suggestions');
    }

    //Products Helper Function
    //Server side button data rendering
    public function showProduct(Product $product)
    {
        return response()->json([
            'id'            => $product->id,
            'name'          => $product->name,
            'description'   => $product->description,
            'price'         => $product->price,
            'packaging_id'  => $product->packaging_id,
            'in_stock'      => (bool)$product->in_stock,
            'image_url'     => $product->image_url,
            'recipe' => $product->flowerProducts->map(function ($fp) {
                return [
                    'flower_id' => $fp->flower_id,
                    'flower_name' => $fp->flower->name,
                    'quantity'  => $fp->quantity,
                ];
            })->values(),
        ]);
    }

    //Store Product
    //Store Product
    public function storeProduct(Request $request)
    {
        try {
            $validInput = $request->validate([
                'name'        => 'required|string|max:100',
                'description' => 'nullable|string|max:1000',
                'price'       => 'required|numeric|min:0',
                'photo'       => 'mimes:jpg,bmp,png,jpeg',
                'packaging_id' => 'required|numeric',
                'flowers'    => 'array',                 // this will be an array of flower_id => "1"
                'quantities' => 'array',                 // matching flower_id => quantity

            ]);
        } catch (ValidationException $e) {
            return redirect()->route('admin.index')->withFragment('#products')
                ->withErrors($e->validator);
        }

        //save photo, get url
        if ($file = $request->file('photo')) {
            $file_path = public_path('images');
            $file_input = date('YmdHis') . '-' . $file->getClientOriginalName();
            $file->move($file_path, $file_input);
            $validInput['image_url'] = $file_input;
        }
        $validInput['in_stock'] = $request->has('in_stock');
        $product = Product::create($validInput);
        if ($request->has('flowers')) {
            foreach ($request->flowers as $flowerId => $checked) {
                if ($checked && isset($request->quantities[$flowerId])) {
                    FlowerProduct::create([
                        'product_id' => $product->id,
                        'flower_id'  => $flowerId,
                        'quantity'   => $request->quantities[$flowerId],
                    ]);
                }
            }
        }
        return redirect()->route('admin.index')
            ->with('success', 'Product created successfully.')->withFragment('products');
    }

    //Update Product
    public function updateProduct(Request $request, Product $product)
    {
        try {
            $validInput = $request->validate([
                'name'        => 'required|string|max:100',
                'description' => 'nullable|string|max:1000',
                'price'       => 'required|numeric|min:0',
                'photo'       => 'nullable|mimes:jpg,bmp,png,jpeg',
                'packaging_id' => 'required|integer',
                'flowers'    => 'array',                 // this will be an array of flower_id => "1"
                'quantities' => 'array',                 // matching flower_id => quantity

            ]);
        } catch (ValidationException $e) {
            return redirect()->route('admin.index')->withFragment('#products')
                ->withErrors($e->validator);
        }
        //save photo, get url
        if ($file = $request->file('photo')) {
            $file_path = public_path('images');
            $file_input = date('YmdHis') . '-' . $file->getClientOriginalName();
            $file->move($file_path, $file_input);
            $validInput['image_url'] = $file_input;
        }
        $validInput['in_stock'] = $request->has('in_stock');
        $product->update($validInput);

        //Remake FlowerProduct
        if ($request->has('flowers')) {
            //Delete existing FlowerProduct
            FlowerProduct::where('product_id', $product->id)->delete();
            foreach ($request->flowers as $flowerId => $checked) {
                if ($checked && isset($request->quantities[$flowerId])) {
                    FlowerProduct::create([
                        'product_id' => $product->id,
                        'flower_id'  => $flowerId,
                        'quantity'   => $request->quantities[$flowerId],
                    ]);
                }
            }
        }

        return redirect()->route('admin.index')
            ->with('success', 'Product updated successfully.')->withFragment('products');
    }

    //Delete Product
    public function destroyProduct(Product $product)
    {
        // 1) Build the full path to the image
        $imagePath = public_path('images/' . $product->image_url);

        // 2) If it exists, delete it
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.')->withFragment('products');
    }

    //Edit Delivery Fee
    public function updateDeliveryFee(Request $request, Delivery $delivery)
    {
        try {
            $validInput = $request->validate([
                'fee' => 'required|numeric|min:0',
            ]);
        } catch (ValidationException $e) {
            return redirect()->route('admin.index')->withFragment('deliveries')
                ->withErrors($e->validator);
        }

        $delivery->update($validInput);

        return redirect()->back()
            ->with('success', 'Delivery fee updated.')->withFragment('deliveries');
    }

    // Delete Delivery
    public function destroyDelivery(Delivery $delivery)
    {
        $delivery->delete();

        return redirect()->back()
            ->with('success', 'Delivery deleted successfully.')->withFragment('deliveries');
    }

    protected function searchOrders($query, $search)
    {
        if ($search) {
            $query->where('id', $search);
        }
        return $query;
    }

    protected function filterOrders($query, $status)
    {
        if ($status) {
            $query->where('progress', $status);
        }
        return $query;
    }

    /**
     * System health-check endpoint.
     */
    public function health()
    {
        try {
            $dbOk    = DB::connection()->getPdo() !== null;
            $queueOk = Queue::size('default') !== null;
            $mailOk  = true;

            $healthy = $dbOk && $queueOk && $mailOk;
        } catch (\Exception $e) {
            $healthy = false;
            $dbOk    = $dbOk ?? false;
            $queueOk = $queueOk ?? false;
            $mailOk  = $mailOk ?? false;
        }

        return response()->json([
            'healthy'   => $healthy,
            'checks'    => [
                'database' => $dbOk,
                'queue'    => $queueOk,
                'mailer'   => $mailOk,
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
