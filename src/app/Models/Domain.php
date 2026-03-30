<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $user_id
 * @property string $url
 * @property string|null $name
 * @property int $check_interval
 * @property int $check_timeout
 * @property string $check_method
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $last_checked_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Domain extends Model
{
    protected $fillable = [
        'url',
        'name',
        'check_interval',
        'check_timeout',
        'check_method',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_checked_at' => 'datetime',
            'check_interval' => 'integer',
            'check_timeout' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checks(): HasMany
    {
        return $this->hasMany(DomainCheck::class);
    }

    public function latestCheck(): HasOne
    {
        return $this->hasOne(DomainCheck::class)->latestOfMany('checked_at');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeNeedsCheck(Builder $query): void
    {
        $query->where(function ($q) {
            $q->whereNull('last_checked_at')
                ->orWhereRaw('DATE_ADD(last_checked_at, INTERVAL (check_interval * 60 - 30) SECOND) <= NOW()');
        });
    }
}
