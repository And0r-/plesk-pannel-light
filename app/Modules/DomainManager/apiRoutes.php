<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DomainManager\Controllers\DomainController;

Route::prefix('domains')->group(function () {
    Route::post('/', [DomainController::class, 'store']); // Domain erstellen
    Route::get('/', [DomainController::class, 'index']); // Domains auflisten
});
