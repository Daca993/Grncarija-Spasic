<?php

use App\Domain\Cart\Services\CartService;
use Livewire\Component;

new class extends Component
{
    public array $quantities = [];

    public function mount(): void
    {
        $items = app(CartService::class)->items();
        $this->quantities = $items->keyBy('id')->map(fn ($i) => (string) $i->quantity)->toArray();
    }

    public function getItemsProperty()
    {
        return app(CartService::class)->items();
    }

    public function getTotalProperty(): float
    {
        return app(CartService::class)->total();
    }

    public function updatedQuantities(): void
    {
        foreach ($this->quantities as $id => $qty) {
            $q = (int) $qty;
            if ($q < 1) {
                $q = 1;
            }
            if ($q > 99) {
                $q = 99;
            }
            app(CartService::class)->update((int) $id, $q);
        }
    }

    public function remove(int $id): void
    {
        app(CartService::class)->remove($id);
        unset($this->quantities[$id]);
    }
};
?>

<div>
    <h1 class="text-2xl font-semibold text-etno-dark mb-4">{{ __('messages.Cart') }}</h1>

    @php
        $minTotal = (int) config('app.wholesale_min_total', 70000);
        $belowMin = (bool) config('app.wholesale_only') && $this->total < $minTotal;
    @endphp

    @if(config('app.wholesale_only'))
        <div class="mb-5 kafana-panel bg-etno-sand/70 rounded-4 p-4 text-etno-brown">
            <p class="font-semibold text-etno-dark">{{ __('messages.wholesale_notice_title') }}</p>
            <p class="mt-1 text-sm">
                {{ __('messages.wholesale_notice_body', ['min' => number_format($minTotal, 0)]) }}
            </p>
            @if($belowMin)
                <p class="mt-2 text-sm font-semibold text-etno-rust">
                    {{ __('messages.wholesale_minimum_not_met', ['min' => number_format($minTotal, 0), 'current' => number_format($this->total, 0)]) }}
                </p>
            @endif
        </div>
    @endif

    @if($this->items->isEmpty())
        <p class="text-etno-brown">{{ __('messages.Your cart is empty') }}</p>
        <a href="{{ route('products.index') }}" class="inline-block mt-4 px-6 py-2 bg-etno-terracotta text-white rounded-full shadow-md hover:bg-etno-rust transition font-medium">{{ __('messages.Products') }}</a>
    @else
        <div class="space-y-4">
            @foreach($this->items as $item)
                <div class="flex flex-wrap items-center gap-4 kafana-panel bg-white p-4 rounded-4 shadow-kafana">
                    <img src="{{ $item->product->image_url ?? config('app.default_product_image') }}" alt="" class="w-20 h-20 object-cover rounded-4">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('products.show', [$item->product->category->slug, $item->product->slug]) }}" class="font-medium text-etno-dark hover:text-etno-terracotta transition">{{ $item->product->name }}</a>
                        <p class="text-etno-terracotta">{{ number_format($item->price, 0) }} {{ $item->product->currency }}</p>
                        <p class="text-sm text-etno-brown">{{ __('messages.Size') }}: {{ $item->size_label_with_cm }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" wire:model.blur="quantities.{{ $item->id }}" min="1" max="99" class="w-16 border border-etno-clay/40 rounded-4 px-2 py-1 bg-etno-cream text-etno-dark focus:outline-none focus:ring-2 focus:ring-etno-terracotta/30 focus:border-etno-terracotta transition">
                    </div>
                    <p class="font-medium w-24 text-right text-etno-dark">{{ number_format($item->subtotal, 0) }} RSD</p>
                    <button type="button" wire:click="remove({{ $item->id }})" class="flex items-center justify-center w-10 h-10 rounded-full border border-etno-brown/30 text-etno-rust hover:bg-etno-red hover:text-white hover:border-etno-red transition flex-shrink-0" title="{{ __('messages.Remove from cart') }}" aria-label="{{ __('messages.Remove from cart') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex flex-wrap items-center justify-between gap-4">
            <p class="text-lg font-semibold text-etno-dark">{{ __('messages.Total') }}: {{ number_format($this->total, 0) }} RSD</p>
            <a href="{{ route('checkout.show') }}"
               @class([
                   'px-6 py-3 rounded-full shadow-md transition font-medium',
                   'bg-etno-terracotta text-white hover:bg-etno-rust' => !$belowMin,
                   'bg-etno-clay/40 text-etno-brown cursor-not-allowed pointer-events-none' => $belowMin,
               ])
               aria-disabled="{{ $belowMin ? 'true' : 'false' }}">
                {{ __('messages.Checkout') }}
            </a>
        </div>
    @endif
</div>
