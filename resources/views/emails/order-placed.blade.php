<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nova porudžbina #{{ $order->order_number }}</title>
</head>
<body style="font-family: sans-serif; line-height: 1.5;">
    <h1>Nova porudžbina #{{ $order->order_number }}</h1>
    <p><strong>Kupac:</strong> {{ $order->customer_name }}</p>
    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
    @if($order->customer_phone)<p><strong>Telefon:</strong> {{ $order->customer_phone }}</p>@endif
    @if($order->customer_address)<p><strong>Adresa:</strong> {{ $order->customer_address }}</p>@endif
    @if($order->notes)<p><strong>Napomena:</strong> {{ $order->notes }}</p>@endif
    <p><strong>Ukupno:</strong> {{ number_format($order->total, 2) }} {{ $order->currency }}</p>
    <p>Pregled stavki je u prilogu (.xlsx).</p>
</body>
</html>
