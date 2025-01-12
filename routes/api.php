<?php

Route::prefix('v1')->group(function () {
    foreach (glob(base_path('app/Modules/*/apiRoutes.php')) as $routeFile) {
        require $routeFile;
    }
});
