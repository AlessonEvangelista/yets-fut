<?php

use App\Http\Controllers\Api\V1\TeamPlayersController;
use Illuminate\Support\Facades\Route;

Route::controller(TeamPlayersController::class)
    ->group(function () {
        Route::resource('', 'TeamPlayersController')->except([
            'create', 'edit', 'show', 'update'
        ])->parameters([
            '' => 'id',
        ]);
    });
