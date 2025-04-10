<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;


Route::get('/', function () {
    return view('welcome');
});

/*Route::prefix('/personas')->group(function () {
    
});*/

Route::get('/personas', [PersonaController::class, 'index']);

// LOGIN
Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/cerrar-sesion', [AuthController::class, 'logout'])->name('logout');

// REGISTRO USUARIO
Route::get('/formulario-registro', [AuthController::class, 'showRegisterForm'])->name('formularioRegistro');
Route::post('/registrar-usuario', [AuthController::class, 'recibirFormularioRegistro'])->name('procesarRegistro');

// INICIO
Route::get('/home', [HomeController::class, 'home']);

// TABLAS MAESTRAS CATALOGO
Route::get('/tablasMaestras', function () {
    return view('tablasMaestras/tablasMaestras');
});

//PROTOTIPO PRIMER INICIO DEL ADMINISTRADOR
Route::get('/primer-inicio', function() {
    return view('primerInicio');
});

// PRUEBA
Route::get('/sublime-merge', function() {
    return view("sublimeMerge");
});