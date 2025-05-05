<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postlogin']);
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postregister']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        $user = auth()->user();
        $role = $user->getRole();

        switch ($role) {
            case 'ADM':
                return redirect()->route('admin.dashboard');
            case 'MHS':
                return redirect()->route('student.dashboard');
            case 'DSN':
                return redirect()->route('lecturer.dashboard');
            default:
                return redirect()->route('login')
                    ->with('error', 'Tidak dapat menentukan hak akses Anda. Silakan hubungi administrator.');
        }
    })->name('home');

    Route::prefix('admin')->name('admin.')->middleware(['auth.user:ADM'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'adminDashboard'])->name('dashboard');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', function () {
                return view('admin.users.index');
            })->name('index');
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
        Route::get('/dashboard', [AuthController::class, 'studentDashboard'])->name('dashboard');

        Route::prefix('achievements')->name('achievements.')->group(function () {
            Route::get('/', function () {
                return view('student.achievements.index');
            })->name('index');
        });

        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', function () {
                return view('student.competitions.index');
            })->name('index');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', function () {
                return view('student.profile.index');
            })->name('index');
        });
    });

    // Lecturer routes
    Route::prefix('lecturer')->name('lecturer.')->middleware(['auth.user:DSN'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'lecturerDashboard'])->name('dashboard');

        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', function () {
                return view('lecturer.students.index');
            })->name('index');
        });

        Route::prefix('recommendations')->name('recommendations.')->group(function () {
            Route::get('/', function () {
                return view('lecturer.recommendations.index');
            })->name('index');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', function () {
                return view('lecturer.profile.index');
            })->name('index');
        });
    });
});
