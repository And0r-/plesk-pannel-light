<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DomainManager\Controllers\DomainController;

Route::prefix('domains')->group(function () {
    Route::post('/', [DomainController::class, 'store']); // Create domain
    Route::get('/', [DomainController::class, 'index']); // List domains
});
