<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use L5Swagger\Http\Controllers\SwaggerController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    });

    Route::get('/home', function () {
        return Inertia::render('Home');
    })->name('dashboard');

    Route::get('/domains', function () {
        return Inertia::render('DomainManager/Index');
    })->name('domains');

    Route::get('/api/documentation', [SwaggerController::class, 'api'])->name('swagger.api');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
