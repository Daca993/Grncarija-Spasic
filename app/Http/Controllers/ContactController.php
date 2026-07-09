<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    private const CONTACT_EMAIL = 'misa.spale@gmail.com';

    public function index(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => __('messages.contact_name_required'),
            'email.required' => __('messages.contact_email_required'),
            'email.email' => __('messages.contact_email_invalid'),
            'message.required' => __('messages.contact_message_required'),
        ]);

        Mail::to(self::CONTACT_EMAIL)->send(new ContactFormMail(
            $validated['name'],
            $validated['email'],
            $validated['message']
        ));

        return redirect()->route('contact')->with('success', __('messages.contact_sent'));
    }
}
