@extends('layouts.app')

@section('content')
<div class="py-8 max-w-2xl">
    <h1 class="text-2xl font-semibold mb-6">{{ __('messages.admin_new_category') }}</h1>
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block font-medium">{{ __('messages.admin_slug') }} *</label>
            <input type="text" name="slug" value="{{ old('slug') }}" required class="w-full border rounded px-3 py-2" placeholder="url-kategorija">
            @error('slug')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block font-medium">{{ __('messages.admin_name') }} (srpski) *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded px-3 py-2" placeholder="{{ __('messages.admin_name') }}">
            @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block font-medium">{{ __('messages.Notes') }} (srpski)</label>
            <textarea name="description" placeholder="{{ __('messages.Notes') }}" class="w-full border rounded px-3 py-2" rows="4">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block font-medium">{{ __('messages.admin_image') }}</label>
            <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block font-medium">{{ __('messages.admin_sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> {{ __('messages.admin_active') }}</label>
        </div>
        <p class="text-stone-500 text-sm">{{ __('messages.admin_single_language_hint') }}</p>
        <div class="flex gap-4">
            <button type="submit" class="px-4 py-2 bg-etno-red text-white rounded-4 hover:opacity-90 transition">{{ __('messages.admin_save') }}</button>
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 border rounded">{{ __('messages.admin_cancel') }}</a>
        </div>
    </form>
</div>
@endsection
