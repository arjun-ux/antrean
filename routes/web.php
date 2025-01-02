<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PasienOnsiteController;
use App\Http\Controllers\PasienOnsiteLaporanController;
use App\Http\Controllers\PoliController;
use App\Models\PasienOnsite;
use App\Models\PasienOnsiteLaporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        return view('admin');
    })->name('admin');
    Route::get('generate-user', [PasienOnsiteController::class, 'generate_user']);
    Route::get('data-pasien-today', [PasienOnsiteController::class, 'data_pasien_today'])->name('data.pasien.today');
    Route::post('data-pasien-old', [PasienOnsiteLaporanController::class, 'data_pasien_old'])->name('data.pasien.old');
});

// pasien middlewarre
Route::middleware('auth','ref_group_id:2')->group(function(){
    Route::get('pasien', function(){
        return view('pasien');
    })->name('pasien');
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
