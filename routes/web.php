<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\Master\MuridController;
use App\Http\Controllers\Master\GuruController;
use App\Http\Controllers\Master\KategoriController;
use App\Http\Controllers\Master\DdcController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::middleware('superadmin')
        ->prefix('master')
        ->name('master.')
        ->group(function () {
            Route::post('/murid/import', [MuridController::class, 'import'])
                ->name('murid.import');

            Route::get('/murid/export', [MuridController::class, 'export'])
                ->name('murid.export');

            Route::post('/murid/luluskan', [MuridController::class, 'luluskan'])
                ->name('murid.luluskan');

            Route::resource('murid', MuridController::class)
                ->except(['show', 'create']);

            Route::post('/guru/import', [GuruController::class, 'import'])
                ->name('guru.import');

            Route::get('/guru/export', [GuruController::class, 'export'])
                ->name('guru.export');

            Route::resource('guru', GuruController::class)
                ->except(['show', 'create']);

            Route::resource('kategori', KategoriController::class)
                ->except(['show', 'create', 'edit']);

            Route::resource('ddc', DdcController::class)
                ->except(['show', 'create', 'edit']);
        });

    Route::get('/pengunjung/lookup', [PengunjungController::class, 'lookup'])
        ->name('pengunjung.lookup');

    Route::get('/pengunjung-export', [PengunjungController::class, 'export'])
        ->name('pengunjung.export');

    Route::get('/pengunjung/form', [PengunjungController::class, 'formPengunjung'])
        ->name('pengunjung.form');

    Route::post('/pengunjung/form', [PengunjungController::class, 'storePengunjungMandiri'])
        ->name('pengunjung.form.store');

    Route::resource('pengunjung', PengunjungController::class)
        ->except(['show']);

    Route::post('/buku/import', [BukuController::class, 'import'])
        ->name('buku.import');

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
