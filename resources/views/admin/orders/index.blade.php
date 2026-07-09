@extends('layouts.app')

@section('content')
<div class="py-4 md:py-8 w-full">
    <div class="rustic-card kafana-panel rounded-4 p-4 md:p-6 bg-etno-beige/80 w-full max-w-full">
        <h1 class="text-xl md:text-2xl font-semibold mb-4 md:mb-6 text-etno-dark">{{ __('messages.Orders') }}</h1>
        @if(session('success'))<p class="text-green-700 mb-4 text-sm md:text-base">{{ session('success') }}</p>@endif
        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px] table-auto bg-white/90 border border-etno-brown/20 rounded-4 overflow-hidden text-base md:text-lg">
                <thead>
                    <tr class="border-b border-etno-brown/30 bg-etno-brown/10">
                        <th class="px-4 py-3 text-left text-etno-dark whitespace-nowrap">#</th>
                        <th class="px-4 py-3 text-left text-etno-dark">{{ __('messages.admin_customer') }}</th>
                        <th class="px-4 py-3 text-left text-etno-dark whitespace-nowrap">{{ __('messages.Total') }}</th>
                        <th class="px-4 py-3 text-left text-etno-dark whitespace-nowrap">{{ __('messages.admin_status') }}</th>
                        <th class="px-4 py-3 text-left text-etno-dark whitespace-nowrap">{{ __('messages.admin_date') }}</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b border-etno-brown/10">
                            <td class="px-4 py-3 text-etno-brown whitespace-nowrap">{{ $order->order_number }}</td>
                            <td class="px-4 py-3 text-etno-dark max-w-[220px] lg:max-w-none truncate lg:overflow-visible lg:whitespace-normal" title="{{ $order->customer_name }} ({{ $order->customer_email }})">{{ $order->customer_name }} <span class="hidden lg:inline">({{ $order->customer_email }})</span></td>
                            <td class="px-4 py-3 text-etno-brown whitespace-nowrap">{{ number_format($order->total, 0) }} {{ $order->currency }}</td>
                            <td class="px-4 py-3 text-etno-brown whitespace-nowrap">{{ $order->status }}</td>
                            <td class="px-4 py-3 text-etno-brown whitespace-nowrap">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-etno-terracotta hover:text-etno-red mr-2 md:mr-3">{{ __('messages.admin_view') }}</a>
                                <a href="{{ route('admin.orders.downloadCsv', $order) }}" class="text-etno-brown hover:text-etno-terracotta">{{ __('messages.admin_export') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 overflow-x-auto">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
