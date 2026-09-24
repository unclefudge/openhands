<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'organisation',
        'phone',
        'service',
        'timeframe',
        'referral',
        'message',
        'spam_score',
        'original_spam_score',
        'spam_status',
        'score_reasons',
        'review_status',
        'ip_hash',
        'ip_country',
        'email_country',
        'link_countries',
        'message_fingerprint',
        'user_agent',
        'turnstile_passed',
        'completion_seconds',
        'notification_sent_at',
        'confirmation_sent_at',
        'reviewed_at',
        'reviewed_by',
        'last_scored_at',
    ];

    protected function casts(): array
    {
        return [
            'score_reasons' => 'array',
            'link_countries' => 'array',
            'turnstile_passed' => 'boolean',
            'notification_sent_at' => 'datetime',
            'confirmation_sent_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'last_scored_at' => 'datetime',
        ];
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeAwaitingReview(Builder $query): Builder
    {
        return $query->where('review_status', 'unreviewed');
    }

    /**
     * @return array<string, string|null>
     */
    public function mailPayload(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'organisation' => $this->organisation,
            'phone' => $this->phone,
            'service' => $this->service,
            'timeframe' => $this->timeframe,
            'referral' => $this->referral,
            'message' => $this->message,
        ];
    }
}
