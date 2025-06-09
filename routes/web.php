<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;


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
//prueba
Route::get('/home', [HomeController::class, 'home']);

// TABLAS MAESTRAS CATALOGO
Route::get('/tablas-maestras', function () {
    return view('tablasMaestras/tablasMaestras');
});





//ROL (perfiles)
//index
Route::get('/tablas-maestras/rol', function () {
    return view('tablasMaestras/rol/index');
})->name('rol.index');
//crear
Route::get('/tablas-maestras/rol/crear', [RolController::class, 'create'])->name('rol.create');
Route::post('/tablas-maestras/rol/crear/insert', [RolController::class, 'store'])->name('rol.insert');
//modificar
Route::get('/tablas-maestras/rol/modificar/{id}/edit', [RolController::class, 'edit'])->name('rol.edit');
Route::put('/tablas-maestras/rol/modificar/{id}', [RolController::class, 'update'])->name('rol.update');
//eliminar
Route::delete('/tablas-maestras/rol/eliminar/{id}', [RolController::class, 'destroy'])->name('rol.delete');






//mi perfil
Route::get('/mi-perfil', [UsuarioController::class, 'mostrarMiPerfil'])->name('miPerfil');
Route::get('/perfil/cambiar-contraseña', function () {
    return view('auth/cambiarContrasena');
})->name('actualizarContrasena');
Route::post('/perfil/cambiar-contraseña', [UsuarioController::class, 'cambiarContrasena'])->name('cambiarContrasena');

//PROTOTIPO PRIMER INICIO DEL ADMINISTRADOR
Route::get('/primer-inicio', function() {
    return view('primerInicio');
});













// PRUEBA
Route::get('/sublime-merge', function() {
    return view("sublimeMerge");
});

Route::get('/prueba', function() {
    return view("probando_funciones");
});