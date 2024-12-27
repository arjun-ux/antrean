<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PasienOnsiteController;
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

// admin
Route::middleware('auth','ref_group_id:1')->group(function(){
    // index admin
    Route::get('admin', function(){
        return view('admin');
    })->name('admin');
    Route::get('data-pasien-today', [PasienOnsiteController::class, 'data_pasien_today'])->name('data.pasien.today');
});

// pasien
Route::middleware('auth','ref_group_id:2')->group(function(){
    Route::get('pasien', function(){
        return view('pasien');
    })->name('pasien');
});


