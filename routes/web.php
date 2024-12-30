<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PasienOnsiteController;
use App\Http\Controllers\PasienOnsiteLaporanController;
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
    Route::get('data-pasien-today', [PasienOnsiteController::class, 'data_pasien_today'])->name('data.pasien.today');
    Route::post('data-pasien-old', [PasienOnsiteLaporanController::class, 'data_pasien_old'])->name('data.pasien.old');
});

// pasien middlewarre
Route::middleware('auth','ref_group_id:2')->group(function(){
    Route::get('pasien', function(){
        return view('pasien');
    })->name('pasien');
    Route::get('data-pasien-today_client', [PasienOnsiteController::class, 'data_pasien_today_client'])->name('data.pasien.today.client');
});

// middleware auth
Route::middleware('auth')->group(function(){
    Route::get('get-poli', [PasienOnsiteLaporanController::class, 'getPoli'])->name('get.poli');
    Route::post('selected-poli', [PasienOnsiteLaporanController::class, 'selected_poli'])->name('selected_poli');
    Route::post('selected-poli-pasien', [PasienOnsiteController::class, 'selected_poli_pasien'])->name('selected_poli_pasien');
});

// generate user awal aplikasi
Route::get('generate-user', [PasienOnsiteController::class, 'generate_user']);
Route::get('laporan', [PasienOnsiteLaporanController::class, 'generate_onsite_laporan']);
