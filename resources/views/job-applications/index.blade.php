<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-2xl text-white leading-tight">
      {{ __('My Job Applications') }}
    </h2>
  </x-slot>



  <div class="py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Applications List -->
      <div class="space-y-4">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-xl font-semibold text-white">Your Applications</h3>
          <span class="text-sm text-gray-400">{{ $jobApplications->total() }} applications</span>
        </div>

        @forelse ($jobApplications as $jobApplication)
          <div
            class="group p-6 bg-gray-800/50 border border-gray-700 hover:border-blue-500/50 rounded-xl backdrop-blur-sm transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/10">
            <div class="space-y-4">
              <!-- Top Section: Job Details & Status -->
              <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left: Job Details -->
                <div class="flex-1">
                  <div class="flex items-start gap-3 mb-4">
                    <div
                      class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                      {{ substr($jobApplication->jobVacancy->company->name, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                      <h4 class="text-xl font-semibold text-white mb-1">
                        {{ $jobApplication->jobVacancy->title }}
                      </h4>
                      <p class="text-gray-400">{{ $jobApplication->jobVacancy->company->name }}</p>
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
                      {{ $jobApplication->jobVacancy->location }}
                    </span>
                    <span class="flex items-center">
                      <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      ${{ number_format($jobApplication->jobVacancy->salary) }} / year
                    </span>
                    <span class="flex items-center">
                      <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      Applied {{ $jobApplication->created_at->diffForHumans() }}
                    </span>
                  </div>

                  <p class="text-gray-300 text-sm mb-4 line-clamp-2">
                    {{ $jobApplication->jobVacancy->description }}
                  </p>

                  <!-- Resume Info -->
                  <div class="flex items-center gap-2 text-sm text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Applied with: <span class="text-blue-400">{{ $jobApplication->resume->fileName }}</span></span>
                    <a href="{{ $jobApplication->resume->publicUrl}}" target="_blank"
                      class="text-blue-500 hover:text-blue-400 transition-colors ml-2 flex items-center gap-1">
                      View
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                      </svg>
                    </a>
                  </div>
                </div>

                <!-- Right: Status & Score -->
                <div class="lg:w-80 space-y-4">
                  <!-- Status Badge & Job Type -->
                  <div class="flex items-center gap-2">
                    @php
                      $statusColors = [
                        'Pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                        'Accepted' => 'bg-green-500/20 text-green-400 border-green-500/30',
                        'Rejected' => 'bg-red-500/20 text-red-400 border-red-500/30',
                      ];
                      $statusColor = $statusColors[$jobApplication->status] ?? $statusColors['Pending'];
                    @endphp
                    <span
                      class="flex-1 px-3 py-1.5 {{ $statusColor }} border rounded-full text-xs font-semibold text-center uppercase">
                      {{ $jobApplication->status }}
                    </span>
                    <span
                      class="px-3 py-1.5 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-full text-xs font-semibold whitespace-nowrap">
                      {{ $jobApplication->jobVacancy->type }}
                    </span>
                  </div>

                  <!-- AI Score -->
                  <div class="p-4 bg-gray-900/50 border border-gray-700 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                      <span class="text-sm text-gray-400">AI Match Score</span>
                      <span class="text-2xl font-bold text-white">{{ $jobApplication->aiGeneratedScore }}<span
                          class="text-sm text-gray-400">/100</span></span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                      @php
                        $scoreColor = $jobApplication->aiGeneratedScore >= 70 ? 'bg-green-500' : ($jobApplication->aiGeneratedScore >= 40 ? 'bg-yellow-500' : 'bg-red-500');
                      @endphp
                      <div class="{{ $scoreColor }} h-2 rounded-full transition-all duration-500"
                        style="width: {{ $jobApplication->aiGeneratedScore }}%"></div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Bottom Section: AI Feedback (Full Width) -->
              <div class="p-5 bg-gray-900/50 border border-gray-700 rounded-lg">
                <h5 class="text-base font-semibold text-white mb-3 flex items-center gap-2">
                  <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                  </svg>
                  AI Analysis & Feedback
                </h5>
                <p class="text-sm text-gray-300 leading-relaxed">
                  {{ $jobApplication->aiGeneratedFeedback }}
                </p>
              </div>
            </div>
          </div>
        @empty
          <div class="text-center py-12 px-4 bg-gray-800/30 border border-gray-700 rounded-xl">
            <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-400 mb-2">No applications yet</h3>
            <p class="text-gray-500 mb-4">Start applying to jobs to see your applications here</p>
            <a href="{{ route('dashboard') }}"
              class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200">
              Browse Jobs
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </a>
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      @if ($jobApplications->hasPages())
        <div class="mt-8">
          {{ $jobApplications->links() }}
        </div>
      @endif
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const startTime = sessionStorage.getItem('application_submission_start');
      if (startTime) {
        const endTime = Date.now();
        const duration = (endTime - startTime) / 1000;

        console.log('%c Performance Measurement ', 'background: #2563eb; color: #fff; font-weight: bold; padding: 2px 4px; border-radius: 4px;');
        console.log(`Total Application Time: %c${duration.toFixed(2)} seconds`, 'color: #2563eb; font-weight: bold;');

        // Clear it so it doesn't show again on refresh
        sessionStorage.removeItem('application_submission_start');
      }
    }, { once: true });
  </script>
</x-app-layout>
