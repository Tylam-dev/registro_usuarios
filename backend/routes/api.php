<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1'], function () {
    // List all users
    Route::get('usuarios', [UsuarioController::class, 'index']);
    // Create a new user
    Route::post('usuarios', [UsuarioController::class, 'store']);
    // Retrieve a specific user
    Route::get('usuarios/{usuario}', [UsuarioController::class, 'show']);
    // Update a user (ID provided in payload, not URL)
    Route::put('usuarios', [UsuarioController::class, 'update']);
    // Delete a specific user
    Route::delete('usuarios/{usuario}', [UsuarioController::class, 'destroy']);
});
