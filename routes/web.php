<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\PeminjamanController;

/*WEB ROUTE*/

Route::get('/', function () {

    return redirect()->route('login');
});

/*AUTH ROUTES*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/pengunjung-export', [PengunjungController::class, 'export'])
        ->name('pengunjung.export');
    Route::resource('pengunjung', PengunjungController::class);

    Route::get('/buku/export', [BukuController::class, 'export'])
        ->name('buku.export');
    Route::resource('buku', BukuController::class);

    Route::get('/peminjaman/export', [PeminjamanController::class, 'export'])
        ->name('peminjaman.export');

    Route::resource('peminjaman', PeminjamanController::class);

    Route::post('/kembali/{id}', [PeminjamanController::class, 'kembali'])
        ->name('kembali');
    Route::post('/hilang/{id}', [PeminjamanController::class, 'hilang'])
        ->name('hilang');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';
