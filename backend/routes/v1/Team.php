<?php

use App\Http\Controllers\Api\V1\TeamPlayersController;
use Illuminate\Support\Facades\Route;

Route::controller(TeamPlayersController::class)
    ->group(function () {
        Route::resource('', 'TeamPlayersController')->except([
            'create', 'edit', 'update'
        ])->parameters([
            '' => 'id',
        ]);
        Route::post('/players-confirm', [TeamPlayersController::class,'playersConfirm'])->name('confirm');
        Route::get('/players-sort/{id}', [TeamPlayersController::class,'playersSort'])->name('sort');
    });
