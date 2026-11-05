<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventSubmission extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  bool  $isUpdate  true when an approved event was edited and went back to pending
     */
    public function __construct(
        public Event $event,
        public bool $isUpdate = false,
    ) {
    }

    public function envelope(): Envelope
    {
        $submitter = $this->event->submittedBy;

        return new Envelope(
            subject: ($this->isUpdate ? 'Event changed, needs re-approval: ' : 'New event to approve: ') . $this->event->title,
            replyTo: $submitter?->email
                ? [new Address($submitter->email, $submitter->name)]
                : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-submission',
        );
    }
}
