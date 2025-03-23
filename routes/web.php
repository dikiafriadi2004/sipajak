<?php

use App\Http\Controllers\Laporan\LaporanRestoranController;
use App\Http\Controllers\Pajak\PajakRestoranController;
use App\Http\Controllers\ProfileController;
use App\Models\Laporan\LaporanRestoran;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::controller(PajakRestoranController::class)
        ->prefix('pajak/restoran')
        ->group(function () {
            Route::get('/', 'index')->name('pajakrestoran.index');
            Route::get('/data', 'getData')->name('pajakrestoran.data');
            Route::get('/filter', 'getfilter')->name('pajakrestoran.filter');
            Route::get('/detail/{id}', 'show')->name('pajakrestoran.show');

            Route::post('/store', 'store')->name('pajakrestoran.store');
            Route::delete('/{id}', 'destroy')->name('pajakrestoran.destroy');
            Route::get('/edit/{id}', 'edit')->name('pajakrestoran.edit');
            Route::put('/update/{id}', 'update')->name('pajakrestoran.update');
        });

    Route::controller(LaporanRestoranController::class)
        ->prefix('pajak/restoran/laporan/')
        ->group(function () {
            Route::get('/{id}', 'index')->name('laporan.restoran.index');
            Route::get('/data/{id}', 'getData')->name('laporan.restoran.data');
            Route::get('/filter/{id}', 'getfilter')->name('laporan.restoran.filter');

            Route::post('/store', 'store')->name('laporan.restoran.store');
            Route::get('/pemberitahuan/{id}', 'showPemberitahun')->name('laporan.restoran.pemberitahuan.show');
            Route::put('/pemberitahuan/{id}', 'pemberitahuan')->name('laporan.restoran.pemberitahuan');

            Route::get('/teguran/{id}', 'showTeguran')->name('laporan.restoran.teguran.show');
            Route::put('/teguran/{id}', 'teguran')->name('laporan.restoran.teguran');

            Route::post('download/surat/pemberitahuan/{id}', 'downloadsuratpemberitahuan')->name('laporan.restoran.teguran.download');
        });
});

require __DIR__ . '/auth.php';
