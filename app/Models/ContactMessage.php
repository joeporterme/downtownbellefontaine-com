<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'is_spam',
        'spam_score',
        'spam_reasons',
        'internal_notes',
        'replied_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'is_spam' => 'boolean',
        'spam_score' => 'integer',
        'spam_reasons' => 'array',
    ];

    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }

    public const STATUSES = [
        'new' => 'New',
        'read' => 'Read',
        'replied' => 'Replied',
        'archived' => 'Archived',
    ];

    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['archived']);
    }
}
