<?php

use App\Http\Controllers\Api\V1\SoccerGinasiumController;
use Illuminate\Support\Facades\Route;

Route::controller(SoccerGinasiumController::class)
    ->group(function () {
        Route::resource('', 'SoccerGinasiumController')->except([
            'create', 'edit',
        ])->parameters([
            '' => 'id',
        ]);
    });
