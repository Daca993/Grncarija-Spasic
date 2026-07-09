<?php

use App\Domain\Cart\Services\CartService;
use App\Domain\Order\Mail\OrderPlaced;
use App\Domain\Order\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;

new class extends Component
{
    public string $customer_name = '';
    public string $customer_email = '';
    public string $customer_phone = '';
    public string $customer_address = '';
    public string $notes = '';

    public function mount(): void
    {
        $this->customer_name = (string) auth()->user()?->name;
        $this->customer_email = (string) auth()->user()?->email;
        $this->customer_phone = (string) auth()->user()?->phone;
    }

    public function getItemsProperty()
    {
        return app(CartService::class)->items();
    }

    public function getTotalProperty(): float
    {
        return app(CartService::class)->total();
    }

    public function submit(): void
    {
        $this->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string|max:50',
            'customer_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = app(CartService::class);
        $items = $cart->items();
        if ($items->isEmpty()) {
            $this->redirect(route('cart.index'), navigate: true);
            return;
        }

        $minTotal = (int) config('app.wholesale_min_total', 70000);
        if (config('app.wholesale_only') && $cart->total() < $minTotal) {
            $this->addError('min_total', __('messages.wholesale_minimum_not_met', [
                'min' => number_format($minTotal, 0),
                'current' => number_format($cart->total(), 0),
            ]));
            session()->flash('info', __('messages.wholesale_minimum_not_met', [
                'min' => number_format($minTotal, 0),
                'current' => number_format($cart->total(), 0),
            ]));
            return;
        }

        $order = new Order;
        $order->order_number = Order::generateOrderNumber();
        $order->user_id = auth()->id();
        $order->customer_name = $this->customer_name;
        $order->customer_email = $this->customer_email;
        $order->customer_phone = $this->customer_phone ?: null;
        $order->customer_address = $this->customer_address ?: null;
        $order->notes = $this->notes ?: null;
        $order->total = $cart->total();
        $order->currency = 'RSD';
        $order->status = 'pending';
        $order->save();

        foreach ($items as $cartItem) {
            $order->items()->create([
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'size' => $cartItem->size,
                'size_cm' => $cartItem->size_cm_display,
                'unit' => $cartItem->product->unit ?? 'cm',
                'price' => $cartItem->price,
                'quantity' => $cartItem->quantity,
                'subtotal' => $cartItem->subtotal,
            ]);
        }

        $cart->clear();
        Mail::to(config('mail.from.address'))->send(new OrderPlaced($order));

        session()->flash('success', __('messages.Order placed successfully') . ' #' . $order->order_number);
        $this->redirect(route('home'), navigate: true);
    }
};
?>

<div>
    @if($this->items->isEmpty())
        <p class="text-etno-brown">{{ __('messages.Your cart is empty') }}</p>
        <a href="{{ route('cart.index') }}" class="inline-block mt-4 px-6 py-2 bg-etno-terracotta text-white rounded-full shadow-md hover:bg-etno-rust transition font-medium">{{ __('messages.Cart') }}</a>
    @else
        <h1 class="text-2xl font-semibold text-etno-dark mb-4">{{ __('messages.Checkout') }}</h1>

        @error('min_total')
            <p class="mb-4 p-3 rounded-4 bg-red-50 text-red-700 border border-red-200">{{ $message }}</p>
        @enderror

        <form wire:submit="submit" class="max-w-2xl">
            <div class="kafana-panel bg-white rounded-4 shadow-kafana p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-etno-brown">{{ __('messages.Customer name') }} *</label>
                    <input type="text" wire:model="customer_name" required class="mt-1 w-full border border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark focus:outline-none focus:ring-2 focus:ring-etno-terracotta/30 focus:border-etno-terracotta transition">
                    @error('customer_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-etno-brown">{{ __('messages.Customer email') }} *</label>
                    <input type="email" wire:model="customer_email" required class="mt-1 w-full border border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark focus:outline-none focus:ring-2 focus:ring-etno-terracotta/30 focus:border-etno-terracotta transition">
                    @error('customer_email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-etno-brown">{{ __('messages.Customer phone') }}</label>
                    <input type="text" wire:model="customer_phone" class="mt-1 w-full border border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark focus:outline-none focus:ring-2 focus:ring-etno-terracotta/30 focus:border-etno-terracotta transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-etno-brown">{{ __('messages.Address') }}</label>
                    <textarea wire:model="customer_address" rows="2" class="mt-1 w-full border border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark focus:outline-none focus:ring-2 focus:ring-etno-terracotta/30 focus:border-etno-terracotta transition"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-etno-brown">{{ __('messages.Notes') }}</label>
                    <textarea wire:model="notes" rows="2" class="mt-1 w-full border border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark focus:outline-none focus:ring-2 focus:ring-etno-terracotta/30 focus:border-etno-terracotta transition"></textarea>
                </div>
            </div>

            <div class="mt-6 p-4 kafana-panel bg-etno-sand/80 rounded-4">
                <p class="font-semibold text-etno-dark">{{ __('messages.Order summary') }}</p>
                <ul class="mt-2 space-y-1 text-etno-brown">
                    @foreach($this->items as $item)
                        <li>{{ $item->product->name }} ({{ $item->size_label_with_cm }}) × {{ $item->quantity }} — {{ number_format($item->subtotal, 0) }} RSD</li>
                    @endforeach
                </ul>
                <p class="mt-2 font-semibold text-etno-dark">{{ __('messages.Total') }}: {{ number_format($this->total, 0) }} RSD</p>
            </div>

            <div class="mt-6 flex gap-4">
                <a href="{{ route('cart.index') }}" class="px-6 py-2 border border-etno-clay text-etno-brown rounded-full hover:bg-etno-sand transition">{{ __('messages.Cart') }}</a>
                <button type="submit" class="px-6 py-3 bg-etno-terracotta text-white rounded-full shadow-md hover:bg-etno-rust transition font-medium">
                    {{ __('messages.Place order') }}
                </button>
            </div>
        </form>
    @endif
</div>
