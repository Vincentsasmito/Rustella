<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // badges in header
        $cartCount       = array_sum(session('cart', []));
        $suggestionCount = Suggestion::where('user_id', $user->id)->count();

        // build the base query, eager-load exactly what we need
        $q = Order::with([
            'orderProducts.product',  // your line-items
            'discount',               // via discount_id
            'delivery'                // via deliveries_id
        ])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // filter by status dropdown
        if ($request->filled('progress') && $request->progress !== 'all') {
            $q->where('progress', $request->progress);
        }

        // paginate (and keep your filters in the query string)
        $orders     = $q->paginate(5)->withQueryString();
        $orderCount = $orders->total();

        // compute money-fields on each order and build items array
        $orders->getCollection()->transform(function ($order) {
            // 1) subtotal
            $subtotal = $order->orderProducts->sum('price');

            // 2) discount amount (if any)
            $discountAmount = 0;
            if ($order->discount && $subtotal >= $order->discount->min_purchase) {
                $rawDisc = $subtotal * ($order->discount->percent / 100);
                $discountAmount = $order->discount->max_value
                    ? min($rawDisc, $order->discount->max_value)
                    : $rawDisc;
            }

            // 3) delivery fee
            $deliveryFee = optional($order->delivery)->fee ?? 0;

            // 4) grand total
            $grandTotal = $subtotal - $discountAmount + $deliveryFee;

            // attach for Blade & JS
            $order->subtotal        = $subtotal;
            $order->discount_amount = $discountAmount;
            $order->delivery_fee    = $deliveryFee;
            $order->grand_total     = $grandTotal;

            // build items for the modal JSON
            $order->items_json = $order->orderProducts->map(function ($op) {
                return [
                    'name'       => $op->product->name,
                    'unit_price' => $op->price / $op->quantity,
                    'quantity'   => $op->quantity,
                    'total'      => $op->price,
                    'image'      => asset('images/' . $op->product->image_url),
                ];
            })->values()->toArray();

            return $order;
        });

        return view('customerviews.userprofile', compact(
            'user',
            'orders',
            'orderCount',
            'cartCount',
            'suggestionCount'
        ));
    }




    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->name = $data['name'];
        $user->save();  // IDE will now know save() exists

        return back()->with('status', 'Name successfully updated.');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 1) Make sure they actually know their current password
        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        // 2) Reject if the “new” password matches the old
        if (Hash::check($data['password'], $user->password)) {
            return back()->withErrors([
                'password' => ['Your new password must be different from the current one.'],
            ]);
        }

        // 3) Everything OK? Hash & save
        $user->password = Hash::make($data['password']);
        $user->save();

        return back()->with('status', 'Password successfully changed.');
    }

    public function updatePayment(Request $request, Order $order)
    {
        // Quick ownership guard:
        if ($order->user_id !== auth()->id()) {
            abort(403, 'You’re not allowed to modify this order.');
        }

        $data = $request->validate([
            'photo' => 'required|mimes:jpg,bmp,png,jpeg',
        ]);

        // store file…
        $fileName = date('YmdHis') . '-' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->move(public_path('payment'), $fileName);

        // persist to payment_url
        $order->payment_url = $fileName;
        $order->save();

        return back()->with('status', 'Payment screenshot uploaded successfully.');
    }
}
