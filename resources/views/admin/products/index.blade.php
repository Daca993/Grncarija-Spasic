@extends('layouts.app')

@section('content')
<div class="py-8 w-full">
    <div class="rustic-card kafana-panel rounded-4 p-6 bg-etno-beige/80 w-full overflow-x-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-etno-dark">{{ __('messages.Products') }}</h1>
            <a href="{{ route('admin.product') }}" class="px-4 py-2 bg-etno-red text-white rounded-4 hover:opacity-90 transition">+ {{ __('messages.admin_new_product') }}</a>
        </div>
        <form class="mb-4" method="GET">
            <select name="category_id" onchange="this.form.submit()" class="border border-etno-brown/30 rounded-4 px-3 py-2 bg-white text-etno-dark">
                <option value="">{{ __('messages.admin_all_categories') }}</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </form>
        @if(session('success'))<p class="text-green-700 mb-4">{{ session('success') }}</p>@endif
        <table class="w-full min-w-[900px] table-auto bg-white/90 border border-etno-brown/20 rounded-4 overflow-hidden text-base md:text-lg">
            <thead><tr class="border-b border-etno-brown/30 bg-etno-brown/10"><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.admin_image') }}</th><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.admin_name') }}</th><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.admin_category') }}</th><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.Price') }}</th><th class="px-4 py-2"></th></tr></thead>
            <tbody>
                @foreach($products as $p)
                    <tr class="border-b border-etno-brown/10">
                        <td class="px-4 py-2">
                            @if($p->image_url)
                                <img src="{{ $p->image_url }}" alt="" class="w-12 h-12 object-cover rounded">
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-2 text-etno-dark">{{ $p->name }}</td>
                        <td class="px-4 py-2 text-etno-brown">{{ $p->category->name }}</td>
                        <td class="px-4 py-2 text-etno-brown">{{ $p->price }} {{ $p->currency }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.products.edit', $p) }}" class="text-etno-terracotta hover:text-etno-red mr-2">{{ __('messages.admin_edit') }}</a>
                            <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('messages.admin_delete_confirm') }}');">@csrf @method('DELETE')<button type="submit" class="text-etno-red hover:underline">{{ __('messages.admin_delete') }}</button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
