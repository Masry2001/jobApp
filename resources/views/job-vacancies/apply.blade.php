<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-2xl text-white leading-tight">
        Apply for Position
      </h2>
    </div>
  </x-slot>

  <div class="py-8 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Back button -->
      <div class="mb-6">
        <x-link :href="route('job-vacancies.show', $jobVacancy)" variant="default" class="inline-flex">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to Job Details
        </x-link>
      </div>

      <!-- Job Summary Card -->
      <div class="mb-6 p-6 bg-gray-800/50 border border-gray-700 rounded-xl backdrop-blur-sm">
        <div class="flex items-start gap-4">
          <div
            class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-lg">
            {{ substr($jobVacancy->company->name, 0, 2) }}
          </div>
          <div class="flex-1 min-w-0">
            <a href="{{ route('job-vacancies.show', $jobVacancy) }}"
              class="text-xl font-bold text-white hover:text-blue-400 transition-colors duration-200">
              {{ $jobVacancy->title }}
            </a>
            <p class="text-gray-400 mt-1 mb-3">{{ $jobVacancy->company->name }}</p>

            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
              <span class="flex items-center text-gray-400">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ $jobVacancy->location }}
              </span>
              <span class="flex items-center text-emerald-400">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                ${{ number_format($jobVacancy->salary) }} / year
              </span>
              <span
                class="px-3 py-1 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-full text-xs font-semibold">
                {{ $jobVacancy->type }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Application Form -->
      <div class="p-6 sm:p-8 bg-gray-800/50 border border-gray-700 rounded-xl backdrop-blur-sm">
        <div class="mb-6">
          <h3 class="text-2xl font-bold text-white mb-2">Submit Your Application</h3>
          <p class="text-gray-400">Please select an existing resume or upload a new one to complete your application.
          </p>
        </div>

        <form action="{{ route('job-vacancies.processApplication', $jobVacancy) }}" method="POST"
          enctype="multipart/form-data" class="space-y-6" x-data="resumeUpload()">
          @csrf

          <!-- Hidden inputs for resume selection -->
          <input type="hidden" name="resume_option" x-model="resumeOption">
          <input type="hidden" name="existing_resume" x-model="selectedResumeId">

          <!-- Resume Selection Section -->
          <div class="p-4 bg-gray-900/30 border border-gray-700 rounded-lg">
            <h4 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
              <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Saved Resumes
            </h4>

            @if($resumes->count() > 0)
              <div class="space-y-2">
                @foreach($resumes as $resume)
                  <label @click="selectExistingResume('{{ $resume->id }}')"
                    :class="selectedResumeId === '{{ $resume->id }}' ? 'bg-blue-500/20 border-blue-500' : 'bg-gray-800/50 border-gray-700 hover:bg-gray-800'"
                    class="flex items-center gap-3 p-3 rounded-lg cursor-pointer transition-all duration-200 border-2">

                    <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0"
                      :class="selectedResumeId === '{{ $resume->id }}' ? 'border-blue-500' : 'border-gray-600'">

                      <div x-show="selectedResumeId === '{{ $resume->id }}'" class="w-2 h-2 rounded-full bg-blue-500"></div>
                    </div>

                    <div class="flex-1">
                      <p class="text-white font-medium">{{ $resume->fileName }}</p>
                      <p class="text-sm text-gray-400">Uploaded {{ $resume->created_at->diffForHumans() }}</p>
                    </div>

                    <div x-show="selectedResumeId === '{{ $resume->id }}'" x-cloak>
                      <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd" />
                      </svg>
                    </div>
                  </label>
                @endforeach
              </div>
            @else
              <p class="text-sm text-gray-500">You haven't uploaded any resumes yet.</p>
            @endif
          </div>

          <!-- Divider with Error Display -->
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-4 bg-gray-800 text-gray-400 font-medium">OR</span>
            </div>
          </div>

          <!-- Validation Error Display (between sections) -->
          @if($errors->has('resume_option') || $errors->has('new_resume') || $errors->has('existing_resume'))
            <div class="flex items-start gap-2 p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
              <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                  clip-rule="evenodd" />
              </svg>
              <div class="flex-1">
                <p class="text-sm font-semibold text-red-400 mb-1">Please correct the following errors:</p>
                <ul class="text-sm text-red-400 list-disc list-inside space-y-1">
                  @if($errors->has('resume_option'))
                    <li>{{ $errors->first('resume_option') }}</li>
                  @endif
                  @if($errors->has('new_resume'))
                    <li>{{ $errors->first('new_resume') }}</li>
                  @endif
                  @if($errors->has('existing_resume'))
                    <li>{{ $errors->first('existing_resume') }}</li>
                  @endif
                </ul>
              </div>
            </div>
          @endif

          <!-- Upload New Resume -->
          <div>
            <div class="space-y-4">
              <h4 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Upload New Resume
              </h4>

              <!-- Drop Zone -->
              <div @dragover.prevent="handleDragOver" @dragleave.prevent="handleDragLeave" @drop.prevent="handleDrop"
                :class="getDropZoneClasses()"
                class="relative border-2 border-dashed rounded-xl p-8 transition-all duration-300">

                <!-- Hidden File Input -->
                <input x-ref="fileInput" @change="handleFileSelect" name="new_resume" type="file" accept=".pdf"
                  class="hidden" />

                <!-- Upload Icon & Text (shown when no file) -->
                <div x-show="!fileName" @click="openFileDialog()" class="text-center cursor-pointer">
                  <div
                    class="mx-auto w-16 h-16 rounded-full bg-gradient-to-br from-blue-600/20 to-purple-600/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                  </div>
                  <p class="text-lg font-semibold text-white mb-2">
                    <span class="text-blue-400">Click to upload</span> or drag and drop
                  </p>
                  <p class="text-sm text-gray-400">PDF files only (max 5MB)</p>
                </div>

                <!-- File Preview (shown when file selected) -->
                <div x-show="fileName" x-cloak class="flex items-center justify-between">
                  <div class="flex items-center gap-4">
                    <div
                      class="w-12 h-12 rounded-lg bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center flex-shrink-0">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                    </div>
                    <div>
                      <p class="text-white font-semibold" x-text="fileName"></p>
                      <p class="text-sm text-gray-400" x-text="fileSize"></p>
                    </div>
                  </div>
                  <button @click="clearFile()" type="button"
                    class="p-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors duration-200 z-20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Client-side Validation Error -->
              <div x-show="hasError" x-cloak
                class="flex items-start gap-2 p-3 bg-red-500/10 border border-red-500/30 rounded-lg">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
                </svg>
                <p class="text-sm text-red-400" x-text="errorMessage"></p>
              </div>

              <!-- Info Box -->
              <div class="flex items-start gap-3 p-4 bg-blue-500/5 border border-blue-500/20 rounded-lg">
                <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm text-blue-300">
                  <p class="font-semibold mb-1">Resume Guidelines:</p>
                  <ul class="list-disc list-inside space-y-1 text-blue-300/80">
                    <li>Make sure your resume is up-to-date</li>
                    <li>Include relevant work experience and skills</li>
                    <li>Use a professional format</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="pt-4">
            <x-primary-button type="submit" class="w-full text-base py-4">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Submit Application
            </x-primary-button>
          </div>
        </form>
      </div>

    </div>
  </div>
</x-app-layout>

<script>
  function resumeUpload() {
    return {
      // State - Initialize with null, not empty string
      fileName: '',
      fileSize: '',
      isDragging: false,
      hasError: false,
      errorMessage: '',
      resumeOption: '',
      selectedResumeId: null, // ← Important: null, not empty string

      // Constants
      MAX_FILE_SIZE: 5 * 1024 * 1024, // 5MB
      ALLOWED_FILE_TYPE: 'application/pdf',

      // Select Existing Resume
      selectExistingResume(resumeId) {
        // Clear any uploaded file
        this.clearFile();

        // Set the selected resume
        this.selectedResumeId = resumeId;
        this.resumeOption = 'existing_resume';

        // Clear any errors
        this.clearError();
      },

      // File Selection Handler
      handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        // When file is selected, switch to new_resume option
        this.selectNewResume();
        this.processFile(file, event.target);
      },

      // Select New Resume Option
      selectNewResume() {
        this.selectedResumeId = null;
        this.resumeOption = 'new_resume';
      },

      // Process and Validate File
      processFile(file, inputElement) {
        this.setFileInfo(file);
        this.clearError();

        const validation = this.validateFile(file);

        if (!validation.isValid) {
          this.setError(validation.errorMessage);
          this.resetFileInput(inputElement);
          this.resumeOption = ''; // Reset option if file is invalid
        }
      },

      // File Validation
      validateFile(file) {
        if (file.size > this.MAX_FILE_SIZE) {
          return {
            isValid: false,
            errorMessage: 'File size must be less than 5MB'
          };
        }

        if (file.type !== this.ALLOWED_FILE_TYPE) {
          return {
            isValid: false,
            errorMessage: 'Only PDF files are allowed'
          };
        }

        return { isValid: true };
      },

      // Set File Information
      setFileInfo(file) {
        this.fileName = file.name;
        this.fileSize = this.formatFileSize(file.size);
      },

      // Format File Size
      formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
      },

      // Drag and Drop Handlers
      handleDragOver() {
        this.isDragging = true;
      },

      handleDragLeave() {
        this.isDragging = false;
      },

      handleDrop(event) {
        this.isDragging = false;

        const file = event.dataTransfer.files[0];
        if (!file) return;

        // When file is dropped, switch to new_resume option
        this.selectNewResume();
        this.assignFileToInput(file);
      },

      // Assign Dropped File to Input
      assignFileToInput(file) {
        const fileInput = this.$refs.fileInput;
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        this.processFile(file, fileInput);
      },

      // Open File Dialog
      openFileDialog() {
        this.$refs.fileInput.click();
      },

      // Clear File
      clearFile() {
        this.resetState();
        this.resetFileInput(this.$refs.fileInput);

        // If we're clearing the file and no existing resume is selected, clear the option
        if (!this.selectedResumeId) {
          this.resumeOption = '';
        }
      },

      // Reset Component State
      resetState() {
        this.fileName = '';
        this.fileSize = '';
        this.clearError();
      },

      // Reset File Input Element
      resetFileInput(inputElement) {
        if (inputElement) {
          inputElement.value = '';
        }
        this.fileName = '';
        this.fileSize = '';
      },

      // Error Management
      setError(message) {
        this.hasError = true;
        this.errorMessage = message;
      },

      clearError() {
        this.hasError = false;
        this.errorMessage = '';
      },

      // Get Drop Zone CSS Classes
      getDropZoneClasses() {
        if (this.hasError) {
          return 'border-red-500 bg-red-500/5';
        }

        if (this.fileName && !this.hasError) {
          return 'border-green-500 bg-green-500/5';
        }

        if (this.isDragging) {
          return 'border-blue-500 bg-blue-500/5';
        }

        return 'border-gray-600 bg-gray-900/30';
      }
    };
  }
</script>