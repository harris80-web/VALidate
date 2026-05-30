<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionCategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Guest-only admin routes
        Route::middleware('guest')->group(function () {
            Route::get('/login', [AdminController::class, 'login'])->name('login');
            Route::post('/authenticate', [AdminController::class, 'authenticate'])->name('authenticate');
        });

        // Authenticated admin routes
        Route::middleware('auth')->group(function () {
            Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

            Route::prefix('profile')->group(function () {
                Route::get('/', [AdminController::class, 'profile'])->name('profile');
                Route::post('/update', [AdminController::class, 'profileUpdate'])->name('profile-update');
            });

            Route::get('/surveyContent', [AdminController::class, 'surveyContent'])->name('survey-content');
            Route::post('/surveyContent/categories', [QuestionCategoryController::class, 'store'])
                ->name('survey-content.categories.store');
            Route::delete('/surveyContent/categories/{id}', [QuestionCategoryController::class, 'destroy'])
                ->name('survey-content.categories.destroy');
            Route::post('/surveyContent/questions', [QuestionController::class, 'store'])
                ->name('survey-content.questions.store');
                Route::put('/surveyContent/questions/{id}', [QuestionController::class, 'update'])
                    ->name('survey-content.questions.update');
            Route::delete('/surveyContent/questions/{id}', [QuestionController::class, 'destroy'])
                ->name('survey-content.questions.destroy');
            // Admin management CRUD
            Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('admins.store');
            Route::put('/admins/{id}', [AdminController::class, 'updateAdmin'])->name('admins.update');
            Route::delete('/admins/{id}', [AdminController::class, 'destroyAdmin'])->name('admins.destroy');
            Route::get('/admins/{id}', [AdminController::class, 'showAdmin'])->name('admins.show');
            Route::get('/user-management', [AdminController::class, 'userManagement'])->name('user-management');
            Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
            Route::get('/report', [AdminController::class, 'report'])->name('report');
            Route::get('/report-generate', [AdminController::class, 'generateReport'])->name('report.generate');
        });
    });


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::name('user.')
    ->group(function () {

        Route::get('/', [UserController::class, 'index'])->name('index');

        Route::prefix('survey')
            ->group(function () {
                Route::get('/consent', [UserController::class, 'surveyConsent'])->name('survey-consent');
                Route::get('/start', [UserController::class, 'surveyStart'])->name('survey-start');
                Route::post('/start', [UserController::class, 'surveyStartSubmit'])->name('submit-survey-start');

                Route::get('/citizens-charter', [UserController::class, 'citizensCharter'])->name('citizens-charter');
                Route::post('/submit-citizens-charter', [UserController::class, 'citizensCharterSubmit'])->name('submit-citizens-charter');
                Route::get('/service-quality', [UserController::class, 'serviceQuality'])->name('service-quality');
                Route::post('/submit-service-quality', [UserController::class, 'serviceQualityDimensionSubmit'])->name('submit-service-quality');

                Route::get('/suggestion', [UserController::class, 'suggestion'])->name('suggestion');
                Route::post('/submit-suggestion', [UserController::class, 'suggestionSubmit'])->name('submit-suggestion');
                Route::post('/store', [UserController::class, 'store'])->name('store');
                Route::get('/finished', [UserController::class, 'finished'])->name('finished');
            });
    });
