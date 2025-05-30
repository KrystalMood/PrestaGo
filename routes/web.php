<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postlogin']);
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postregister']);
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware(['auth.user:ADM'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'adminDashboard'])->name('dashboard');

        // User Management Routes
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/show', [UserController::class, 'show'])->name('show');
            Route::get('/{id}/details', [UserController::class, 'getDetails'])->name('details');
            Route::get('/export', [UserController::class, 'export'])->name('export');
        });

        // Achievement Verification Routes
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\VerificationController@index')->name('index');
            Route::get('/{id}', 'App\Http\Controllers\Admin\VerificationController@show')->name('show');
            Route::put('/{id}', 'App\Http\Controllers\Admin\VerificationController@update')->name('update');
        });

        // Competition Management Routes
        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CompetitionController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\CompetitionController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\CompetitionController::class, 'store'])->name('store');
            Route::get('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'show'])->name('show');
            Route::put('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'update'])->name('update');
            Route::delete('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'destroy'])->name('destroy');
            Route::patch('/{competition}/toggle-verification', [App\Http\Controllers\Admin\CompetitionController::class, 'toggleVerification'])->name('toggle-verification');
            Route::get('/{competition}/participants', [App\Http\Controllers\Admin\CompetitionController::class, 'participants'])->name('participants');
            Route::post('/{competition}/participants', [App\Http\Controllers\Admin\CompetitionController::class, 'addParticipant'])->name('participants.store');
            Route::delete('/{competition}/participants/{participant}', [App\Http\Controllers\Admin\CompetitionController::class, 'removeParticipant'])->name('participants.destroy');
            
            // Sub-competition routes
            Route::get('/{competition}/sub-competitions', [App\Http\Controllers\Admin\SubCompetitionController::class, 'index'])->name('sub-competitions.index');
            Route::post('/{competition}/sub-competitions', [App\Http\Controllers\Admin\SubCompetitionController::class, 'store'])->name('sub-competitions.store');
            Route::get('/{competition}/sub-competitions/{sub_competition}', [App\Http\Controllers\Admin\SubCompetitionController::class, 'show'])->name('sub-competitions.show');
            Route::put('/{competition}/sub-competitions/{sub_competition}', [App\Http\Controllers\Admin\SubCompetitionController::class, 'update'])->name('sub-competitions.update');
            Route::delete('/{competition}/sub-competitions/{sub_competition}', [App\Http\Controllers\Admin\SubCompetitionController::class, 'destroy'])->name('sub-competitions.destroy');
            Route::get('/{competition}/sub-competitions/{sub_competition}/participants', [App\Http\Controllers\Admin\SubCompetitionController::class, 'participants'])->name('sub-competitions.participants');
            Route::post('/{competition}/sub-competitions/{sub_competition}/participants', [App\Http\Controllers\Admin\SubCompetitionController::class, 'addParticipant'])->name('sub-competitions.participants.store');
            Route::delete('/{competition}/sub-competitions/{sub_competition}/participants/{participant}', [App\Http\Controllers\Admin\SubCompetitionController::class, 'removeParticipant'])->name('sub-competitions.participants.destroy');
        });

        // Period Management Routes
        Route::prefix('periods')->name('periods.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\PeriodController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\PeriodController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\PeriodController::class, 'store'])->name('store');
            Route::get('/{id}/json', [App\Http\Controllers\Admin\PeriodController::class, 'show'])->name('show.json');
            Route::get('/export', [App\Http\Controllers\Admin\PeriodController::class, 'export'])->name('export');
            Route::get('/{id}', [App\Http\Controllers\Admin\PeriodController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\PeriodController::class, 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\PeriodController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\PeriodController::class, 'destroy'])->name('destroy');
        });

        // Program Management Routes
        Route::prefix('programs')->name('programs.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\StudyProgramController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\StudyProgramController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\StudyProgramController::class, 'store'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\Admin\StudyProgramController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\StudyProgramController::class, 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\StudyProgramController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\StudyProgramController::class, 'destroy'])->name('destroy');
        });

        // Recommendation Management Routes
        Route::prefix('recommendations')->name('recommendations.')->group(function () {
           Route::get('/', [App\Http\Controllers\Admin\RecommendationController::class, 'index'])->name('index');
           Route::get('/create', [App\Http\Controllers\Admin\RecommendationController::class, 'create'])->name('create');
           Route::post('/', [App\Http\Controllers\Admin\RecommendationController::class, 'store'])->name('store');
           Route::get('/automatic', [App\Http\Controllers\Admin\RecommendationController::class, 'automatic'])->name('automatic');
           Route::post('/generate', [App\Http\Controllers\Admin\RecommendationController::class, 'generate'])->name('generate');
           Route::post('/save-generated', [App\Http\Controllers\Admin\RecommendationController::class, 'saveGenerated'])->name('save-generated');
           Route::post('/save-all-generated', [App\Http\Controllers\Admin\RecommendationController::class, 'saveAllGenerated'])->name('save-all-generated');
           Route::get('/export', [App\Http\Controllers\Admin\RecommendationController::class, 'export'])->name('export');
           Route::get('/{id}', [App\Http\Controllers\Admin\RecommendationController::class, 'show'])->name('show');
           Route::patch('/{id}/status', [App\Http\Controllers\Admin\RecommendationController::class, 'updateStatus'])->name('update-status');
        });

        // Report Management Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', function () {
                return view('admin.reports.index');
            })->name('index');
        });

        // Settings Management Routes
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
            Route::get('/general', [App\Http\Controllers\Admin\SettingsController::class, 'general'])->name('general');
            Route::post('/general', [App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('general.update');
            Route::get('/email', [App\Http\Controllers\Admin\SettingsController::class, 'email'])->name('email');
            Route::get('/security', [App\Http\Controllers\Admin\SettingsController::class, 'security'])->name('security');
            Route::get('/display', [App\Http\Controllers\Admin\SettingsController::class, 'display'])->name('display');
        });

        // Achievement Verification Routes
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\AchievementVerificationController@index')->name('index');
            Route::get('/{id}', 'App\Http\Controllers\Admin\AchievementVerificationController@show')->name('show');
            Route::post('/{id}/approve', 'App\Http\Controllers\Admin\AchievementVerificationController@approve')->name('approve');
            Route::post('/{id}/reject', 'App\Http\Controllers\Admin\AchievementVerificationController@reject')->name('reject');
            Route::get('/attachment/{id}/download', 'App\Http\Controllers\Admin\AchievementVerificationController@downloadAttachment')->name('download');
            Route::get('/export', 'App\Http\Controllers\Admin\AchievementVerificationController@export')->name('export');
        });
    });

    // Student routes
    Route::prefix('student')->name('Mahasiswa.')->middleware(['auth.user:MHS'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'studentDashboard'])->name('dashboard');

        Route::prefix('achievements')->name('achievements.')->group(function () {
            Route::get('/', [App\Http\Controllers\mahasiswa\AchievementController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\mahasiswa\AchievementController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\mahasiswa\AchievementController::class, 'store'])->name('store');
            Route::get('/show/{id}', [App\Http\Controllers\mahasiswa\AchievementController::class, 'show'])->name('show');
            Route::get('/edit/{id}', [App\Http\Controllers\mahasiswa\AchievementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\mahasiswa\AchievementController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\mahasiswa\AchievementController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', [App\Http\Controllers\mahasiswa\CompetitionContoller::class, 'index'])->name('index');
            Route::get('/show/{competition}', [App\Http\Controllers\mahasiswa\CompetitionContoller::class, 'show'])->name('show');

            Route::get('/create', function () {
                return view('Mahasiswa.competitions.create');
            })->name('create');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', function () {
                return view('Mahasiswa.profile.index');
            })->name('index');
        });
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', function () {
                return view('Mahasiswa.settings.index');
            })->name('index');
        });
    });

    // Lecturer routes
    Route::prefix('lecturer')->name('lecturer.')->middleware(['auth.user:DSN'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'lecturerDashboard'])->name('dashboard');

        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', function () {
                return view('Dosen.students.index');
            })->name('index');
        });

        Route::prefix('recommendations')->name('recommendations.')->group(function () {
            Route::get('/', function () {
                return view('Dosen.recommendations.index');
            })->name('index');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', function () {
                return view('Dosen.profile.index');
            })->name('index');
        });
    });

    // Admin Reports Routes
    Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
        Route::get('/achievements', [App\Http\Controllers\Admin\ReportController::class, 'achievements'])->name('achievements');
        Route::get('/programs', [App\Http\Controllers\Admin\ReportController::class, 'programs'])->name('programs');
        Route::get('/trends', [App\Http\Controllers\Admin\ReportController::class, 'trends'])->name('trends');
        Route::get('/periods', [App\Http\Controllers\Admin\ReportController::class, 'periods'])->name('periods');
        Route::get('/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('export');
        Route::post('/generate-report', [App\Http\Controllers\Admin\ReportController::class, 'generateReport'])->name('generate-report');
    });
});
