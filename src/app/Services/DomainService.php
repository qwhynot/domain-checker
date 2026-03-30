<?php

namespace App\Services;

use App\Models\Domain;
use App\Models\User;

class DomainService
{
    public function create(User $user, array $data): Domain
    {
        $domain = new Domain($data);
        $domain->user()->associate($user);
        $domain->save();

        return $domain;
    }

    public function update(Domain $domain, array $data): Domain
    {
        $domain->fill($data);
        $domain->save();

        return $domain;
    }

    public function delete(Domain $domain): void
    {
        $domain->delete();
    }

    public function calculateUptime(Domain $domain, int $hours): float
    {
        $since = now()->subHours($hours);

        $total = $domain->checks()
            ->where('checked_at', '>=', $since)
            ->count();

        if ($total === 0) {
            return 0;
        }

        $successful = $domain->checks()
            ->where('checked_at', '>=', $since)
            ->where('status', 'up')
            ->count();

        return round(($successful / $total) * 100, 2);
    }
}
