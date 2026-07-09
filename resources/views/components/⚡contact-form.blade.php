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
    @if($sent)
        <p class="mb-6 p-4 rounded-4 bg-green-50 text-green-800 border border-green-200 text-center">{{ __('messages.contact_sent') }}</p>
    @endif

    <form wire:submit="submit" style="display: flex; flex-direction: column; gap: 1rem; width: 100%;">
        <div style="width: 100%;">
            <label for="contact-name" class="sr-only">{{ __('messages.contact_name') }}</label>
            <input type="text" id="contact-name" wire:model="name" required placeholder="{{ __('messages.contact_name') }}" style="width: 100%; box-sizing: border-box;">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div style="width: 100%;">
            <label for="contact-email" class="sr-only">{{ __('messages.contact_email') }}</label>
            <input type="email" id="contact-email" wire:model="email" required placeholder="{{ __('messages.contact_email') }}" style="width: 100%; box-sizing: border-box;">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div style="width: 100%;">
            <label for="contact-message" class="sr-only">{{ __('messages.contact_message') }}</label>
            <textarea id="contact-message" wire:model="message" rows="5" required placeholder="{{ __('messages.contact_message') }}" style="width: 100%; box-sizing: border-box; resize: vertical; min-height: 150px;"></textarea>
            @error('message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn-primary" style="border: none; cursor: pointer; width: 100%;" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('messages.contact_send') }}</span>
            <span wire:loading>{{ __('messages.contact_send') }}...</span>
        </button>
    </form>
</div>
