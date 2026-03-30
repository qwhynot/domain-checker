<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Domains') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Table --}}
            <div class="flex justify-end">
                <a href="{{ route('domains.create') }}" style="margin-top: 20px;background-color:#4f46e5;color:#fff;display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:12px;font-weight:600;font-size:14px;text-decoration:none;box-shadow:0 4px 12px rgba(79,70,229,.35);transition:background .15s" onmouseover="this.style.backgroundColor='#4338ca'" onmouseout="this.style.backgroundColor='#4f46e5'">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Domain
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                @if($domains->isEmpty())
                    <div class="text-center py-20 px-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 mb-4">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800">No domains yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Add your first domain to start monitoring it.</p>
                        <div class="mt-6">
                            <a href="{{ route('domains.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 rounded-lg text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Domain
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/60">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Domain</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Last Check</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Interval</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($domains as $domain)
                                    <tr class="hover:bg-gray-50/70 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-800 text-sm">{{ $domain->name ?: $domain->url }}</div>
                                            @if($domain->name)
                                                <div class="text-xs text-gray-400 mt-0.5">{{ $domain->url }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($domain->latestCheck)
                                                @if($domain->latestCheck->status === 'up')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                                        UP
                                                    </span>
                                                @elseif($domain->latestCheck->status === 'down')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                        DOWN
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                                        ERROR
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $domain->last_checked_at?->diffForHumans() ?? 'Never' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            Every {{ $domain->check_interval }} min
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('domains.show', $domain) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View
                                                </a>
                                                <a href="{{ route('domains.edit', $domain) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('domains.destroy', $domain) }}" method="POST" class="inline" onsubmit="return confirm('Delete this domain?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($domains->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $domains->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
