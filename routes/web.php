<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobApplicationsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobVacancyController;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;



Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth', 'role:Job-Seeker'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/job-applications', [JobApplicationsController::class, 'index'])->name('job-applications.index');

    Route::get('/job-vacancies/{jobVacancy}', [JobVacancyController::class, 'show'])->name('job-vacancies.show');

    Route::get('/job-vacancies/{jobVacancy}/apply', [JobVacancyController::class, 'apply'])->name('job-vacancies.apply');

    Route::post('/job-vacancies/{jobVacancy}/apply', [JobVacancyController::class, 'processApplication'])->name('job-vacancies.processApplication');

    // test open Ai
    Route::get('/testOpenAI', [JobVacancyController::class, 'testOpenAI'])->name('testOpenAI');

    // test gemini
    Route::get('/testGemini', [JobVacancyController::class, 'testGemini'])->name('testGemini');

    // list models of gemini
    Route::get('/listModels', [JobVacancyController::class, 'listModels'])->name('listModels');



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
