<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArsipController;

// ========== PUBLIC ==========
Route::get('/dashboard', [ArsipController::class, 'public'])->name('dashboard');

Route::get('/search', [ArsipController::class, 'search'])->name('arsip.search');
Route::get('/suggest', [ArsipController::class, 'suggest'])->name('arsip.suggest');

// ========== ADMIN LOGIN ==========
Route::post('/dashboard/admin', [ArsipController::class, 'adminLogin'])->name('dashboard.admin');
Route::post('/dashboard/admin/logout', [ArsipController::class, 'adminLogout'])->name('dashboard.admin.logout');

// ========== ADMIN PAGE ==========
Route::get('/dashboard/admin', [ArsipController::class, 'admin'])->name('dashboard.admin.view');
Route::get('/dashboard/admin/search', [ArsipController::class, 'adminSearch'])->name('arsip.admin.search');


// ========== CRUD ARSIP (Admin Only) ==========
// create / store sudah ada
Route::post('/dashboard/admin/store', [ArsipController::class, 'store'])->name('arsip.store');

// UPDATE DATA
Route::put('/dashboard/admin/arsip/{id}', [ArsipController::class, 'update'])->name('arsip.update');

// DELETE DATA
Route::delete('/dashboard/admin/arsip/{id}', [ArsipController::class, 'destroy'])->name('arsip.destroy');
