<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactSubmission;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        $subscribe = $request->boolean('newsletter');

        // Notify the partnership. A mail failure must never block the submission —
        // the DB record is the source of truth and the admin Filament inbox is the
        // backstop. SMTP misconfigurations / transient failures stay invisible to
        // the user.
        try {
            $recipient = config('services.contact.recipient', env('CONTACT_FORM_RECIPIENT'));
            if ($recipient) {
                Mail::to($recipient)->send(new ContactSubmission($contactMessage, $subscribe));
            }
        } catch (\Throwable $e) {
            Log::warning('Contact form mail dispatch failed', [
                'message_id' => $contactMessage->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Opt-in: push the submitter to the same HubSpot newsletter list the
        // footer/popup forms use. Best-effort — never blocks the submission.
        if ($subscribe) {
            $this->subscribeToNewsletter($contactMessage, $request);
        }

        return redirect()->route('pages.contact')
            ->with('success', "Thanks for reaching out! We'll be in touch soon.");
    }

    /**
     * Subscribe a contact-form submitter to the HubSpot newsletter form.
     * The form requires first + last name, so split the single name field
     * (falling back to reusing the first token when only one was given).
     */
    protected function subscribeToNewsletter(ContactMessage $message, Request $request): void
    {
        $portal = config('services.hubspot.portal_id');
        $formId = config('services.hubspot.newsletter_form_id');

        if (! $portal || ! $formId) {
            return;
        }

        $parts = preg_split('/\s+/', trim($message->name), 2);
        $firstName = $parts[0] !== '' ? $parts[0] : $message->name;
        $lastName = $parts[1] ?? $firstName;

        try {
            Http::timeout(8)->asJson()->post(
                "https://api.hsforms.com/submissions/v3/integration/submit/{$portal}/{$formId}",
                [
                    'fields' => [
                        ['name' => 'firstname', 'value' => $firstName],
                        ['name' => 'lastname', 'value' => $lastName],
                        ['name' => 'email', 'value' => $message->email],
                    ],
                    'context' => [
                        'pageUri' => $request->fullUrl(),
                        'pageName' => 'Contact form',
                    ],
                ],
            );
        } catch (\Throwable $e) {
            Log::warning('Contact form newsletter subscribe failed', [
                'message_id' => $message->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
