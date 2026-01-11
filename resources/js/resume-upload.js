export default function resumeUpload() {
    return {
        // State - Initialize with null, not empty string
        fileName: '',
        fileSize: '',
        isDragging: false,
        hasError: false,
        errorMessage: '',
        resumeOption: '',
        selectedResumeId: null,
        isSubmitting: false,

        // Constants
        MAX_FILE_SIZE: 5 * 1024 * 1024, // 5MB
        ALLOWED_FILE_TYPE: 'application/pdf',

        // Form Submit Handler
        handleSubmit(event) {
            // 1. Guard against double submission (logic level)
            if (this.isSubmitting) return;

            // Validate that user has selected either existing resume or uploaded new one
            if (!this.selectedResumeId && !this.fileName) {
                this.setError('Please select an existing resume or upload a new one');
                return; // Stop form submission
            }

            // Set submitting state internally
            this.isSubmitting = true;

            // 2. Disable button and show loading state via DOM (security/separation level)
            const button = document.getElementById('submit-button');
            const spinner = document.getElementById('loading-spinner');
            const icon = document.getElementById('submit-icon');
            const text = document.getElementById('submit-text');

            if (button) button.disabled = true;
            if (spinner) spinner.classList.remove('hidden');
            if (icon) icon.classList.add('hidden');
            if (text) text.innerText = 'Submitting Application...';

            // Submit the form
            event.target.submit();
        },

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
