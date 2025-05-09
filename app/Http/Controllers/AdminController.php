<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


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
        $ordersThisMonth = Order::with(['orderProducts', 'discount'])
            ->whereBetween('created_at', [$thisStart, $thisEnd])
            ->get();

        $ordersLastMonth = Order::with(['orderProducts', 'discount'])
            ->whereBetween('created_at', [$lastStart, $lastEnd])
            ->get();

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

                //Net = subtotal minus discount
                return $carry + ($subtotal - $discAmt);
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

        //__Products Sold Section__
        //Sum quantities of all orderProducts this month
        $productsThis = $ordersThisMonth
            // for each order, sum its items’ quantities
            ->sum(fn($order) => $order->orderProducts->sum('quantity'));

        //Same for last month
        $productsLast = $ordersLastMonth
            ->sum(fn($order) => $order->orderProducts->sum('quantity'));

        //Compute products % change
        if ($productsLast > 0) {
            $prodChange = round((($productsThis - $productsLast) / $productsLast) * 100, 1);
        } else {
            $prodChange = $productsThis > 0 ? 100 : 0;
        }

        //__Sales Chart Data Section__
        $salesData = [
            '7'  => $this->getSalesDataForPeriod($now->copy()->subDays(6), $now),
            '30' => $this->getSalesDataForPeriod($now->copy()->subDays(29), $now),
            '90' => $this->getSalesDataForPeriod($now->copy()->subDays(89), $now),
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

            //products data
            'totalProducts'     => $productsThis,
            'productsChange'    => abs($prodChange),
            'productsUp'        => ($prodChange >= 0),

            //sales chart data
            'salesChartData' => $salesData,
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
            ])
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
            $dailyTotals[$key] += ($subtotal - $discount);
        }

        // 5. Return a plain PHP array keyed by date
        return $dailyTotals;
    }
}
