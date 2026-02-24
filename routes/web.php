<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarrioController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AlquilerController;
use App\Http\Controllers\UtileriaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class , 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

    // Barrios
    Route::resource('barrios', BarrioController::class)->names('barrios');

    // Socios
    Route::resource('socios', SocioController::class)->names('socios');

    // Alquileres
    Route::get('/alquileres', [AlquilerController::class , 'index'])->name('alquileres.index');
    Route::get('/alquileres/eventos', [AlquilerController::class , 'getEvents'])->name('alquileres.eventos');
    Route::post('/alquileres', [AlquilerController::class , 'store'])->name('alquileres.store');
    Route::get('/alquileres/{id}', [AlquilerController::class , 'show'])->name('alquileres.show');
    Route::put('/alquileres/{id}', [AlquilerController::class , 'update'])->name('alquileres.update');
    Route::post('/alquileres/{id}/pagar-saldo', [AlquilerController::class , 'registrarPago'])->name('alquileres.pagar-saldo');
    Route::delete('/alquileres/{id}', [AlquilerController::class , 'destroy'])->name('alquileres.destroy');

    // Utilería
    Route::resource('utilerias', UtileriaController::class)->names('utilerias');

    // Caja
    Route::get('/caja', [CajaController::class , 'index'])->name('caja.index');
    Route::post('/caja', [CajaController::class , 'store'])->name('caja.store');
    Route::post('/caja/pago-cuota', [CajaController::class , 'pagarCuota'])->name('caja.pagarCuota');

    // Reportes
    Route::get('/reportes', [ReporteController::class , 'index'])->name('reportes.index');
    Route::get('/reportes/socios', [ReporteController::class , 'sociosPdf'])->name('reportes.socios');
    Route::get('/reportes/alquileres', [ReporteController::class , 'alquileresPdf'])->name('reportes.alquileres');
    Route::get('/reportes/caja', [ReporteController::class , 'cajaPdf'])->name('reportes.caja');
});

require __DIR__ . '/auth.php';