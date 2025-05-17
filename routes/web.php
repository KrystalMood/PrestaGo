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
            Route::get('/{id}/details', [UserController::class, 'show'])->name('details');
        });

        // Achievement Verification Routes
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/', function () {
                return view('admin.verification.index');
            })->name('index');
            Route::patch('/{id}/approve', function ($id) {
                return redirect()->back()->with('success', 'Prestasi berhasil disetujui');
            })->name('approve');
            Route::patch('/{id}/reject', function ($id) {
                return redirect()->back()->with('success', 'Prestasi berhasil ditolak');
            })->name('reject');
        });

        // Competition Management Routes
        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CompetitionController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\CompetitionController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\CompetitionController::class, 'store'])->name('store');
            Route::get('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'show'])->name('show');
            Route::get('/{competition}/edit', [App\Http\Controllers\Admin\CompetitionController::class, 'edit'])->name('edit');
            Route::put('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'update'])->name('update');
            Route::delete('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'destroy'])->name('destroy');
            Route::patch('/{competition}/toggle-verification', [App\Http\Controllers\Admin\CompetitionController::class, 'toggleVerification'])->name('toggle-verification');
            Route::get('/{competition}/participants', [App\Http\Controllers\Admin\CompetitionController::class, 'participants'])->name('participants');
        });

        // Period Management Routes
        Route::prefix('periods')->name('periods.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\PeriodController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\PeriodController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\PeriodController::class, 'store'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\Admin\PeriodController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\PeriodController::class, 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\PeriodController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\PeriodController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/toggle-active', [App\Http\Controllers\Admin\PeriodController::class, 'toggleActive'])->name('toggle-active');
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
            Route::patch('/{id}/toggle-active', [App\Http\Controllers\Admin\StudyProgramController::class, 'toggleActive'])->name('toggle-active');
        });

        // Recommendation Management Routes
        Route::prefix('recommendations')->name('recommendations.')->group(function () {
            Route::get('/', function () {
                return view('admin.recommendations.index');
            })->name('index');
        });

        // Report Management Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', function () {
                return view('admin.reports.index');
            })->name('index');
        });

        // Settings Management Routes
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', function () {
                return view('admin.settings.index');
            })->name('index');
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
            Route::get('/', function () {
                return view('Mahasiswa.achievements.index');
            })->name('index');
            Route::get('/create', function () {
                return view('Mahasiswa.achievements.create');
            })->name('create');
        });

        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', function () {
                return view('Mahasiswa.competitions.index');
            })->name('index');
            Route::get('/create', function () {
                return view('Mahasiswa.competitions.create');
            })->name('create');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', function () {
                return view('Mahasiswa.profile.index');
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
});
