<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;

Route::prefix('role')
    ->controller(RoleController::class)
    ->group(function () {
        Route::post('/create','store');
    });

