<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarrioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/barrios', [BarrioController::class, 'index'])->name('barrio.index');

    // Llamo al ingreso de la pantalla Barrios
    Route::get('/barrios', function () {
        return view('barrios.index');
    })->name('barrios.web');

    Route::get('/barrios/crear', function() {
        return view('barrios.create');
    })->name('barrios.crear.web');
    
    Route::get('/socios', function() {
        return view('socios.index');
    })->name('socios.web');

    Route::get('/alquileres', function() {
        return view('alquileres.index');
    })->name('alquileres.web');
});

require __DIR__.'/auth.php';