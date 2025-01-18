<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Home');
    });

    Route::get('/home', function () {
        return Inertia::render('Home');
    })->name('dashboard');

    Route::get('/domains', function () {
        return Inertia::render('DomainManager/Index');
    })->name('domains');


    Route::get('/show-token', function (Request $request) {
        $user = auth()->user();
        $plainTextToken = $user->createToken('Api Token')->plainTextToken;

        return Inertia::render('ShowToken', [
            'token' => $plainTextToken,
        ]);
    })->name('show.token');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
