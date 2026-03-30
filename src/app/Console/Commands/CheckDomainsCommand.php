<?php

namespace App\Console\Commands;

use App\Jobs\CheckDomainJob;
use App\Models\Domain;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('domains:check')]
#[Description('Dispatch check jobs for domains that need checking')]
class CheckDomainsCommand extends Command
{
    public function handle(): int
    {
        $domains = Domain::active()->needsCheck()->get();

        $this->info("Dispatching checks for {$domains->count()} domains...");

        foreach ($domains as $domain) {
            CheckDomainJob::dispatch($domain);
        }

        $this->info('Done.');
        
        return Command::SUCCESS;
    }
}
