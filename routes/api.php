<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SocioController;
use App\Http\Controllers\AlquilerController;
use App\Http\Controllers\BarrioController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\UtileriaController;

/*
 |--------------------------------------------------------------------------
 | Rutas API
 |--------------------------------------------------------------------------
 */

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- Rutas API ---

// Route::apiResource('socios', SocioController::class)->names('api.socios');
// Route::apiResource('alquileres', AlquilerController::class)->names('api.alquileres');
// Route::apiResource('barrios', BarrioController::class)->names('api.barrios');
Route::apiResource('sectors', SectorController::class);
Route::apiResource('utilerias', UtileriaController::class)->names('api.utilerias');

Route::get('/test-ruta', function () {
    return 'funciona';
});