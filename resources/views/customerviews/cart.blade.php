@extends('layouts.app')

@section('content')
    <div class="container-fluid px-5 my-5">
        {{-- ✅ Show error or success messages --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- … header … --}}

        @if ($cart && count($cart))
            {{-- ✅ START FORM: Add opening form tag --}}
            <form method="POST" action="{{ route('orders.store') }}">
                @csrf

                <div class="card shadow-sm p-4">
                    {{-- 1) Cart Table --}}
                    <div class="table-responsive mb-5" style="…">
                        <table class="table …">
                            <thead>…</thead>
                            <tbody>
                                @foreach ($cart as $item)
                                    @php
                                        $price = $item['product']->price;
                                        $quantity = $item['quantity'];
                                    @endphp
                                    <tr>
                                        <td>{{ $item['product']->name }}</td>
                                        <td>IDR {{ number_format($price, 0) }}</td>
                                        <td>
                                            <input type="number" name="quantity" value="{{ $quantity }}" min="1"
                                                data-price="{{ $price }}"
                                                class="form-control form-control-sm quantity-input" style="width: 70px;">
                                        </td>
                                        <td class="row-subtotal">
                                            IDR {{ number_format($price * $quantity, 0) }}
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-sm remove-item">✕</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- 2) Discount & Summary --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Discount</label>
                            <select name="discount_id" class="form-select" id="discount-select">
                                <option value="">No Discount</option>
                                @foreach ($discounts as $d)
                                    <option value="{{ $d->id }}">
                                        {{ $d->code }} ({{ $d->percent }}%)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 offset-md-6 text-end">
                            <div class="d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <strong id="subtotal">IDR 0</strong>
                            </div>

                            <div id="discount-line" class="d-flex justify-content-between text-danger"
                                style="display: none;">
                                <span id="discount-label"></span>
                                <strong id="discount-amount"></strong>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <span><strong>Total:</strong></span>
                                <strong id="final-total">IDR 0</strong>
                            </div>

                            {{--  Make this a form submit button --}}
                            <button type="submit" class="btn btn-success mt-3 w-100">
                                Proceed to Checkout
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Sender Email</label>
                        <input type="email" name="sender_email"
                            value="{{ old('sender_email', $order->sender_email ?? '') }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sender Phone</label>
                        <input type="text" name="sender_phone"
                            value="{{ old('sender_phone', $order->sender_phone ?? '') }}" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Sender Note</label>
                        <textarea name="sender_note" class="form-control">{{ old('sender_note', $order->sender_note ?? '') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Recipient Name</label>
                        <input type="text" name="recipient_name"
                            value="{{ old('recipient_name', $order->recipient_name ?? '') }}" class="form-control"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Recipient Phone</label>
                        <input type="text" name="recipient_phone"
                            value="{{ old('recipient_phone', $order->recipient_phone ?? '') }}" class="form-control"
                            required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Recipient Address</label>
                        <input type="text" name="recipient_address"
                            value="{{ old('recipient_address', $order->recipient_address ?? '') }}" class="form-control"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Recipient City</label>
                        <input type="text" name="recipient_city"
                            value="{{ old('recipient_city', $order->recipient_city ?? '') }}" class="form-control"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Delivery Time</label>
                        <input type="datetime-local" name="delivery_time"
                            value="{{ old('delivery_time', isset($order) ? \Carbon\Carbon::parse($order->delivery_time)->format('Y-m-d\TH:i') : '') }}"
                            class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Delivery Details</label>
                        <textarea name="delivery_details" class="form-control">{{ old('delivery_details', $order->delivery_details ?? '') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Progress</label>
                        <input type="text" name="progress" value="{{ old('progress', $order->progress ?? '') }}"
                            class="form-control" required>
                    </div>
            </form> {{--  END FORM --}}
        @else
            {{-- empty cart --}}
        @endif

    </div>
@endsection

@php
    $discountsData = $discounts
        ->mapWithKeys(
            fn($d) => [
                $d->id => [
                    'percent' => $d->percent,
                    'min_purchase' => $d->min_purchase,
                    'max_value' => $d->max_value,
                ],
            ],
        )
        ->toArray();
@endphp

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // grab elements
            const discounts = @json($discountsData);
            const discountSelect = document.getElementById('discount-select');
            const subtotalEl = document.getElementById('subtotal');
            const discountLine = document.getElementById('discount-line');
            const discountLabel = document.getElementById('discount-label');
            const discountAmountEl = document.getElementById('discount-amount');
            const finalTotalEl = document.getElementById('final-total');
            const qtyInputs = document.querySelectorAll('.quantity-input');

            console.log('Discount system initializing…', {
                discounts,
                qtyInputs: qtyInputs.length
            });

            function applyDiscount(subtotalRaw) {
                const dId = discountSelect.value;
                const d = discounts[dId] || null;

                console.log('applyDiscount called', {
                    subtotalRaw,
                    selectedDiscount: d
                });

                let discountAmt = 0;
                if (d && subtotalRaw >= d.min_purchase) {
                    discountAmt = Math.min(subtotalRaw * (d.percent / 100), d.max_value);
                }

                console.log(' -> discountAmt =', discountAmt);

                if (discountAmt > 0) {
                    discountLine.style.setProperty('display', 'flex', 'important');
                    discountLabel.textContent = `Discount (${d.percent}%)`;
                    discountAmountEl.textContent = `- IDR ${discountAmt.toLocaleString()}`;
                } else {
                    discountLine.style.setProperty('display', 'none', 'important');
                }
                console.log(
                    'Discount details:',
                    'percent=', d?.percent,
                    'min_purchase=', d?.min_purchase,
                    'max_value=', d?.max_value
                );

                return subtotalRaw - discountAmt;
            }
            //Show discount
            function recalcTotals() {
                let subtotalRaw = 0;

                if (discountSelect.value == 0) {
                    document.getElementById('discount-line').style.display = 'none';
                }
                qtyInputs.forEach(input => {
                    const price = parseFloat(input.dataset.price) || 0;
                    const qty = parseInt(input.value) || 0;
                    const rowCell = input.closest('tr').querySelector('.row-subtotal');
                    const rowTotal = price * qty;
                    rowCell.textContent = `IDR ${rowTotal.toLocaleString()}`;
                    subtotalRaw += rowTotal;
                });

                console.log('recalcTotals: subtotalRaw=', subtotalRaw);
                subtotalEl.textContent = `IDR ${subtotalRaw.toLocaleString()}`;
                const totalAfter = applyDiscount(subtotalRaw);
                finalTotalEl.textContent = `IDR ${totalAfter.toLocaleString()}`;
            }

            // attach listeners
            qtyInputs.forEach(i => i.addEventListener('change', recalcTotals));
            discountSelect.addEventListener('change', recalcTotals);

            // initial run
            recalcTotals();
        });
    </script>
@endpush
