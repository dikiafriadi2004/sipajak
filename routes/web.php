<?php

use Illuminate\Support\Facades\Route;
use App\Models\Laporan\LaporanRestoran;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pajak\PajakHotelController;
use App\Http\Controllers\Pajak\PajakRestoranController;
use App\Http\Controllers\Laporan\LaporanHotelController;
use App\Http\Controllers\Laporan\LaporanRestoranController;

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


    // Pajak Restoran dan Laporan Restoran
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

            // TAMBAHKAN {id} DI AKHIR UNTUK MEMBERITAHU BAHWA URL TADI MENGIRIM DATA BERDASARKAN ID, JADI DATA ID AKAN DI KIRIM KE FUNCTION downloadPemberitahuan DI CONTROLLER
            Route::get('download/surat/pemberitahuan/{id}', 'downloadPemberitahuan')->name('laporan.restoran.pemberitahuan.download');
            Route::get('download/surat/teguran/{id}', 'downloadTeguran')->name('laporan.restoran.teguran.download');
        });

        // Pajak Hotel dan Laporan Hotel
        Route::controller(PajakHotelController::class)
        ->prefix('pajak/hotel')
        ->group(function () {
            Route::get('/', 'index')->name('pajakhotel.index');
            Route::get('/data', 'getData')->name('pajakhotel.data');
            Route::get('/filter', 'getfilter')->name('pajakhotel.filter');
            Route::get('/detail/{id}', 'show')->name('pajakhotel.show');

            Route::post('/store', 'store')->name('pajakhotel.store');
            Route::delete('/{id}', 'destroy')->name('pajakhotel.destroy');
            Route::get('/edit/{id}', 'edit')->name('pajakhotel.edit');
            Route::put('/update/{id}', 'update')->name('pajakhotel.update');
        });

    Route::controller(LaporanHotelController::class)
        ->prefix('pajak/hotel/laporan/')
        ->group(function () {
            Route::get('/{id}', 'index')->name('laporan.hotel.index');
            Route::get('/data/{id}', 'getData')->name('laporan.hotel.data');
            Route::get('/filter/{id}', 'getfilter')->name('laporan.hotel.filter');

            Route::post('/store', 'store')->name('laporan.hotel.store');
            Route::get('/pemberitahuan/{id}', 'showPemberitahun')->name('laporan.hotel.pemberitahuan.show');
            Route::put('/pemberitahuan/{id}', 'pemberitahuan')->name('laporan.hotel.pemberitahuan');

            Route::get('/teguran/{id}', 'showTeguran')->name('laporan.hotel.teguran.show');
            Route::put('/teguran/{id}', 'teguran')->name('laporan.hotel.teguran');

            // TAMBAHKAN {id} DI AKHIR UNTUK MEMBERITAHU BAHWA URL TADI MENGIRIM DATA BERDASARKAN ID, JADI DATA ID AKAN DI KIRIM KE FUNCTION downloadPemberitahuan DI CONTROLLER
            Route::get('download/surat/pemberitahuan/{id}', 'downloadPemberitahuan')->name('laporan.hotel.pemberitahuan.download');
            Route::get('download/surat/teguran/{id}', 'downloadTeguran')->name('laporan.hotel.teguran.download');
        });
});

require __DIR__ . '/auth.php';
