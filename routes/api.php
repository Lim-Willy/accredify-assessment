<?php

use App\Http\Controllers\Authentication\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerificationController;
use Illuminate\Auth\Middleware\Authenticate;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth', 'verified'])->group(function() {
    Route::post('verifyDocument', [VerificationController::class, 'verifyJsonFile']);
});
Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('verifyDocumentApi', [VerificationController::class, 'verifyJsonFile']);
});
Route::post('login', Login::class);