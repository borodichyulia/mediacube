<?php

use App\Http\Controllers\ClientRoleController;
use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Auth\RegisterController;
use \App\Http\Controllers\Auth\LoginController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('clients/roles', ClientRoleController::class);
});
Route::apiResource('clients', ClientController::class)->middleware('auth:sanctum');
