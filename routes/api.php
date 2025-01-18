<?php

use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::middleware([
    EnsureFrontendRequestsAreStateful::class,
    'auth:sanctum',
    'throttle:api',
])->group(function () {
    foreach (glob(base_path('app/Modules/*/Routes/v*')) as $versionPath) {
        $version = basename($versionPath); // z.B. 'v1', 'v2'
        Route::prefix($version)->group(function () use ($versionPath) {
            foreach (glob("{$versionPath}/apiRoutes.php") as $routeFile) {
                require $routeFile;
            }
        });
    }
});
