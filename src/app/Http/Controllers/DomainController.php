<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Models\Domain;
use App\Services\DomainService;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function __construct(
        private DomainService $domainService
    ) {}

    public function index(Request $request)
    {
        $domains = $request->user()
            ->domains()
            ->with('latestCheck')
            ->latest()
            ->paginate(15);

        return view('domains.index', compact('domains'));
    }

    public function create()
    {
        $this->authorize('create', Domain::class);

        return view('domains.create');
    }

    public function store(StoreDomainRequest $request)
    {
        $this->authorize('create', Domain::class);

        $domain = $this->domainService->create(
            $request->user(),
            $request->validated()
        );

        return redirect()
            ->route('domains.show', $domain)
            ->with('success', 'Domain created successfully.');
    }

    public function show(Domain $domain)
    {
        $this->authorize('view', $domain);

        $checks = $domain->checks()
            ->orderByDesc('checked_at')
            ->paginate(25);

        $uptime24h = $this->domainService->calculateUptime($domain, 24);
        $uptime7d = $this->domainService->calculateUptime($domain, 168);

        return view('domains.show', compact('domain', 'checks', 'uptime24h', 'uptime7d'));
    }

    public function edit(Domain $domain)
    {
        $this->authorize('update', $domain);

        return view('domains.edit', compact('domain'));
    }

    public function update(UpdateDomainRequest $request, Domain $domain)
    {
        $domain = $this->domainService->update(
            $domain,
            $request->validated()
        );

        return redirect()
            ->route('domains.show', $domain)
            ->with('success', 'Domain updated successfully.');
    }

    public function destroy(Domain $domain)
    {
        $this->authorize('delete', $domain);

        $this->domainService->delete($domain);

        return redirect()
            ->route('domains.index')
            ->with('success', 'Domain deleted successfully.');
    }
}
