<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SecretaryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard per role
Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/sekretaris/dashboard', function () {
        return view('sekretaris.dashboard');
    })->name('sekretaris.dashboard');

    Route::get('/owner/dashboard', function () {
        return view('owner.dashboard');
    })->name('owner.dashboard');

    // Manajemen Owner (Daftar, Detail, dan Edit Profil oleh Owner/Admin; Create & Delete dibatasi di Controller)
    Route::middleware(['role:admin,owner'])->group(function () {
        Route::resource('owner', \App\Http\Controllers\OwnerController::class);
    });

    // Modul Master Data & Manajemen yang Khusus untuk Admin
    Route::middleware(['admin'])->group(function () {
        // Manajemen User
        Route::resource('admin/users', UserController::class)->names('admin.users');

        // Manajemen Supplier (sesuai activity diagram: Read + Update Data Supplier)
        Route::resource('admin/suppliers', SupplierController::class)->names('admin.suppliers');

        // Manajemen Sekretaris
        Route::resource('admin/secretaries', SecretaryController::class)->names('admin.secretaries');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';