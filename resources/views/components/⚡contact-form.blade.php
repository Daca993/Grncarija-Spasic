<?php

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $message = '';
    public bool $sent = false;

    public function submit(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => __('messages.contact_name_required'),
            'email.required' => __('messages.contact_email_required'),
            'email.email' => __('messages.contact_email_invalid'),
            'message.required' => __('messages.contact_message_required'),
        ]);

        Mail::to((string) config('app.contact_email'))->send(new ContactFormMail(
            $this->name,
            $this->email,
            $this->message
        ));

        $this->sent = true;
        $this->reset(['name', 'email', 'message']);
    }
};
?>

<div>
    <h1 class="text-xl md:text-2xl font-semibold text-etno-dark mb-2 md:mb-3 text-center">{{ __('messages.Contact') }}</h1>
    <p class="text-etno-brown text-center mb-6 md:mb-8 text-sm md:text-base">{{ __('messages.contact_subtitle') }}</p>

    @if($sent)
        <p class="mb-6 p-4 rounded-4 bg-green-50 text-green-800 border border-green-200 text-center">{{ __('messages.contact_sent') }}</p>
    @endif

    <form wire:submit="submit" class="kafana-panel rounded-4 bg-etno-sand/40 p-6 md:p-8 border-2 border-etno-clay/30 max-w-2xl mx-auto">
        <div class="mb-4 md:mb-5">
            <label for="contact-name" class="block text-etno-dark font-medium mb-1.5 text-sm md:text-base">{{ __('messages.contact_name') }}</label>
            <input type="text" id="contact-name" wire:model="name" required
                   class="w-full rounded-4 border-2 border-etno-clay/40 px-4 py-2.5 md:py-3 text-etno-dark focus:border-etno-terracotta focus:ring-1 focus:ring-etno-terracotta text-base">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4 md:mb-5">
            <label for="contact-email" class="block text-etno-dark font-medium mb-1.5 text-sm md:text-base">{{ __('messages.contact_email') }}</label>
            <input type="email" id="contact-email" wire:model="email" required
                   class="w-full rounded-4 border-2 border-etno-clay/40 px-4 py-2.5 md:py-3 text-etno-dark focus:border-etno-terracotta focus:ring-1 focus:ring-etno-terracotta text-base">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-6 md:mb-8">
            <label for="contact-message" class="block text-etno-dark font-medium mb-1.5 text-sm md:text-base">{{ __('messages.contact_message') }}</label>
            <textarea id="contact-message" wire:model="message" rows="5" required
                      class="w-full rounded-4 border-2 border-etno-clay/40 px-4 py-2.5 md:py-3 text-etno-dark focus:border-etno-terracotta focus:ring-1 focus:ring-etno-terracotta text-base resize-y min-h-[120px]"></textarea>
            @error('message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="w-full md:w-auto px-8 py-3 rounded-4 font-medium bg-etno-red text-white hover:opacity-90 transition text-base" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('messages.contact_send') }}</span>
            <span wire:loading>{{ __('messages.contact_send') }}...</span>
        </button>
    </form>
</div>
