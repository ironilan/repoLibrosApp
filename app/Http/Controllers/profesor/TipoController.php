<?php

namespace App\Http\Controllers\profesor;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Tipo;
use Illuminate\Http\Request;

class TipoController extends Controller
{



    public function index($idTipo, $idBook)
    {
        $book = Book::with(['folders' => function ($query) use ($idTipo) {
            $query->wherePivot('tipo_id', $idTipo)
                ->wherePivot('estado', 1);
        }])->find($idBook);

        if (!$book) {
            abort(404);
        }

        // Cargar los recursos manualmente para cada folder
        $book->folders->load('resources');

        return view('profesor.libros.recursos', [
            'book' => $book,
            'folders' => $book->folders
        ]);
    }



    public function getTiposWithBookAndFolder()
    {
        $tipos = Tipo::whereHas('folders', function ($query) {
            $query->whereNotNull('book_id'); // Asegura que hay un libro asociado en la tabla pivote
        })->get();

        // Si no hay tipos que cumplan con la condición, devolver un error 404
        if ($tipos->isEmpty()) {
            return response()->json(['error' => 'No se encontraron tipos con libro y carpeta asociados'], 404);
        }

        return response()->json($tipos);
    }
}
