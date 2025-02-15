<?php

use App\Http\Controllers\Api\ApiPasienController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// get pasien onsite by kode pkm dan tanggal
Route::get('/v1/pasien-onsite', [ApiPasienController::class, 'getDataPasienOnsite']);
