<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactSubmission;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    public function store(Request $request)
    {
        // Honeypot — bots fill the hidden field; humans don't see it.
        if ($request->filled('website_url')) {
            return redirect()->route('pages.contact')->with('success', "Thanks! We'll be in touch.");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ]);

        $contactMessage = ContactMessage::create([
            ...$validated,
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent() ? substr($request->userAgent(), 0, 500) : null,
        ]);

        // Notify the partnership. A mail failure must never block the submission —
        // the DB record is the source of truth and the admin Filament inbox is the
        // backstop. SMTP misconfigurations / transient failures stay invisible to
        // the user.
        try {
            $recipient = config('services.contact.recipient', env('CONTACT_FORM_RECIPIENT'));
            if ($recipient) {
                Mail::to($recipient)->send(new ContactSubmission($contactMessage));
            }
        } catch (\Throwable $e) {
            Log::warning('Contact form mail dispatch failed', [
                'message_id' => $contactMessage->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('pages.contact')
            ->with('success', "Thanks for reaching out! We'll be in touch soon.");
    }
}
