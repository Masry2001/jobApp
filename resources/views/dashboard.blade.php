<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Welcome Message -->
            <div
                class="mb-8 p-6 bg-gradient-to-r from-blue-600/10 to-purple-600/10 border border-blue-500/20 rounded-xl backdrop-blur-sm">
                <h3 class="text-2xl font-bold text-white mb-2">
                    {{ __('Welcome back, :name! 👋', ['name' => auth()->user()->name]) }}
                </h3>
                <p class="text-gray-400">Ready to find your next opportunity?</p>
            </div>

            <!-- Search and Filters Section -->
            <div class="mb-8 p-6 bg-gray-800/50 border border-gray-700 rounded-xl backdrop-blur-sm">
                <form action="{{ route('dashboard') }}" method="GET" class="space-y-4">

                    <!-- Search Bar -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <x-input-label for="search" :value="__('Search jobs')" class="mb-1.5" />
                            <x-text-input id="search" type="text" name="search" :value="request('search')"
                                placeholder="Job title, company, or keywords..." class="w-full" />
                            <x-input-error :messages="$errors->get('search')" class="mt-2" />
                        </div>
                        <div class="flex items-end">
                            <x-primary-button type="submit" class="w-full sm:w-auto">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                {{ __('Search') }}
                            </x-primary-button>
                            <!-- clear search -->
                            @if (request('search'))
                                <x-link class="ml-4 px-6 py-3" :href="route('dashboard', ['search' => null, 'filter' => request('filter')])" variant="primary">
                                    Clear Search
                                </x-link>
                            @endif
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="flex flex-wrap gap-2 pt-2">
                        <span class="text-sm text-gray-400 self-center mr-2">Filter by:</span>
                        <x-link :href="route('dashboard', ['search' => request('search'), 'filter' => 'Full-Time'])"
                            variant="default">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Full-Time
                        </x-link>
                        <x-link :href="route('dashboard', ['search' => request('search'), 'filter' => 'Remote'])"
                            variant="default">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Remote
                        </x-link>
                        <x-link :href="route('dashboard', ['search' => request('search'), 'filter' => 'Hybrid'])"
                            variant="default">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Hybrid
                        </x-link>
                        <x-link :href="route('dashboard', ['search' => request('search'), 'filter' => 'Contract'])"
                            variant="default">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Contract
                        </x-link>
                        <!-- clear filter -->
                        @if (request('filter'))
                            <x-link :href="route('dashboard', ['search' => request('search')])" variant="primary">
                                Clear Filter
                            </x-link>
                        @endif

                    </div>
                    <!-- if there is a filter keep it -->
                    @if (request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif
                </form>
            </div>

            <!-- Job List -->
            <div class="space-y-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-white">Available Positions</h3>
                    <span class="text-sm text-gray-400">{{ $jobVacancies->total() }} jobs found</span>
                </div>

                @forelse ($jobVacancies as $job)
                    <div
                        class="group p-6 bg-gray-800/50 border border-gray-700 hover:border-blue-500/50 rounded-xl backdrop-blur-sm transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/10">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-3 mb-3">
                                    <div
                                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ substr($job->company->name, 0, 3) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('job-vacancies.show', $job) }}"
                                            class="text-xl font-semibold text-blue-500 hover:text-blue-600 transition-colors duration-200">
                                            {{ $job->title }}
                                        </a>
                                        <p class="text-gray-400 mt-1">{{ $job->company->name }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-400 mb-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $job->location }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        ${{ number_format($job->salary) }} / year
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $job->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <x-link :href="route('job-vacancies.apply', $job)" variant="primary" class="inline-flex">
                                    Apply Now
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </x-link>
                            </div>

                            <div class="flex sm:flex-col items-start gap-2">
                                <span
                                    class="px-3 py-1.5 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-full text-xs font-semibold whitespace-nowrap">
                                    {{ $job->type }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 px-4 bg-gray-800/30 border border-gray-700 rounded-xl">
                        <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-400 mb-2">No jobs found</h3>
                        <p class="text-gray-500">Try adjusting your search or filters</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $jobVacancies->links() }}
            </div>
        </div>
    </div>
</x-app-layout>