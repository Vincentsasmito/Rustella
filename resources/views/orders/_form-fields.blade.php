{{-- Shared fields for create & edit --}}

<div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Sender Email</label>
      <input type="email" name="sender_email"
        value="{{ old('sender_email', $order->sender_email ?? '') }}"
        class="form-control" required>
    </div>
  
    <div class="col-md-6">
      <label class="form-label">Sender Phone</label>
      <input type="text" name="sender_phone"
        value="{{ old('sender_phone', $order->sender_phone ?? '') }}"
        class="form-control" required>
    </div>
  
    <div class="col-12">
      <label class="form-label">Sender Note</label>
      <textarea name="sender_note" class="form-control">{{ old('sender_note', $order->sender_note ?? '') }}</textarea>
    </div>
  
    <div class="col-md-6">
      <label class="form-label">Recipient Name</label>
      <input type="text" name="recipient_name"
        value="{{ old('recipient_name', $order->recipient_name ?? '') }}"
        class="form-control" required>
    </div>
  
    <div class="col-md-6">
      <label class="form-label">Recipient Phone</label>
      <input type="text" name="recipient_phone"
        value="{{ old('recipient_phone', $order->recipient_phone ?? '') }}"
        class="form-control" required>
    </div>
  
    <div class="col-12">
      <label class="form-label">Recipient Address</label>
      <input type="text" name="recipient_address"
        value="{{ old('recipient_address', $order->recipient_address ?? '') }}"
        class="form-control" required>
    </div>
  
    <div class="col-md-6">
      <label class="form-label">Recipient City</label>
      <input type="text" name="recipient_city"
        value="{{ old('recipient_city', $order->recipient_city ?? '') }}"
        class="form-control" required>
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
      <input type="text" name="progress"
        value="{{ old('progress', $order->progress ?? '') }}"
        class="form-control" required>
    </div>
  
    <div class="col-md-6">
      <label class="form-label">Discount</label>
      <select name="discount_id" class="form-select">
        <option value="">No Discount</option>
        @foreach($discounts as $d)
          <option value="{{ $d->id }}"
            {{ old('discount_id', $order->discount_id ?? '') == $d->id ? 'selected' : '' }}>
            {{ $d->code }} ({{ $d->percent }}%)
          </option>
        @endforeach
      </select>
    </div>
  </div>
  