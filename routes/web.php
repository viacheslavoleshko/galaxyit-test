<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/apply', [CandidateController::class, 'create'])->name('apply.create');
Route::post('/apply', [CandidateController::class, 'store'])->name('apply.store');