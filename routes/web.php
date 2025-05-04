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

Route::get('/login',  [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postlogin']);
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postregister']);

Route::middleware(['auth'])->group(function () {
    Route::get('/', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::prefix('admin')->name('admin.')->middleware(['auth.user:ADM'])->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
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
    
    Route::middleware(['auth.user:MHS,DSN'])->group(function () {
    });
});
