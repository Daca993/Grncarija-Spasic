@extends('layouts.app')

@section('content')
<div class="py-8 w-full">
    <div class="rustic-card kafana-panel rounded-4 p-6 bg-etno-beige/80 w-full overflow-x-auto">
    <h1 class="text-2xl font-semibold mb-6 text-etno-dark">Order #{{ $order->order_number }}</h1>
    @if(session('success'))<p class="text-green-700 mb-4">{{ session('success') }}</p>@endif
    <div class="bg-white/90 border border-etno-brown/20 rounded-4 p-6 space-y-2 mb-6">
        <p><strong>{{ __('messages.admin_customer') }}:</strong> {{ $order->customer_name }}</p>
        <p><strong>{{ __('messages.Customer email') }}:</strong> {{ $order->customer_email }}</p>
        @if($order->customer_phone)<p><strong>{{ __('messages.Customer phone') }}:</strong> {{ $order->customer_phone }}</p>@endif
        @if($order->customer_address)<p><strong>{{ __('messages.Address') }}:</strong> {{ $order->customer_address }}</p>@endif
        @if($order->notes)<p><strong>{{ __('messages.Notes') }}:</strong> {{ $order->notes }}</p>@endif
        <p><strong>{{ __('messages.Total') }}:</strong> {{ number_format($order->total, 0) }} {{ $order->currency }}</p>
        <p><strong>{{ __('messages.admin_status') }}:</strong> {{ $order->status }}</p>
    </div>
    <div class="flex flex-wrap gap-2 mb-6 items-center">
        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex gap-2">
            @csrf
            @method('PATCH')
            <select name="status" class="border border-etno-brown/30 rounded-4 px-3 py-2 bg-white text-etno-dark">
                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>pending</option>
                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>confirmed</option>
                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>shipped</option>
                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>cancelled</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-etno-red text-white rounded-4 hover:opacity-90 transition">{{ __('messages.admin_update_status') }}</button>
        </form>
        <a href="{{ route('admin.orders.downloadCsv', $order) }}" class="px-4 py-2 bg-etno-brown text-white rounded-4 hover:opacity-90 transition inline-block">{{ __('messages.admin_download_csv') }}</a>
    </div>
    <table class="w-full min-w-full table-auto bg-white/90 border border-etno-brown/20 rounded-4 overflow-hidden">
        <thead><tr class="border-b border-etno-brown/30 bg-etno-brown/10"><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.Product name') }}</th><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.Size') }}</th><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.Price') }}</th><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.Quantity') }}</th><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.Subtotal') }}</th></tr></thead>
        <tbody>
            @foreach($order->items as $item)
                <tr class="border-b border-etno-brown/10">
                    <td class="px-4 py-2 text-etno-dark">{{ $item->product_name }}</td>
                    <td class="px-4 py-2 text-etno-brown">{{ $item->size_label_with_cm }}</td>
                    <td class="px-4 py-2 text-etno-brown">{{ number_format($item->price, 0) }} {{ $order->currency }}</td>
                    <td class="px-4 py-2 text-etno-brown">{{ $item->quantity }}</td>
                    <td class="px-4 py-2 text-etno-brown">{{ number_format($item->subtotal, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="mt-4"><a href="{{ route('admin.orders.index') }}" class="text-etno-terracotta hover:text-etno-red">← {{ __('messages.Orders') }}</a></p>
    </div>
</div>
@endsection
