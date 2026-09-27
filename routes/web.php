<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalibrationController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PracticumBookingController;
use App\Http\Controllers\PracticumScheduleController;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LandingController;
/*
|--------------------------------------------------------------------------
| Ganti isi routes/web.php project Laravel Anda dengan file ini
| (gabungkan dengan rute default Breeze: '/', profile, dsb.)
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');

// Webhook Midtrans: HARUS di luar middleware auth & tanpa proteksi CSRF
// (tambahkan '/midtrans/callback' ke $except di app/Http/Middleware/VerifyCsrfToken.php)
Route::post('/midtrans/callback', [PaymentController::class, 'midtransCallback'])->name('payments.midtrans-callback');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Dashboard (menggantikan dashboard default Breeze)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Sampel Uji
    Route::get('/samples', [SampleController::class, 'index'])->name('samples.index');
    Route::get('/samples/create', [SampleController::class, 'create'])->name('samples.create');
    Route::post('/samples', [SampleController::class, 'store'])->name('samples.store');
    Route::get('/samples/{sample}', [SampleController::class, 'show'])->name('samples.show');
    Route::post('/samples/{sample}/verify', [SampleController::class, 'verify'])->name('samples.verify');
    Route::post('/samples/{sample}/input-hasil', [SampleController::class, 'inputHasil'])->name('samples.input-hasil');
    Route::post('/samples/{sample}/approve-hasil', [SampleController::class, 'approveHasil'])->name('samples.approve-hasil');
    Route::get('/samples/{sample}/sertifikat', [CertificateController::class, 'download'])->name('certificates.download');

    // Modul Alat & Kalibrasi
    Route::get('/instruments', [InstrumentController::class, 'index'])->name('instruments.index');
    Route::get('/instruments/create', [InstrumentController::class, 'create'])->name('instruments.create');
    Route::post('/instruments', [InstrumentController::class, 'store'])->name('instruments.store');
    Route::get('/instruments/{instrument}', [InstrumentController::class, 'show'])->name('instruments.show');
    Route::get('/instruments/{instrument}/edit', [InstrumentController::class, 'edit'])->name('instruments.edit');
    Route::put('/instruments/{instrument}', [InstrumentController::class, 'update'])->name('instruments.update');
    Route::delete('/instruments/{instrument}', [InstrumentController::class, 'destroy'])->name('instruments.destroy');

    Route::get('/calibrations', [CalibrationController::class, 'index'])->name('calibrations.index');
    Route::get('/instruments/{instrument}/calibrations/create', [CalibrationController::class, 'create'])->name('calibrations.create');
    Route::post('/instruments/{instrument}/calibrations', [CalibrationController::class, 'store'])->name('calibrations.store');

    // Modul Praktikum
    Route::get('/practicum-schedules', [PracticumScheduleController::class, 'index'])->name('practicum-schedules.index');
    Route::get('/practicum-schedules/create', [PracticumScheduleController::class, 'create'])->name('practicum-schedules.create');
    Route::post('/practicum-schedules', [PracticumScheduleController::class, 'store'])->name('practicum-schedules.store');
    Route::get('/practicum-schedules/{practicumSchedule}', [PracticumScheduleController::class, 'show'])->name('practicum-schedules.show');

    Route::get('/practicum-bookings', [PracticumBookingController::class, 'index'])->name('practicum-bookings.index');
    Route::post('/practicum-schedules/{schedule}/book', [PracticumBookingController::class, 'store'])->name('practicum-bookings.store');
    Route::post('/practicum-bookings/{booking}/status/{status}', [PracticumBookingController::class, 'updateStatus'])->name('practicum-bookings.status');

    // Modul Pembayaran
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/snap-token', [PaymentController::class, 'snapToken'])->name('payments.snap-token');
    Route::post('/payments/{payment}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payments.upload-proof');
    Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

    // Modul Laporan / Export Excel
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/samples/export', [ReportController::class, 'exportSamples'])->name('reports.samples.export');

    // Modul Kelola User (khusus admin, di-guard di controller)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php'; // rute bawaan Breeze (login, register, dll)
