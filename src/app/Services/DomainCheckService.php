<?php

namespace App\Services;

use App\Models\Domain;
use App\Models\DomainCheck;
use App\Notifications\DomainDownNotification;
use App\Notifications\DomainRecoveryNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DomainCheckService
{
    public function check(Domain $domain): DomainCheck
    {
        $previousCheck = $domain->latestCheck;
        $previousStatus = $previousCheck?->status;
        
        $startTime = microtime(true);

        try {
            $response = Http::timeout($domain->check_timeout)
                ->withOptions(['allow_redirects' => true])
                ->{strtolower($domain->check_method)}($domain->url);

            $responseTimeMs = (int) ((microtime(true) - $startTime) * 1000);

            $check = new DomainCheck([
                'status'           => $response->successful() ? 'up' : 'down',
                'http_status_code' => $response->status(),
                'response_time_ms' => $responseTimeMs,
                'error_message'    => $response->successful() ? null : "HTTP {$response->status()}",
                'checked_at'       => now(),
            ]);
        } catch (\Exception $e) {
            $responseTimeMs = (int) ((microtime(true) - $startTime) * 1000);

            $check = new DomainCheck([
                'status'           => 'error',
                'http_status_code' => null,
                'response_time_ms' => $responseTimeMs,
                'error_message'    => Str::limit($e->getMessage(), 500),
                'checked_at'       => now(),
            ]);
        }

        $check->domain()->associate($domain);
        $check->save();

        $domain->last_checked_at = now();
        $domain->save();

        $this->sendNotificationIfNeeded($domain, $previousStatus, $check);

        return $check;
    }

    private function sendNotificationIfNeeded(Domain $domain, ?string $previousStatus, DomainCheck $check): void
    {
        $currentStatus = $check->status;

        $wasHealthy = $previousStatus === null || $previousStatus === 'up';

        if ($wasHealthy && in_array($currentStatus, ['down', 'error'], true)) {
            $domain->user->notify(new DomainDownNotification($domain, $check));
        }

        if (in_array($previousStatus, ['down', 'error'], true) && $currentStatus === 'up') {
            $domain->user->notify(new DomainRecoveryNotification($domain, $check));
        }
    }
}
