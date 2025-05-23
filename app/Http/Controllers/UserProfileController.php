<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Suggestion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // badges in header
        $cartCount       = array_sum(session('cart', []));
        $suggestionCount = Suggestion::where('user_id', $user->id)
            ->where('type', 'product')
            ->count();

        // build & paginate
        $q = Order::with(['orderProducts.product', 'discount', 'delivery', 'user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($request->filled('progress') && $request->progress !== 'all') {
            $q->where('progress', $request->progress);
        }

        $orders     = $q->paginate(5)->withQueryString();
        $orderCount = $orders->total();

        // compute money‐fields & items_json
        $orders->getCollection()->transform(function ($order) {
            $subtotal = $order->orderProducts->sum('price');

            $discountAmount = 0;
            if ($order->discount && $subtotal >= $order->discount->min_purchase) {
                $rawDisc = $subtotal * ($order->discount->percent / 100);
                $discountAmount = $order->discount->max_value
                    ? min($rawDisc, $order->discount->max_value)
                    : $rawDisc;
            }

            $deliveryFee = $order->delivery_fee ?? 0;
            $grandTotal  = $subtotal - $discountAmount + $deliveryFee;

            // Attach for Blade display
            $order->subtotal        = $subtotal;
            $order->discount_amount = $discountAmount;
            $order->delivery_fee    = $deliveryFee;
            $order->grand_total     = $grandTotal;

            // fetch all product‐type suggestions for this order, keyed by product_id
            $suggestionsByProduct = Suggestion::where('order_id', $order->id)
                ->where('type', 'product')
                ->get()
                ->keyBy('product_id');

            // (3) build items_json merging in any review
            $order->items_json = $order->orderProducts->map(function ($op) use ($suggestionsByProduct) {
                $s = $suggestionsByProduct->get($op->product_id);

                return [
                    'productId' => $op->product_id,
                    'name'      => $op->product->name,
                    'image'     => asset('images/' . $op->product->image_url),
                    'unitPrice' => $op->price / $op->quantity,
                    'quantity'  => $op->quantity,
                    'total'     => $op->price,
                    'review'    => $s ? [
                        'rating'   => $s->rating,
                        'message'  => $s->message,
                        'date'     => $s->created_at->format('F j, Y'),
                    ] : null,
                ];
            })->values()->toArray();

            return $order;
        });


        // Build a pure PHP array for JS consumption
        $ordersForJs = $orders->getCollection()->map(function ($order) {
            return [
                'id'            => $order->id,
                'date'          => $order->created_at->format('F j, Y'),

                // 1) deliveryTime straight from the column:
                'deliveryTime' => $order->delivery_time
                    ? Carbon::parse($order->delivery_time)->format('F j, Y H:i')
                    : null,

                'status'        => $order->progress,

                // 2) build location from the related delivery model:
                'location'      => optional($order->delivery)->city
                    ? optional($order->delivery)->city . ', ' . optional($order->delivery)->subdistrict
                    : null,

                'recipientName'    => $order->recipient_name,
                'senderEmail'      => $order->user->email,
                'recipientPhone'   => $order->recipient_phone,
                'recipientAddress' => $order->recipient_address,
                'note'             => $order->note ?? '',

                // 3) items: unitPrice = price/quantity
                'items'         => collect($order->items_json)->map(function ($it) {
                    return [
                        'productId' => $it['productId'],
                        'image'     => $it['image'],
                        'name'      => $it['name'],
                        'unitPrice' => $it['total'] / $it['quantity'],
                        'quantity'  => $it['quantity'],
                        'total'     => $it['total'],
                        'review'    => $it['review'] ?? null,
                    ];
                })->toArray(),
                'hasReview'  => collect($order->items_json)->every(fn($it) => $it['review'] !== null),
                'subtotal'      => $order->subtotal,
                'discount'      => $order->discount_amount,
                'deliveryFee'   => $order->delivery_fee,
                'grandTotal'    => $order->grand_total,
            ];
        })->toArray();

        return view('customerviews.userprofile', compact(
            'user',
            'orders',
            'orderCount',
            'cartCount',
            'suggestionCount',
            'ordersForJs'
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

    public function getReview(Order $order)
    {
        // 1) Load the order’s items (order_products) + the product itself
        $items = $order->orderProducts()->with('product')->get();

        // 2) Load all product‐type suggestions on that order, keyed by product_id
        $suggestions = Suggestion::with('user')
            ->where('order_id', $order->id)
            ->where('type', 'product')
            ->get()
            ->keyBy('product_id');

        // 3) Combine into one collection: each item + its review (if it exists)
        $reviewRows = $items->map(function ($item) use ($suggestions) {
            $s = $suggestions->get($item->product_id);

            return [
                'product'    => $item->product,                      // id, name, image, etc.
                'quantity'   => $item->quantity,
                'unit_price' => $item->price,
                'total'      => $item->price * $item->quantity,
                'review'     => $s ? [
                    'id'         => $s->id,
                    'rating'     => $s->rating,
                    'message'    => $s->message,
                    'created_at' => $s->created_at,
                    'reviewer'   => $s->user->only('id', 'name'),
                ] : null,
            ];
        });
    }

    public function storeReviews(Request $request)
    {

        // 1) Validate the arrays
        $validated = $request->validate([
            'order_id'      => 'required|exists:orders,id',
            'product_id'    => 'required|array',
            'product_id.*'  => 'required|exists:products,id',
            'rating'        => 'required|array',
            'rating.*'      => 'required|integer|min:1|max:5',
            'message'       => 'required|array',
            'message.*'     => 'required|string|max:2000',
        ]);

        $userId  = Auth::id();
        $orderId = $validated['order_id'];

        // 2) Save all reviews in a single transaction
        $created = DB::transaction(function () use ($validated, $userId, $orderId) {
            $rows = [];
            foreach ($validated['product_id'] as $i => $productId) {
                $rows[] = Suggestion::create([
                    'user_id'    => $userId,
                    'order_id'   => $orderId,
                    'product_id' => $productId,
                    'type'       => 'product',
                    'rating'     => $validated['rating'][$i],
                    'message'    => $validated['message'][$i],
                ]);
            }
            return $rows;
        });

        // 3) Return all created suggestions
        return response()->json([
            'success'     => true,
            'suggestions' => $created,
        ], 201);
    }
}
