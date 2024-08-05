<?php

use App\Http\Controllers\Authentication\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Verification\Verification;
use Illuminate\Auth\Middleware\Authenticate;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth', 'verified'])->group(function() {
    Route::post('verifyDocument', Verification::class);
});
Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('verifyDocumentApi', Verification::class);
});
Route::post('login', Login::class);