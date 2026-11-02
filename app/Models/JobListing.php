<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Named JobListing (table: job_listings) because Laravel already uses
 * the "jobs" table for its queue system.
 */
class JobListing extends Model
{
    public const CATEGORIES = [
        'IT & Software',
        'Design',
        'Marketing',
        'Sales',
        'Finance',
        'Education',
        'Healthcare',
        'Engineering',
        'Customer Support',
        'Other',
    ];

    public const TYPES = ['Full-time', 'Part-time', 'Internship', 'Contract', 'Remote'];

    protected $fillable = [
        'title',
        'company_name',
        'location',
        'category',
        'job_type',
        'salary_min',
        'salary_max',
        'description',
        'requirements',
        'deadline',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'salary_min' => 'integer',
            'salary_max' => 'integer',
        ];
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /** Open jobs whose deadline has not passed. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('status', 'open')
            ->where(fn ($q) => $q->whereNull('deadline')->orWhereDate('deadline', '>=', now()->toDateString()));
    }

    /** Search filters coming from the jobs search form. */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, function ($q, $search) {
                $q->where(function ($w) use ($search) {
                    $w->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                });
            })
            ->when($filters['location'] ?? null, fn ($q, $loc) => $q->where('location', 'like', "%{$loc}%"))
            ->when($filters['category'] ?? null, fn ($q, $cat) => $q->where('category', $cat))
            ->when($filters['type'] ?? null, fn ($q, $type) => $q->where('job_type', $type));
    }

    public function isOpen(): bool
    {
        return $this->status === 'open' && (! $this->deadline || $this->deadline->endOfDay()->isFuture());
    }

    public function getSalaryRangeAttribute(): string
    {
        if (! $this->salary_min && ! $this->salary_max) {
            return 'Not disclosed';
        }
        if ($this->salary_min && $this->salary_max) {
            return '₹'.number_format($this->salary_min).' – ₹'.number_format($this->salary_max);
        }

        return '₹'.number_format($this->salary_min ?: $this->salary_max);
    }
}
