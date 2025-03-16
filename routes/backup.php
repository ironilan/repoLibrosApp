<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\profesor\BookController as ProfesorBookController;
use App\Http\Controllers\profesor\RecursoController;
use App\Http\Controllers\profesor\TipoController as ProfesorTipoController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\JwtAuthenticate;

Route::get('login', [AuthController::class, 'viewLogin']);


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
});


Route::prefix('users')->middleware(JwtAuthenticate::class)->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'delete']);
});


Route::prefix('folders')->middleware(JwtAuthenticate::class)->group(function () {
    Route::get('/', [FolderController::class, 'index']); // Listar carpetas
    Route::post('/', [FolderController::class, 'store']); // Crear carpeta
    Route::get('/{id}', [FolderController::class, 'show']); // Ver detalles de una carpeta
    Route::put('/{id}', [FolderController::class, 'update']); // Actualizar carpeta
    Route::delete('/{id}', [FolderController::class, 'destroy']); // Eliminar carpeta
});

Route::prefix('tipos')->middleware(JwtAuthenticate::class)->group(function () {
    Route::get('/', [TipoController::class, 'index']); // Listar carpetas
    Route::post('/', [TipoController::class, 'store']); // Crear carpeta
    Route::get('/{id}', [TipoController::class, 'show']); // Ver detalles de una carpeta
    Route::put('/{id}', [TipoController::class, 'update']); // Actualizar carpeta
    Route::delete('/{id}', [TipoController::class, 'destroy']); // Eliminar carpeta
});


Route::prefix('books')->middleware(JwtAuthenticate::class)->group(function () {
    Route::get('/', [BookController::class, 'index']); // Listar libros
    Route::post('/', [BookController::class, 'store']); // Subir libro
    Route::get('/{id}', [BookController::class, 'show']); // Ver detalles
    Route::put('/{id}', [BookController::class, 'update']); // Actualizar
    Route::delete('/{id}', [BookController::class, 'destroy']); // Eliminar
    Route::post('/assign', [BookController::class, 'assignBookToTeacher']); // Asignar libro
    Route::get('/assigned', [BookController::class, 'getAssignedBooks']); // Ver libros asignados
});


Route::group(['prefix'=> 'profesor', 'middleware' => JwtAuthenticate::class], function(){
    Route::get('/libros', [ProfesorBookController::class, 'index']);
    Route::get('/mis-libros', [ProfesorBookController::class, 'misLibros']);
    Route::get('/libros/{id}', [ProfesorBookController::class, 'libro']);

    Route::get('/tipo/{idTipo}/book/{idBook}', [ProfesorTipoController::class, 'getCarpetasFromTipoAndBook']);
    Route::get('/tipos', [ProfesorTipoController::class, 'getTiposWithBookAndFolder']);
    Route::get('/recursos/download/{id}', [RecursoController::class, 'download']);
    Route::get('/recursos/{idFolderTipoBook}', [RecursoController::class, 'getRecursos']);


});


