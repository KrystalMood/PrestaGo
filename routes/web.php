<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
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
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');

// Debug routes
Route::get('/debug/skills', [App\Http\Controllers\Student\ProfileController::class, 'getSkills']);
Route::get('/api/interest-areas', [App\Http\Controllers\Student\ProfileController::class, 'getInterestAreas']);

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

        // Competition Management Routes
        Route::get('/update-competition-statuses', [App\Http\Controllers\Admin\CompetitionStatusController::class, 'updateStatuses'])->name('update-competition-statuses');
        
        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CompetitionController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\AuthController::class, 'redirectToCompetitionsWithCreateModal'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\CompetitionController::class, 'store'])->name('store');
            Route::get('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'show'])->name('show');
            Route::put('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'update'])->name('update');
            Route::delete('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'destroy'])->name('destroy');
            Route::patch('/{competition}/toggle-verification', [App\Http\Controllers\Admin\CompetitionController::class, 'toggleVerification'])->name('toggle-verification');
            
            // Sub-competition routes
            Route::get('/{competition}/sub-competitions', [App\Http\Controllers\Admin\SubCompetitionController::class, 'index'])->name('sub-competitions.index');
            Route::post('/{competition}/sub-competitions', [App\Http\Controllers\Admin\SubCompetitionController::class, 'store'])->name('sub-competitions.store');
            Route::get('/{competition}/sub-competitions/{sub_competition}', [App\Http\Controllers\Admin\SubCompetitionController::class, 'show'])->name('sub-competitions.show');
            Route::put('/{competition}/sub-competitions/{sub_competition}', [App\Http\Controllers\Admin\SubCompetitionController::class, 'update'])->name('sub-competitions.update');
            Route::delete('/{competition}/sub-competitions/{sub_competition}', [App\Http\Controllers\Admin\SubCompetitionController::class, 'destroy'])->name('sub-competitions.destroy');
            Route::get('/{competition}/sub-competitions/{sub_competition}/participants', [App\Http\Controllers\Admin\SubCompetitionController::class, 'participants'])->name('sub-competitions.participants');
            Route::post('/{competition}/sub-competitions/{sub_competition}/participants', [App\Http\Controllers\Admin\SubCompetitionController::class, 'addParticipant'])->name('sub-competitions.participants.store');
            Route::get('/{competition}/sub-competitions/{sub_competition}/participants/{participant}/ajax', [App\Http\Controllers\Admin\SubCompetitionController::class, 'showParticipantAjax'])->name('sub-competitions.participants.show.ajax');
            Route::put('/{competition}/sub-competitions/{sub_competition}/participants/{participant}', [App\Http\Controllers\Admin\SubCompetitionController::class, 'updateParticipant'])->name('sub-competitions.participants.update');
            Route::delete('/{competition}/sub-competitions/{sub_competition}/participants/{participant}', [App\Http\Controllers\Admin\SubCompetitionController::class, 'removeParticipant'])->name('sub-competitions.participants.destroy');
            
            // Sub-competition skills routes
            Route::get('/{competition}/sub-competitions/{sub_competition}/skills/available', [App\Http\Controllers\Admin\SubCompetitionSkillController::class, 'getAvailableSkills'])->name('sub-competitions.skills.available');
            Route::get('/{competition}/sub-competitions/{sub_competition}/skills', [App\Http\Controllers\Admin\SubCompetitionSkillController::class, 'index'])->name('sub-competitions.skills');
            Route::post('/{competition}/sub-competitions/{sub_competition}/skills', [App\Http\Controllers\Admin\SubCompetitionSkillController::class, 'store'])->name('sub-competitions.skills.store');
            Route::put('/{competition}/sub-competitions/{sub_competition}/skills/{skill}', [App\Http\Controllers\Admin\SubCompetitionSkillController::class, 'update'])->name('sub-competitions.skills.update');
            Route::delete('/{competition}/sub-competitions/{sub_competition}/skills/{skill}', [App\Http\Controllers\Admin\SubCompetitionSkillController::class, 'destroy'])->name('sub-competitions.skills.destroy');
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
            Route::get('/{id}/json', [App\Http\Controllers\Admin\StudyProgramController::class, 'getJson'])->name('json');
        });

        // Recommendation Management Routes
        Route::prefix('recommendations')->name('recommendations.')->group(function () {
           Route::get('/', [App\Http\Controllers\Admin\RecommendationController::class, 'index'])->name('index');
           Route::get('/automatic', [App\Http\Controllers\Admin\RecommendationController::class, 'automatic'])->name('automatic');
           Route::post('/generate', [App\Http\Controllers\Admin\RecommendationController::class, 'generate'])->name('generate');
           Route::post('/save-generated', [App\Http\Controllers\Admin\RecommendationController::class, 'saveGenerated'])->name('save-generated');
           Route::post('/save-all-generated', [App\Http\Controllers\Admin\RecommendationController::class, 'saveAllGenerated'])->name('save-all-generated');
           Route::post('/remove-generated', [App\Http\Controllers\Admin\RecommendationController::class, 'removeGenerated'])->name('remove-generated');
           Route::post('/clear-generated', [App\Http\Controllers\Admin\RecommendationController::class, 'clearGenerated'])->name('clear-generated');
           Route::post('/delete-all', [App\Http\Controllers\Admin\RecommendationController::class, 'deleteAll'])->name('delete-all');
           Route::get('/export', [App\Http\Controllers\Admin\RecommendationController::class, 'export'])->name('export');
           Route::get('/{id}', [App\Http\Controllers\Admin\RecommendationController::class, 'show'])->name('show');
           Route::patch('/{id}/status', [App\Http\Controllers\Admin\RecommendationController::class, 'updateStatus'])->name('update-status');
           Route::delete('/{id}', [App\Http\Controllers\Admin\RecommendationController::class, 'destroy'])->name('destroy');
        });

        // Report Management Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/achievements', [ReportController::class, 'achievements'])->name('achievements');
            Route::get('/programs', [ReportController::class, 'programs'])->name('programs');
            Route::get('/trends', [ReportController::class, 'trends'])->name('trends');
            Route::get('/periods', [ReportController::class, 'periods'])->name('periods');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
            Route::post('/generate', [ReportController::class, 'generateReport'])->name('generate-report');
        });

        // Settings Management Routes
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
            Route::post('/security', [App\Http\Controllers\Admin\SettingsController::class, 'updateSecurity'])->name('security.update');
            Route::post('/change-password', [App\Http\Controllers\Admin\SettingsController::class, 'changePassword'])->name('change-password');
        });

        // Achievement Verification Routes
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/', 'App\Http\Controllers\Admin\AchievementVerificationController@index')->name('index');
            Route::get('/{id}', 'App\Http\Controllers\Admin\AchievementVerificationController@show')->name('show');
            Route::post('/{id}/approve', 'App\Http\Controllers\Admin\AchievementVerificationController@approve')->name('approve');
            Route::post('/{id}/reject', 'App\Http\Controllers\Admin\AchievementVerificationController@reject')->name('reject');
            Route::put('/{id}', 'App\Http\Controllers\Admin\AchievementVerificationController@update')->name('update');
            Route::get('/attachment/{id}/download', 'App\Http\Controllers\Admin\AchievementVerificationController@downloadAttachment')->name('download');
            Route::get('/export', 'App\Http\Controllers\Admin\AchievementVerificationController@export')->name('export');
        });
    });

    // Student routes
    Route::prefix('student')->name('student.')->middleware(['auth.user:MHS'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'studentDashboard'])->name('dashboard');

        Route::prefix('achievements')->name('achievements.')->group(function () {
            Route::get('/', 'App\Http\Controllers\Student\AchievementController@index')->name('index');
            Route::get('/create', 'App\Http\Controllers\Student\AchievementController@create')->name('create');
            Route::post('/', 'App\Http\Controllers\Student\AchievementController@store')->name('store');
            Route::get('/{id}/edit', 'App\Http\Controllers\Student\AchievementController@edit')->name('edit');
            Route::put('/{id}', 'App\Http\Controllers\Student\AchievementController@update')->name('update');
            Route::delete('/{id}', 'App\Http\Controllers\Student\AchievementController@destroy')->name('destroy');
            Route::get('/{id}', 'App\Http\Controllers\Student\AchievementController@show')->name('show');
            Route::get('/{id}/details', 'App\Http\Controllers\Student\AchievementController@getDetails')->name('details');
        });

        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', 'App\Http\Controllers\Student\CompetitionController@index')->name('index');
            Route::get('/{id}', 'App\Http\Controllers\Student\CompetitionController@show')->name('show');
            
            // Sub-competition routes
            Route::prefix('/{competitionId}/sub')->name('sub.')->group(function () {
                Route::get('/{subCompetitionId}', 'App\Http\Controllers\Student\CompetitionController@showSubCompetition')->name('show');
                Route::match(['get', 'post'], '/{subCompetitionId}/apply', 'App\Http\Controllers\Student\CompetitionController@applySubCompetition')->name('apply');
            });
        });

        // API for dropdown options
        Route::get('/api/competitions/list', 'App\Http\Controllers\Student\CompetitionController@getCompetitionsList')->name('api.competitions.list');

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [App\Http\Controllers\Student\ProfileController::class, 'index'])->name('index');
            Route::post('/update', [App\Http\Controllers\Student\ProfileController::class, 'update'])->name('update');
            Route::post('/skills', [App\Http\Controllers\Student\ProfileController::class, 'updateSkills'])->name('skills.update');
            Route::post('/interests', [App\Http\Controllers\Student\ProfileController::class, 'updateInterests'])->name('interests.update');
        });

        // Competition feedback routes
        Route::prefix('feedback')->name('feedback.')->group(function () {
            Route::get('/', [App\Http\Controllers\Student\CompetitionFeedbackController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\Student\CompetitionFeedbackController::class, 'store'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\Student\CompetitionFeedbackController::class, 'show'])->name('show');
            Route::delete('/{id}', [App\Http\Controllers\Student\CompetitionFeedbackController::class, 'destroy'])->name('destroy');
            
            Route::get('/list', [App\Http\Controllers\Student\CompetitionFeedbackController::class, 'list'])->name('list');
            
            Route::post('/check-eligibility', [App\Http\Controllers\Student\CompetitionFeedbackController::class, 'checkFeedbackEligibility'])->name('check-eligibility');
        });
        
        // Get competition details including lecturer mentorships
        Route::post('/competition-details', [App\Http\Controllers\Student\CompetitionFeedbackController::class, 'getCompetitionDetails'])->name('competition-details');

        // Lecturer Rating Routes
        Route::prefix('lecturer-ratings')->name('lecturer-ratings.')->group(function () {
            Route::post('/', [App\Http\Controllers\Student\LecturerRatingController::class, 'store'])->name('store');
            Route::get('/competition/{competitionId}', [App\Http\Controllers\Student\LecturerRatingController::class, 'getLecturersForCompetition'])->name('get-lecturers');
            Route::get('/competition/{competitionId}/lecturer/{dosenId}', [App\Http\Controllers\Student\LecturerRatingController::class, 'getUserRating'])->name('get-user-rating');
        });
    });

    // Lecturer routes
        Route::prefix('lecturer')->name('lecturer.')->middleware(['auth.user:DSN'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'lecturerDashboard'])->name('dashboard');

        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [App\Http\Controllers\dosen\StudentsController::class, 'index'])->name('index');
            Route::get('/{id}/details', [App\Http\Controllers\dosen\StudentsController::class, 'getDetails'])->name('details');
            Route::post('/{id}/details/approve', [App\Http\Controllers\dosen\StudentsController::class, 'approve'])->name('approve');
            Route::get('/{id}/view', [App\Http\Controllers\dosen\StudentsController::class, 'getDetails'])->name('view');
            Route::delete('/{id}/delete', [App\Http\Controllers\dosen\StudentsController::class, 'destroy'])->name('destroy');
            Route::get('/{id}', [App\Http\Controllers\dosen\StudentsController::class, 'show'])->name('show');
        });

        Route::prefix('achievements')->name('achievements.')->group(function () {
            Route::get('/', [App\Http\Controllers\dosen\AchievementController::class, 'index'])->name('index');
            Route::get('/{id}', [App\Http\Controllers\dosen\AchievementController::class, 'show'])->name('show');
            Route::get('/{id}/details/{userId}', 'App\Http\Controllers\dosen\AchievementController@getDetails')->name('details');
        });
        
        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', [App\Http\Controllers\dosen\CompetitionController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\dosen\CompetitionController::class, 'store'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\dosen\CompetitionController::class, 'show'])->name('show');
            Route::get('/{id}/sub-competitions', [App\Http\Controllers\dosen\CompetitionController::class, 'subCompetitions'])->name('sub-competitions.index');
            Route::get('/{id}/sub-competitions/create', [App\Http\Controllers\dosen\CompetitionController::class, 'createSubCompetition'])->name('sub-competitions.create');
            Route::post('/{id}/sub-competitions', [App\Http\Controllers\dosen\CompetitionController::class, 'storeSubCompetition'])->name('sub-competitions.store');
            Route::get('/{id}/sub-competitions/{sub_id}', [App\Http\Controllers\dosen\CompetitionController::class, 'showSubCompetition'])->name('sub-competitions.show');
            Route::get('/{competition}/sub-competitions/{sub_competition}/participants', [App\Http\Controllers\dosen\CompetitionController::class, 'participants'])->name('sub-competitions.participants');
            Route::post('/{competition}/sub-competitions/{sub_competition}/participants', [App\Http\Controllers\dosen\CompetitionController::class, 'storeParticipant'])->name('sub-competitions.participants.store');
            Route::put('/{competition}/sub-competitions/{sub_competition}/participants/{participant}/update-status', [App\Http\Controllers\dosen\CompetitionController::class, 'updateParticipantStatus'])->name('sub-competitions.participants.update-status');
            
            Route::match(['get', 'post'], '/{competition}/sub-competitions/{sub_competition}/apply', [App\Http\Controllers\dosen\CompetitionController::class, 'applySubCompetition'])->name('sub-competitions.apply');
            
            // Sub-competition skills routes
            Route::get('/{competition}/sub-competitions/{sub_competition}/skills', [App\Http\Controllers\dosen\CompetitionController::class, 'skills'])->name('sub-competitions.skills');
            Route::post('/{competition}/sub-competitions/{sub_competition}/skills', [App\Http\Controllers\dosen\CompetitionController::class, 'storeSkill'])->name('sub-competitions.skills.store');
            Route::put('/{competition}/sub-competitions/{sub_competition}/skills/{skill}', [App\Http\Controllers\dosen\CompetitionController::class, 'updateSkill'])->name('sub-competitions.skills.update');
            Route::delete('/{competition}/sub-competitions/{sub_competition}/skills/{skill}', [App\Http\Controllers\dosen\CompetitionController::class, 'destroySkill'])->name('sub-competitions.skills.destroy');
            Route::put('/{competition}/sub-competitions/{sub_competition}', [App\Http\Controllers\dosen\CompetitionController::class, 'updateSubCompetition'])->name('sub-competitions.update');
        });
        
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [App\Http\Controllers\dosen\ProfileController::class, 'index'])->name('index');
            Route::put('/update', [App\Http\Controllers\dosen\ProfileController::class, 'update'])->name('update');
            Route::post('/skills', [App\Http\Controllers\dosen\ProfileController::class, 'updateSkills'])->name('skills.update');
            Route::post('/interests', [App\Http\Controllers\dosen\ProfileController::class, 'updateInterests'])->name('interests.update');
        });
        
        Route::prefix('recommendations')->name('recommendations.')->group(function () {
            Route::get('/', function () {
                return view('Dosen.recommendations.index');
            })->name('index');
        });

        Route::prefix('akademik')->name('akademik.')->group(function () {
            Route::get('/', function () {
                return view('Dosen.akademik.index');
            })->name('index');
        });

        Route::prefix('penelitian')->name('penelitian.')->group(function () {
            Route::get('/', function () {
                return view('Dosen.penelitian.index');
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
