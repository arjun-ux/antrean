<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\PasienOnsiteController;
use App\Http\Controllers\PasienOnsiteLaporanController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\RekapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// unauthorized
Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');
// callback not found
Route::fallback(function(){
    return response()->view('auth.404', [], 404);
});


// halaman login dengan middleware tamu
Route::middleware('guest')->group(function(){
    Route::get('login', [AuthController::class, 'login_page'])->name('login');
    Route::post('login', [AuthController::class, 'dologin'])->name('dologin');
});

// logout
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// admin middleware
Route::middleware('auth','ref_group_id:1')->group(function(){
    // index admin
    Route::get('admin', function(){
        return view('admin_page.index');
    })->name('admin.index');
    // data grafik pasien per pkm
    Route::get('data-pasien-per-pkm', [RekapController::class, 'getPasienPerPKM'])->name('getPasienPerPKM');
    // rekap pasien
    Route::get('rekap-pasien', function(){
        return view('admin_page.rekap_pasien');
    })->name('rekap_pasien');
    // rekap per pkm
    Route::get('rekap-per-pkm', [RekapController::class, 'rekap_per_pkm'])->name('rekap_per_pkm');
    Route::get('data-rekap', [RekapController::class, 'data_rekap'])->name('data.rekap');

    Route::get('generate-user', [PasienOnsiteController::class, 'generate_user']);
    Route::get('data-pasien-today', [PasienOnsiteController::class, 'data_pasien_today'])->name('data.pasien.today');
    Route::post('data-pasien-old', [PasienOnsiteLaporanController::class, 'data_pasien_old'])->name('data.pasien.old');
    // user
    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('data-user', [UserController::class, 'data_user'])->name('data.user');
    Route::get('cek-user', [UserController::class, 'cek_user_baru'])->name('cek_user_baru');
    Route::post('sinkron', [UserController::class, 'sinkron'])->name('sinkron');
});

// pasien middlewarre
Route::middleware('auth','ref_group_id:2')->group(function(){
    // halaman client index
    Route::get('pasien-index', [RekapController::class, 'rekap_pkm'])->name('pasien.index');
    // grafik
    Route::get('data-pasien-on-pkm', [RekapController::class, 'getPasienOnPKM'])->name('getPasienOnPKM');
    Route::get('pasien-rekap', function(){
        return view('client_page.pasien');
    })->name('pasien.rekap');
    Route::get('data-pasien-today-client', [PasienOnsiteController::class, 'data_pasien_today_client'])->name('data.pasien.today.client');
    Route::post('data-pasien-old-client', [PasienOnsiteLaporanController::class, 'data_pasien_old_client'])->name('data.pasien.old.client');

});

// middleware auth
Route::middleware('auth')->group(function(){
    // poli
    Route::get('get-poli', [PoliController::class, 'getPoli'])->name('get.poli');
    Route::post('selected-poli', [PoliController::class, 'selected_poli'])->name('selected_poli');
    Route::post('selected-poli-pasien', [PoliController::class, 'selected_poli_pasien'])->name('selected_poli_pasien');
    // pkm
    Route::get('get-pkm', [PoliController::class, 'getPkm'])->name('get.pkm');
    Route::post('selected-pkm', [PoliController::class, 'selected_pkm'])->name('selected_pkm');
    Route::post('selected-pkm-pasien', [PoliController::class, 'selected_pkm_pasien'])->name('selected_pkm_pasien');
});
