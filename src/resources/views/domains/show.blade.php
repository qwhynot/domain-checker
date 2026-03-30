<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $domain->name ?: $domain->url }}
                </h2>
                @if($domain->name)
                    <p class="text-sm text-gray-500 mt-0.5">{{ $domain->url }}</p>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('domains.edit', $domain) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('domains.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- Domain Info --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-5">Domain Information</h3>
                    <dl class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">URL</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium break-all">{{ $domain->url }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</dt>
                            <dd class="mt-2">
                                @if($domain->latestCheck)
                                    @if($domain->latestCheck->status === 'up')
                                        <span class="px-2.5 py-1 inline-flex items-center gap-1.5 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse inline-block"></span>
                                            UP
                                        </span>
                                    @elseif($domain->latestCheck->status === 'down')
                                        <span class="px-2.5 py-1 inline-flex items-center gap-1.5 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                            DOWN
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex items-center gap-1.5 text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 inline-block"></span>
                                            ERROR
                                        </span>
                                    @endif
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                        Pending
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Check Interval</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">Every {{ $domain->check_interval }} min</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Checked</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $domain->last_checked_at?->diffForHumans() ?? 'Never' }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Timeout</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $domain->check_timeout }}s</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Method</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $domain->check_method }}</dd>
                        </div>

                        {{-- Uptime 24h --}}
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Uptime (24h)</dt>
                            <dd class="text-sm font-semibold {{ $uptime24h >= 99 ? 'text-green-700' : ($uptime24h >= 90 ? 'text-yellow-700' : 'text-red-700') }}">
                                {{ $uptime24h }}%
                            </dd>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full {{ $uptime24h >= 99 ? 'bg-green-500' : ($uptime24h >= 90 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                     style="width: {{ $uptime24h }}%"></div>
                            </div>
                        </div>

                        {{-- Uptime 7d --}}
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Uptime (7d)</dt>
                            <dd class="text-sm font-semibold {{ $uptime7d >= 99 ? 'text-green-700' : ($uptime7d >= 90 ? 'text-yellow-700' : 'text-red-700') }}">
                                {{ $uptime7d }}%
                            </dd>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full {{ $uptime7d >= 99 ? 'bg-green-500' : ($uptime7d >= 90 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                     style="width: {{ $uptime7d }}%"></div>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Check History --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Check History</h3>
                    @if($checks->isEmpty())
                        <div class="text-center py-10">
                            <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No checks yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HTTP Code</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Response Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Error</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($checks as $check)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $check->checked_at->format('Y-m-d H:i:s') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($check->status === 'up')
                                                    <span class="px-2 inline-flex items-center gap-1 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                                        UP
                                                    </span>
                                                @elseif($check->status === 'down')
                                                    <span class="px-2 inline-flex items-center gap-1 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                                        DOWN
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex items-center gap-1 text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 inline-block"></span>
                                                        ERROR
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($check->http_status_code)
                                                    @php $code = $check->http_status_code; @endphp
                                                    <span class="font-mono text-sm font-medium
                                                        {{ $code >= 200 && $code < 300 ? 'text-green-700' : '' }}
                                                        {{ $code >= 300 && $code < 400 ? 'text-blue-700' : '' }}
                                                        {{ $code >= 400 && $code < 500 ? 'text-orange-700' : '' }}
                                                        {{ $code >= 500 ? 'text-red-700' : '' }}
                                                    ">{{ $code }}</span>
                                                @else
                                                    <span class="text-sm text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($check->response_time_ms)
                                                    <span class="{{ $check->response_time_ms < 500 ? 'text-green-700' : ($check->response_time_ms < 1500 ? 'text-yellow-700' : 'text-red-700') }} font-medium">
                                                        {{ $check->response_time_ms }} ms
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                                {{ $check->error_message ?? '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $checks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
