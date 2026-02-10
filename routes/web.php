<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NilaiController;

Route::get('/', function () {
    return view('welcome');
});

// Endpoint Nilai RT (Tes Minat) dan Nilai ST (Tes Skolastik)
Route::get('/nilaiRT', [NilaiController::class, 'nilaiRT']);
Route::get('/nilaiST', [NilaiController::class, 'nilaiST']);
