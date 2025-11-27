<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-2xl text-white leading-tight">
        Job Details
      </h2>
    </div>
  </x-slot>

  <div class="py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Back button -->
      <div class="mb-6">
        <x-link :href="route('dashboard')" variant="default" class="inline-flex">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to Dashboard
        </x-link>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Main Content - Left Side -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Job Header Card -->
          <div class="p-6 bg-gray-800/50 border border-gray-700 rounded-xl backdrop-blur-sm">
            <div class="flex items-start gap-4 mb-6">
              <div
                class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white font-bold text-xl flex-shrink-0 shadow-lg">
                {{ substr($jobVacancy->company->name, 0, 2) }}
              </div>
              <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">
                  {{ $jobVacancy->title }}
                </h1>
                <a href="{{ $jobVacancy->company->website }}" target="_blank"
                  class="text-lg text-blue-400 hover:text-blue-300 transition-colors duration-200 flex items-center gap-2">
                  {{ $jobVacancy->company->name }}
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                  </svg>
                </a>
              </div>
              <span
                class="px-4 py-2 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-full text-sm font-semibold whitespace-nowrap">
                {{ $jobVacancy->type }}
              </span>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="flex items-center gap-3 p-3 bg-gray-900/50 rounded-lg border border-gray-700/50">
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-gray-500">Location</p>
                  <p class="text-sm font-semibold text-gray-200">{{ $jobVacancy->location }}</p>
                </div>
              </div>

              <div class="flex items-center gap-3 p-3 bg-gray-900/50 rounded-lg border border-gray-700/50">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-gray-500">Salary</p>
                  <p class="text-sm font-semibold text-gray-200">${{ number_format($jobVacancy->salary) }}/year</p>
                </div>
              </div>

              <div class="flex items-center gap-3 p-3 bg-gray-900/50 rounded-lg border border-gray-700/50">
                <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-gray-500">Posted</p>
                  <p class="text-sm font-semibold text-gray-200">{{ $jobVacancy->created_at->diffForHumans() }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Job Description -->
          <div class="p-6 bg-gray-800/50 border border-gray-700 rounded-xl backdrop-blur-sm">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Job Description
            </h2>
            <div class="prose prose-invert max-w-none">
              <p class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $jobVacancy->description }}</p>
            </div>
          </div>

          <!-- Apply Button - Mobile -->
          <div class="lg:hidden">
            <x-link :href="route('job-vacancies.apply', $jobVacancy)" variant="primary"
              class="w-full text-center justify-center">
              Apply for this Position
              <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </x-link>
          </div>

        </div>

        <!-- Sidebar - Right Side -->
        <div class="lg:col-span-1 space-y-6">

          <!-- Job Overview Card -->
          <div class="p-6 bg-gray-800/50 border border-gray-700 rounded-xl backdrop-blur-sm sticky top-6">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              Job Overview
            </h2>

            <div class="space-y-4">
              <div class="pb-4 border-b border-gray-700">
                <p class="text-sm text-gray-500 mb-1">Date Posted</p>
                <p class="text-base font-semibold text-gray-200">{{ $jobVacancy->created_at->format('M d, Y') }}</p>
              </div>

              <div class="pb-4 border-b border-gray-700">
                <p class="text-sm text-gray-500 mb-1">Company</p>
                <p class="text-base font-semibold text-gray-200">{{ $jobVacancy->company->name }}</p>
              </div>

              <div class="pb-4 border-b border-gray-700">
                <p class="text-sm text-gray-500 mb-1">Company Website</p>
                <a href="{{ $jobVacancy->company->website }}" target="_blank"
                  class="text-base font-semibold text-blue-400 hover:text-blue-300 transition-colors duration-200">{{ $jobVacancy->company->website }}</a>
              </div>

              <div class="pb-4 border-b border-gray-700">
                <p class="text-sm text-gray-500 mb-1">Location</p>
                <p class="text-base font-semibold text-gray-200">{{ $jobVacancy->location }}</p>
              </div>

              <div class="pb-4 border-b border-gray-700">
                <p class="text-sm text-gray-500 mb-1">Salary Range</p>
                <p class="text-base font-semibold text-emerald-400">${{ number_format($jobVacancy->salary) }} / year</p>
              </div>

              <div class="pb-4 border-b border-gray-700">
                <p class="text-sm text-gray-500 mb-1">Job Type</p>
                <span
                  class="inline-block px-3 py-1 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-full text-sm font-semibold">
                  {{ $jobVacancy->type }}
                </span>
              </div>

              <div>
                <p class="text-sm text-gray-500 mb-1">Category</p>
                <p class="text-base font-semibold text-gray-200">{{ $jobVacancy->jobCategory->name }}</p>
              </div>
            </div>

            <!-- Apply Button - Desktop -->
            <div class="mt-6 pt-6 border-t border-gray-700 hidden lg:block">
              <x-link :href="route('job-vacancies.apply', $jobVacancy)" variant="primary"
                class="w-full text-center justify-center text-base">
                Apply for this Position
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </x-link>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>
</x-app-layout>