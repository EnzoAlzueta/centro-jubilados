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
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- Rutas API ---

Route::apiResource('socios', SocioController::class);
Route::apiResource('alquileres', AlquilerController::class);
Route::apiResource('barrios', BarrioController::class);
Route::apiResource('sectors', SectorController::class);
Route::apiResource('utilerias', UtileriaController::class);

Route::get('/test-ruta', function() { return 'funciona'; });    