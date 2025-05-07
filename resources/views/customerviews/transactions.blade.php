<h1>Your Transactions</h1>
@foreach ($orders as $order)
    <div>
        <strong>Order #{{ $order->id }}</strong> <br>
        <strong>Created At:</strong> {{ $order->created_at }} <br>
        <strong>Sender Email:</strong> {{ $order->sender_email }} <br>
        <strong>Sender Phone:</strong> {{ $order->sender_phone }} <br>
        <strong>Recipient Name:</strong> {{ $order->recipient_name }} <br>
        <strong>Recipient Address:</strong> {{ $order->recipient_address }} <br>
        <strong>Recipient City:</strong> {{ $order->recipient_city }} <br>
        <strong>Delivery Time:</strong> {{ $order->delivery_time }} <br>
        <strong>Progress:</strong> {{ $order->progress }} <br>
        <strong>Order Total:</strong> Rp {{ number_format($totals[$order->id], 0, ',', '.') }} <br> <!-- Displaying the total calculated in the controller -->
        <br><br>
    </div>
@endforeach
