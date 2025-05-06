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



    Route::prefix('admin')->name('admin.')->middleware(['auth.user:ADM'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'adminDashboard'])->name('dashboard');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('achievements')->name('achievements.')->group(function () {
            Route::get('/', function () {
                return view('admin.achievements.index');
            })->name('index');
        });

        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', function () {
                return view('admin.competitions.index');
            })->name('index');

            Route::get('/create', function () {
                return view('admin.competitions.create');
            })->name('create');
        });

        Route::prefix('periods')->name('periods.')->group(function () {
            Route::get('/', function () {
                return view('admin.periods.index');
            })->name('index');
        });

        Route::prefix('programs')->name('programs.')->group(function () {
            Route::get('/', function () {
                return view('admin.programs.index');
            })->name('index');
        });

        Route::prefix('recommendations')->name('recommendations.')->group(function () {
            Route::get('/', function () {
                return view('admin.recommendations.index');
            })->name('index');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', function () {
                return view('admin.reports.index');
            })->name('index');
        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', function () {
                return view('admin.settings.index');
            })->name('index');
        });
    });

    // Student routes
    Route::prefix('student')->name('student.')->middleware(['auth.user:MHS'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'studentDashboard'])->name('Mahasiswa.dashboard');

        Route::prefix('achievements')->name('achievements.')->group(function () {
            Route::get('/', function () {
                return view('Mahasiswa.achievements.index');
            })->name('index');
        });

        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', function () {
                return view('Mahasiswa.competitions.index');
            })->name('index');
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
