<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    public const STATUSES = ['pending', 'shortlisted', 'rejected', 'hired'];

    protected $fillable = [
        'job_listing_id',
        'user_id',
        'resume_path',
        'cover_letter',
        'status',
    ];

    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }

    // The job seeker who applied
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'shortlisted' => 'info',
            'rejected' => 'danger',
            'hired' => 'success',
            default => 'secondary',
        };
    }
}
