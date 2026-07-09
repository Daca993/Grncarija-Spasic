@extends('layouts.app')

@section('content')
<div class="py-8 w-full">
    @if(session('success'))<p class="text-green-700 mb-4">{{ session('success') }}</p>@endif

    <div class="w-full">
        <div class="rustic-card kafana-panel rounded-4 p-6 bg-etno-beige/80 w-full overflow-x-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-etno-dark">{{ __('messages.Categories') }}</h1>
                <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-etno-red text-white rounded-4 hover:opacity-90 transition">+ {{ __('messages.admin_new_category') }}</a>
            </div>
            <table class="w-full min-w-[900px] table-auto bg-white/90 border border-etno-brown/20 rounded-4 overflow-hidden text-base md:text-lg">
                <thead><tr class="border-b border-etno-brown/30 bg-etno-brown/10"><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.admin_image') }}</th><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.admin_slug') }}</th><th class="px-4 py-2 text-left text-etno-dark">{{ __('messages.admin_name') }}</th><th class="px-4 py-2 text-etno-dark">{{ __('messages.admin_active') }}</th><th class="px-4 py-2"></th></tr></thead>
                <tbody>
                    @foreach($categories as $c)
                        <tr class="border-b border-etno-brown/10">
                            <td class="px-4 py-2">
                                @if($c->image_url)
                                    <img src="{{ $c->image_url }}" alt="" class="w-12 h-12 object-cover rounded">
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-2 text-etno-brown">{{ $c->slug }}</td>
                            <td class="px-4 py-2 text-etno-dark">{{ $c->name }}</td>
                            <td class="px-4 py-2 text-etno-brown">{{ $c->is_active ? __('messages.admin_yes') : __('messages.admin_no') }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.categories.edit', $c) }}" class="text-etno-terracotta hover:text-etno-red mr-2">{{ __('messages.admin_edit') }}</a>
                                <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('messages.admin_delete_confirm') }}');">@csrf @method('DELETE')<button type="submit" class="text-etno-red hover:underline">{{ __('messages.admin_delete') }}</button></form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
