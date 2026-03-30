<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('domains.show', $domain) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Domain') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $domain->name ?: $domain->url }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('domains.update', $domain) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Basic info section --}}
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Basic Information</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="url" class="block text-sm font-medium text-gray-700">URL <span class="text-red-500">*</span></label>
                                <input type="url" name="url" id="url" value="{{ old('url', $domain->url) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('url') border-red-300 bg-red-50 @enderror">
                                @error('url')
                                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Name
                                    <span class="text-gray-400 font-normal">(optional)</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $domain->name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-300 bg-red-50 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Check settings section --}}
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Check Settings</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label for="check_interval" class="block text-sm font-medium text-gray-700">Interval <span class="text-gray-400 font-normal">(min)</span></label>
                                <input type="number" name="check_interval" id="check_interval" value="{{ old('check_interval', $domain->check_interval) }}" required min="1" max="1440"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('check_interval') border-red-300 bg-red-50 @enderror">
                                @error('check_interval')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="check_timeout" class="block text-sm font-medium text-gray-700">Timeout <span class="text-gray-400 font-normal">(sec)</span></label>
                                <input type="number" name="check_timeout" id="check_timeout" value="{{ old('check_timeout', $domain->check_timeout) }}" required min="1" max="60"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('check_timeout') border-red-300 bg-red-50 @enderror">
                                @error('check_timeout')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="check_method" class="block text-sm font-medium text-gray-700">HTTP Method</label>
                                <select name="check_method" id="check_method" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('check_method') border-red-300 bg-red-50 @enderror">
                                    <option value="HEAD" {{ old('check_method', $domain->check_method) === 'HEAD' ? 'selected' : '' }}>HEAD</option>
                                    <option value="GET" {{ old('check_method', $domain->check_method) === 'GET' ? 'selected' : '' }}>GET</option>
                                </select>
                                @error('check_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="p-6 bg-gray-50 flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $domain->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Active</span>
                        </label>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('domains.show', $domain) }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-5 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
