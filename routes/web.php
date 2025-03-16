<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\profesor\BookController as ProfesorBookController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\profesor\TipoController as ProfesorTipoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/logout', function () {
    Auth::logout();

    return redirect('/login');
})->middleware('auth');

Route::get('/dashboard', function () {
    $user = Auth::user();

    // Verifica si el usuario tiene el rol de administrador o profesor
    if ($user->hasRole(['administrador'])) {
        return redirect()->route('users.index'); // Redirige a la vista del dashboard
    } else {
        return redirect()->route('dashboard.profesor.libros');
    }

    // Si el usuario no tiene los roles, redirigirlo o mostrar error
    abort(403, 'No tienes permisos para acceder a esta página.');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {

    Route::prefix('users')->group(function () {
        Route::middleware('can:view-users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/list', [UserController::class, 'list']);
        });

        Route::middleware('can:create-users')->post('/', [UserController::class, 'store']);

        Route::middleware('can:edit-users')->put('/{id}', [UserController::class, 'update']);

        Route::middleware('can:delete-users')->delete('/{id}', [UserController::class, 'delete']);
    });


    Route::prefix('packs')->group(function () {
        Route::middleware('can:view-packs')->group(function () {
            Route::get('/', [PackController::class, 'index'])->name('packs.index');
            Route::get('/list', [PackController::class, 'list']);
        });

        Route::middleware('can:create-packs')->post('/', [PackController::class, 'store']);

        Route::middleware('can:edit-packs')->put('/{id}', [PackController::class, 'update']);

        Route::middleware('can:delete-packs')->delete('/{id}', [PackController::class, 'delete']);
    });




    Route::prefix('folders')->group(function () {
        Route::middleware('can:view-folders')->group(function () {
            Route::get('/', [FolderController::class, 'index']); // Listar carpetas
            Route::get('/list', [FolderController::class, 'list']);
            Route::get('/{id}', [FolderController::class, 'show']); // Ver detalles de una carpeta
        });

        Route::middleware('can:create-folders')->post('/', [FolderController::class, 'store']);

        Route::middleware('can:edit-folders')->put('/{id}', [FolderController::class, 'update']);

        Route::middleware('can:delete-folders')->delete('/{id}', [FolderController::class, 'destroy']);
    });


    Route::prefix('tipos')->controller(TipoController::class)->group(function () {
        Route::middleware('can:view-tipos')->group(function () {
            Route::get('/', 'index'); // Listar tipos
            Route::get('/list', 'list');
            Route::get('/{id}', 'show'); // Ver detalles
        });

        Route::middleware('can:create-tipos')->post('/', 'store');

        Route::middleware('can:edit-tipos')->put('/{id}', 'update');

        Route::middleware('can:delete-tipos')->delete('/{id}', 'destroy');
    });


    Route::prefix('books')->controller(BookController::class)->group(function () {
        Route::middleware('can:view-books')->group(function () {
            Route::get('/', 'index');
            Route::get('/list', 'list'); // Listar libros
            Route::get('/{id}', 'show'); // Ver detalles
            Route::get('/assigned', 'getAssignedBooks'); // Ver libros asignados
        });

        Route::middleware('can:create-books')->group(function () {
            Route::post('/', 'store'); // Subir libro
            Route::post('/assign', 'assignBookToTeacher'); // Asignar libro
        });

        Route::middleware('can:edit-books')->put('/update/{id}', 'update'); // Actualizar

        Route::middleware('can:delete-books')->delete('/{id}', 'destroy'); // Eliminar
    });


    Route::prefix('libros')->controller(BookController::class)->middleware('can:view-books')->group(function () {
        Route::get('/profesores', 'viewLibrosProfesor');
        Route::get('/profesores/list', 'profesores');
    });


    Route::prefix('profesor')->controller(BookController::class)->group(function () {
        Route::middleware('can:delete-users')->put('/desactivar/{id}', 'desactivarProfesor');

        Route::middleware('can:edit-users')->group(function () {
            Route::put('/activar/{id}', 'activarProfesor');
            Route::put('/asignar-libros/{id}', 'asignarLibrosProfesor');
            Route::put('/asignar-packs/{id}', 'asignarPackProfesor');
        });
    });

    Route::prefix('packs')->controller(BookController::class)->middleware('can:edit-users')->group(function () {
        Route::get('/profesores', 'viewPacksProfesor');
    });


    Route::prefix('carpetas/libros')->controller(RecursoController::class)->middleware('can:edit-folders')->group(function () {
        Route::get('/', 'viewCarpetasLibros');
        Route::get('/list', 'listarRecursos');
        Route::post('/crear', 'crearRecurso');
        Route::put('/update/{id}', 'updateRecurso');

        Route::middleware('can:delete-folders')->delete('/{id}', 'eliminar');


    });



    Route::group(['prefix' => 'profesor'], function () {
        Route::get('/libros', [ProfesorBookController::class, 'index'])->name('dashboard.profesor.libros');
        Route::get('/libros/list', [ProfesorBookController::class, 'list']);
        Route::get('/mis-libros', [ProfesorBookController::class, 'viewMisLibros']);
        Route::get('/mis-libros/list', [ProfesorBookController::class, 'misLibros']);
        Route::get('/libros/{id}', [ProfesorBookController::class, 'show']);
        Route::get('/libros/{id}/list', [ProfesorBookController::class, 'libro']);

        Route::get('/tipo/{idTipo}/book/{idBook}', [ProfesorTipoController::class, 'index']);
        Route::get('/tipos', [ProfesorTipoController::class, 'getTiposWithBookAndFolder']);
        Route::get('/recursos/download/{id}', [RecursoController::class, 'download']);
        Route::get('/recursos/{idFolderTipoBook}', [RecursoController::class, 'getRecursos']);

        Route::get('/descargar-archivo', [RecursoController::class, 'downloadFromDrive']);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




require __DIR__ . '/auth.php';
