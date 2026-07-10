<?php

use App\Http\Controllers\ActivationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::get('/', [PublicController::class, 'index'])->name('public.home');
Route::get('/senarai-kempen', [PublicController::class, 'senaraiKempen'])->name('public.kempen.index');
Route::get('/kempen/{kempen}', [PublicController::class, 'kempen'])->name('public.kempen');
Route::get('/ketelusan', [PublicController::class, 'ketelusan'])->name('public.ketelusan');
Route::post('/testimoni/hantar', [PublicController::class, 'hantarTestimoni'])->name('public.testimoni.hantar');

// ─── Auth Routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/daftar/penderma', [AuthController::class, 'showRegisterPenderma'])->name('register.penderma');
    Route::post('/daftar/penderma', [AuthController::class, 'registerPenderma'])->name('register.penderma.post');
    Route::get('/daftar/organisasi', [AuthController::class, 'showRegisterOrganisasi'])->name('register.organisasi');
    Route::post('/daftar/organisasi', [AuthController::class, 'registerOrganisasi'])->name('register.organisasi.post');

    Route::get('/lupa-kata-laluan', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/lupa-kata-laluan', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/tetapan-semula-kata-laluan/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/tetapan-semula-kata-laluan', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── ToyyibPay Callback (tanpa auth — dipanggil oleh server ToyyibPay) ────────
Route::post('/derma/callback', [DonationController::class, 'tindakBalas'])->name('public.derma.callback');
Route::get('/derma/return', [DonationController::class, 'lamanKembali'])->name('public.derma.return');

Route::post('/organisasi/activation/callback', [ActivationController::class, 'tindakBalas'])->name('organisasi.activation.callback');
Route::get('/organisasi/activation/return', [ActivationController::class, 'lamanKembali'])->name('organisasi.activation.return');

// ─── Organisasi Activation Routes (Authenticated) ───────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/organisasi/activation', [ActivationController::class, 'index'])->name('organisasi.activation');
    Route::post('/organisasi/activation/proses', [ActivationController::class, 'proses'])->name('organisasi.activation.proses');
});

// ─── Authenticated Donor Routes ───────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/derma/{kempen}/checkout', [DonationController::class, 'borangBayaran'])->name('public.derma.checkout');
    Route::post('/derma/{kempen}/proses', [DonationController::class, 'proses'])->name('public.derma.proses');
    Route::get('/derma/terima-kasih/{derma}', [DonationController::class, 'terimaKasih'])->name('public.derma.terima_kasih');
    Route::get('/derma/gagal/{derma}', [DonationController::class, 'gagal'])->name('public.derma.gagal');
    Route::get('/riwayat-derma', [PublicController::class, 'riwayatDerma'])->name('public.riwayat');
    Route::get('/derma/{derma}/resit', [DonationController::class, 'muatTurunResit'])->name('public.derma.resit');

    // ─── Profile Routes ────────────────────────────────────────────────────────
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profil/kemaskini', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profil/kata-laluan', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // ─── Laporan Routes (Cetak) ─────────────────────────
    Route::get('/laporan/cetak', [ReportController::class, 'cetak'])->name('laporan.cetak');
});
