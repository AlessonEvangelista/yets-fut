<?php

use App\Http\Controllers\api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)
    ->group(function () {
        Route::resource('', 'UserController')->except([
                'create', 'edit',
            ])->parameters([
                '' => 'id',
            ]);
    });
