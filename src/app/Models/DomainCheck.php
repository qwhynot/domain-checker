<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $domain_id
 * @property string $status
 * @property int|null $http_status_code
 * @property int $response_time_ms
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon $checked_at
 * @property \Illuminate\Support\Carbon $created_at
 */
class DomainCheck extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'domain_id',
        'status',
        'http_status_code',
        'response_time_ms',
        'error_message',
        'checked_at',
    ];

    protected function casts(): array
    {
        return [
            'checked_at' => 'datetime',
            'response_time_ms' => 'integer',
            'http_status_code' => 'integer',
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }
}
