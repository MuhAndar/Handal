<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\SpeedCodingAdminController;
use App\Http\Controllers\Validator\ValidatorController;
use App\Http\Controllers\School\DashboardController;
use App\Http\Controllers\School\SurveyController;
use App\Http\Controllers\School\ProfileController;
use App\Http\Controllers\StudentSurveyController;
use App\Http\Controllers\SpeedCodingController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/landing-v2', [LandingController::class, 'indexV2'])->name('landing.v2');
Route::get('/speed-coding', [SpeedCodingController::class, 'index'])->name('speed.coding');

Route::get('/pengajuan-akun', [LandingController::class, 'registrationPage'])->name('registration.page');
Route::post('/register-school', [LandingController::class, 'storeRegistration'])->name('register.store');

Route::get('/ranking-sekolah', [LandingController::class, 'rankingPage'])->name('ranking.page');
Route::redirect('/ranking-v2', '/ranking-sekolah');

// === Rute Publik Survei Siswa (Tanpa Login) ===
Route::get('/survey-siswa/{code}', [StudentSurveyController::class, 'show'])->name('student.survey.show');
Route::post('/survey-siswa/{code}', [StudentSurveyController::class, 'store'])->name('student.survey.store');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// === Aktivasi & Reset Password Mandiri (Tautan Unik / Anti-Spam Backup) ===
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updateResetPassword'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'validator') return redirect()->route('validator.dashboard');
        if ($role === 'school') return redirect()->route('school.dashboard');
        return abort(403, 'Unauthorized action.');
    })->name('dashboard');

    Route::middleware(['role:school'])->prefix('school')->name('school.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/survey/start', [SurveyController::class, 'start'])->name('survey.start');
        Route::get('/survey/step/{step}', [SurveyController::class, 'step'])->name('survey.step');
        Route::post('/survey/process/{step}', [SurveyController::class, 'process'])->name('survey.process');
        Route::get('/survey/result/{id}', [SurveyController::class, 'result'])->name('survey.result');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Kontrol Survei Siswa (Buka/Tutup & Generate Ulang Tautan)
        Route::post('/survey-siswa/toggle', [StudentSurveyController::class, 'toggleStatus'])->name('student.survey.toggle');
        Route::post('/survey-siswa/regenerate', [StudentSurveyController::class, 'regenerateCode'])->name('student.survey.regenerate');
    });

    Route::middleware(['role:validator'])->prefix('validator')->name('validator.')->group(function () {
        Route::get('/dashboard', [ValidatorController::class, 'index'])->name('dashboard');
        Route::get('/registrations/{id}', [ValidatorController::class, 'show'])->name('show');
        Route::post('/registrations/approve/{id}', [ValidatorController::class, 'approve'])->name('approve');
        Route::post('/registrations/reject/{id}', [ValidatorController::class, 'reject'])->name('reject');

        Route::get('/survey/verify/{id}', [ValidatorController::class, 'verifySurvey'])->name('survey.verify');
        Route::post('/survey/verify/{id}', [ValidatorController::class, 'storeVerification'])->name('survey.store');
    });

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])
            ->name('dashboard');
        Route::get('/registrations-verified', [RegistrationController::class, 'index'])
            ->name('registrations.index');

        Route::post('/registrations/create-account/{id}', [RegistrationController::class, 'createAccount'])
            ->name('registrations.create');

        Route::post('/registrations/reject/{id}', [RegistrationController::class, 'rejectToValidator'])
            ->name('registrations.reject');

        Route::post('/registrations/reset-link/{id}', [RegistrationController::class, 'generateResetLink'])
            ->name('registrations.reset_link');

        // === KELOLA DATA SPEED CODING SCRATCH ===
        Route::get('/speed-coding', [SpeedCodingAdminController::class, 'index'])
            ->name('speed_coding.index');
        Route::get('/speed-coding/template', [SpeedCodingAdminController::class, 'downloadTemplate'])
            ->name('speed_coding.template');
        Route::post('/speed-coding/store', [SpeedCodingAdminController::class, 'store'])
            ->name('speed_coding.store');
        Route::put('/speed-coding/{id}', [SpeedCodingAdminController::class, 'update'])
            ->name('speed_coding.update');
        Route::delete('/speed-coding/{id}', [SpeedCodingAdminController::class, 'destroy'])
            ->name('speed_coding.destroy');
        Route::post('/speed-coding/import', [SpeedCodingAdminController::class, 'import'])
            ->name('speed_coding.import');

        Route::resource('categories', CategoryController::class);
        Route::resource('questions', QuestionController::class);
    });
});
