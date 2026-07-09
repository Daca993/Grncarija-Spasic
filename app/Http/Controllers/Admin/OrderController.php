<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with('items')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function downloadCsv(Order $order): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $order->load('items');

        $filename = 'porudzbina-' . $order->order_number . '.csv';

        return Response::streamDownload(function () use ($order) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM za Excel
            $sep = ';';

            // Blok 1: podaci o porudžbini – jedna kolona oznaka, jedna kolona vrednost
            fputcsv($out, [__('messages.admin_order_number'), $order->order_number], $sep);
            fputcsv($out, [__('messages.admin_customer'), $order->customer_name], $sep);
            fputcsv($out, [__('messages.Customer email'), $order->customer_email], $sep);
            fputcsv($out, [__('messages.Customer phone'), $order->customer_phone ?? ''], $sep);
            fputcsv($out, [__('messages.Address'), $order->customer_address ?? ''], $sep);
            fputcsv($out, [__('messages.Notes'), $order->notes ?? ''], $sep);
            fputcsv($out, [__('messages.admin_status'), $order->status], $sep);
            fputcsv($out, [__('messages.admin_date'), $order->created_at->format('d.m.Y H:i')], $sep);
            fputcsv($out, [], $sep);

            // Blok 2: stavke – jedna kolona po podatku
            fputcsv($out, [
                __('messages.Product name'),
                'Veličina',
                __('messages.Price'),
                __('messages.Quantity'),
                __('messages.Subtotal'),
            ], $sep);

            foreach ($order->items as $item) {
                $sizeStr = $item->size ? $item->size . ($item->size_cm ? ' ' . $item->size_cm : '') : '';
                fputcsv($out, [
                    $item->product_name,
                    $sizeStr,
                    number_format($item->price, 2, ',', ''),
                    $item->quantity,
                    number_format($item->subtotal, 2, ',', ''),
                ], $sep);
            }

            fputcsv($out, [], $sep);
            fputcsv($out, [
                __('messages.Total'),
                '',
                '',
                '',
                number_format($order->total, 2, ',', '') . ' ' . $order->currency,
            ], $sep);

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function updateStatus(Request $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['status' => 'required|in:pending,confirmed,shipped,cancelled']);
        $order->update(['status' => $request->status]);
        return back()->with('success', __('messages.admin_status_updated'));
    }
}
