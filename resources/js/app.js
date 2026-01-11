import './bootstrap';

import Alpine from 'alpinejs';
import resumeUpload from './resume-upload';

window.Alpine = Alpine;
Alpine.data('resumeUpload', resumeUpload);

Alpine.start();
