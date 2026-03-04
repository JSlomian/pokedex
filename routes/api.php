<?php

declare(strict_types=1);

use App\Http\Controllers\BannedPokemonController;
use Illuminate\Support\Facades\Route;

Route::middleware('api-key')->group(function () {
    Route::get('/banned', [BannedPokemonController::class, 'index']);
    Route::post('/banned', [BannedPokemonController::class, 'store']);
    Route::delete('/banned/{bannedPokemon:name}', [BannedPokemonController::class, 'destroy']);
});
