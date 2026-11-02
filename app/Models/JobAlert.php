<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class JobAlert extends Model
{
    protected $fillable = ['keyword', 'location', 'category', 'job_type', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Does a newly posted job match every filled-in field of this alert? */
    public function matches(JobListing $job): bool
    {
        if ($this->keyword) {
            $haystack = $job->title.' '.$job->description.' '.$job->company_name;
            if (! Str::contains($haystack, $this->keyword, ignoreCase: true)) {
                return false;
            }
        }
        if ($this->location && ! Str::contains($job->location, $this->location, ignoreCase: true)) {
            return false;
        }
        if ($this->category && $this->category !== $job->category) {
            return false;
        }
        if ($this->job_type && $this->job_type !== $job->job_type) {
            return false;
        }

        return true;
    }

    public function getSummaryAttribute(): string
    {
        return collect([
            $this->keyword ? '"'.$this->keyword.'"' : null,
            $this->location ? 'in '.$this->location : null,
            $this->category,
            $this->job_type,
        ])->filter()->implode(' · ') ?: 'All jobs';
    }
}
