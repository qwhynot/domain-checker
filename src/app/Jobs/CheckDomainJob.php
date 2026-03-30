<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Services\DomainCheckService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDomainJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 1;
    public int $timeout = 70;
    public int $uniqueFor = 120;

    public function __construct(
        public Domain $domain
    ) {}

    public function uniqueId(): string
    {
        return (string) $this->domain->id;
    }

    public function handle(DomainCheckService $service): void
    {
        $service->check($this->domain);
    }
}
