<?php

use App\Http\Controllers\Api\ApiPasienController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/v1/pasien-onsite', [ApiPasienController::class, 'getDataPasienOnsite']);
