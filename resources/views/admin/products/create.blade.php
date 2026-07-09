@extends('layouts.app')

@section('content')
<div class="py-8 w-full max-w-2xl">
    <h1 class="text-2xl font-semibold text-etno-dark mb-6">{{ __('messages.admin_new_product') }}</h1>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="kafana-panel bg-white rounded-4 shadow-kafana p-6 md:p-8 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-etno-brown mb-1">{{ __('messages.admin_category_label') }} *</label>
            <select name="category_id" required class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark">
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-etno-brown mb-1">Naziv (srpski) *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark" placeholder="Naziv proizvoda">
            @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-etno-brown mb-1">Opis (srpski)</label>
            <textarea name="description" placeholder="Opis proizvoda" class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark" rows="4">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-etno-brown mb-1">Slika</label>
            <input type="file" name="image" accept="image/*" class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-etno-brown mb-1">{{ __('messages.Currency') }}</label>
                <input type="hidden" name="currency" value="RSD">
                <input type="text" value="RSD" disabled class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark opacity-80 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-etno-brown mb-1">Jedinica mere</label>
                <select name="unit" class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark">
                    @foreach(\App\Domain\Catalog\Models\Product::UNITS as $key => $label)
                        <option value="{{ $key }}" {{ old('unit', 'cm') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="text-sm text-etno-brown/70 mt-1">Za prikaz veličine: cm, litri, kom, itd.</p>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-etno-brown mb-1">Veličine – vrednost i cena po veličini *</label>
            <p class="text-sm text-etno-brown/70 mb-2">Npr. 10×12, Ø18, 2 (litri). Cena obavezna za svaku veličinu.</p>
            <div class="grid grid-cols-3 gap-4">
                <div class="border-2 border-etno-clay/40 rounded-4 p-3 bg-etno-sand/30">
                    <label class="block text-sm font-medium text-etno-dark">Mala</label>
                    <input type="text" name="size_mala_cm" value="{{ old('size_mala_cm') }}" placeholder="cm (npr. 10×12)" class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 mt-2 bg-etno-cream text-etno-dark">
                    <input type="number" name="price_mala" step="0.01" value="{{ old('price_mala') }}" required placeholder="Cena" class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 mt-2 bg-etno-cream text-etno-dark">
                </div>
                <div class="border-2 border-etno-clay/40 rounded-4 p-3 bg-etno-sand/30">
                    <label class="block text-sm font-medium text-etno-dark">Srednja</label>
                    <input type="text" name="size_srednja_cm" value="{{ old('size_srednja_cm') }}" placeholder="cm (npr. 15×20)" class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 mt-2 bg-etno-cream text-etno-dark">
                    <input type="number" name="price_srednja" step="0.01" value="{{ old('price_srednja') }}" required placeholder="Cena" class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 mt-2 bg-etno-cream text-etno-dark">
                </div>
                <div class="border-2 border-etno-clay/40 rounded-4 p-3 bg-etno-sand/30">
                    <label class="block text-sm font-medium text-etno-dark">Velika</label>
                    <input type="text" name="size_velika_cm" value="{{ old('size_velika_cm') }}" placeholder="cm (npr. 20×25)" class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 mt-2 bg-etno-cream text-etno-dark">
                    <input type="number" name="price_velika" step="0.01" value="{{ old('price_velika') }}" required placeholder="Cena" class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 mt-2 bg-etno-cream text-etno-dark">
                </div>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-etno-brown mb-1">{{ __('messages.admin_sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full border-2 border-etno-clay/40 rounded-4 px-3 py-2 bg-etno-cream text-etno-dark">
        </div>
        <div>
            <label class="inline-flex items-center gap-2 text-etno-brown">
                <input type="checkbox" name="is_active" value="1" class="rounded border-etno-clay/60" {{ old('is_active', true) ? 'checked' : '' }}>
                {{ __('messages.admin_active') }}
            </label>
        </div>
        <p class="text-etno-brown/70 text-sm">{{ __('messages.admin_single_language_hint') }}</p>
        <div class="flex gap-4">
            <button type="submit" class="px-4 py-2 bg-etno-red text-white rounded-4 hover:opacity-90 transition">{{ __('messages.admin_save') }}</button>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-etno-brown/30 rounded-4 text-etno-brown hover:bg-etno-beige/80">{{ __('messages.admin_cancel') }}</a>
        </div>
    </form>
</div>
@endsection
