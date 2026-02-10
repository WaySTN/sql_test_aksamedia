<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NilaiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Endpoint Nilai RT (Tes Minat) dan Nilai ST (Tes Skolastik)
Route::get('/nilaiRT', [NilaiController::class, 'nilaiRT']);
Route::get('/nilaiST', [NilaiController::class, 'nilaiST']);

