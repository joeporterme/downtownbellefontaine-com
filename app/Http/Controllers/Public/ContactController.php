<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactSubmission;
use App\Models\ContactMessage;
use App\Services\SpamDetectionService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    public function store(Request $request, SpamDetectionService $spam)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ]);

        // Multi-signal spam scoring (honeypot + timing + content/email/phone/URL).
        // Split the single name field so the name-pattern heuristics engage.
        $nameParts = preg_split('/\s+/', trim($validated['name']), 2);
        $spamResult = $spam->analyze($validated + [
            'first_name' => $nameParts[0] ?? '',
            'last_name' => $nameParts[1] ?? '',
        ], [
            'honeypot' => $request->input('website_url'),
            ...$this->formTiming($request->input('form_token')),
            'ip' => $request->ip(),
        ]);

        // Always store — spam is kept (flagged) in the admin inbox for review,
        // never silently dropped.
        $contactMessage = ContactMessage::create([
            ...$validated,
            'status' => 'new',
            'is_spam' => $spamResult['is_spam'],
            'spam_score' => $spamResult['score'],
            'spam_reasons' => $spamResult['reasons'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent() ? substr($request->userAgent(), 0, 500) : null,
        ]);

        $subscribe = $request->boolean('newsletter');

        if (! $spamResult['is_spam']) {
            // Notify the partnership. A mail failure must never block the submission —
            // the DB record is the source of truth and the admin Filament inbox is
            // the backstop.
            try {
                $recipient = config('services.contact.recipient');
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
        } else {
            Log::info('Contact submission flagged as spam; stored but not notified', [
                'message_id' => $contactMessage->id,
                'spam_score' => $spamResult['score'],
                'spam_reasons' => $spamResult['reasons'],
            ]);
        }

        // Same response either way — never reveal the spam verdict to a bot.
        return redirect()->route('pages.contact')
            ->with('success', "Thanks for reaching out! We'll be in touch soon.");
    }

    /**
     * Decode the signed load-time token from <x-form-spam-fields>.
     *
     * @return array{token_state: 'ok'|'missing'|'invalid', form_start_time: ?int}
     */
    protected function formTiming(?string $token): array
    {
        if (! $token) {
            return ['token_state' => 'missing', 'form_start_time' => null];
        }

        try {
            $started = Crypt::decryptString($token);
        } catch (DecryptException) {
            return ['token_state' => 'invalid', 'form_start_time' => null];
        }

        return ctype_digit($started)
            ? ['token_state' => 'ok', 'form_start_time' => (int) $started]
            : ['token_state' => 'invalid', 'form_start_time' => null];
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
