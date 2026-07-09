<?php

use Livewire\Component;

new class extends Component
{
    public string $locale = '';

    public function mount(): void
    {
        $this->locale = app()->getLocale();
    }

    public function updatedLocale(): void
    {
        if (!in_array($this->locale, config('app.available_locales', []), true)) {
            return;
        }
        session(['locale' => $this->locale]);
        // Livewire POST dolazi na /livewire-xxx/update, pa request()->url() nije stranica; koristimo Referer
        $target = request()->header('Referer') ?? url('/');
        $target = preg_replace('/[?&]locale=[^&]*/', '', $target);
        $target = rtrim($target, '?&');
        $target .= (str_contains($target, '?') ? '&' : '?') . 'locale=' . $this->locale;
        $this->redirect($target, navigate: false);
    }
};
?>

<div class="relative inline-block">
    <select wire:model.live="locale" class="border-0 bg-transparent text-etno-brown hover:text-etno-terracotta text-sm font-medium cursor-pointer focus:ring-0 focus:outline-none">
        @foreach(config('app.available_locales') as $loc)
            <option value="{{ $loc }}">{{ strtoupper($loc) }}</option>
        @endforeach
    </select>
</div>
