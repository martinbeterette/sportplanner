<?php

use App\Http\Controllers\ContactoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\TipoContactoController;
use App\Http\Controllers\rolController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/personas',         [PersonaController::class, 'indexApi']);
Route::post('/personas',        [PersonaController::class, 'store']);
Route::get('/personas/{id}',    [PersonaController::class, 'show']);
Route::put('/personas/{id}',    [PersonaController::class, 'update']);
Route::delete('/personas/{id}', [PersonaController::class, 'destroy']);

Route::get('tipo-contactos',    [TipoContactoController::class, 'index']);
Route::post('tipo-contactos',   [TipoContactoController::class, 'store']);

Route::get('contactos',         [ContactoController::class, 'index']);
Route::post('contactos',        [ContactoController::class, 'store']);

Route::get('rol',             [rolController::class, 'indexApi']);
Route::post('rol',            [rolController::class, 'store']);

Route::get('usuarios',          [UsuarioController::class, 'index']);
Route::post('usuarios',         [UsuarioController::class, 'store']);