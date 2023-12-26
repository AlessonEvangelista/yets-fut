<?php

use App\Http\Controllers\Api\V1\GameSettingsController;
use Illuminate\Support\Facades\Route;

Route::controller(GameSettingsController::class)
    ->group(function () {
        Route::resource('', 'GameSettingsController')->except([
            'create', 'edit',
        ])->parameters([
            '' => 'id',
        ]);
    });
