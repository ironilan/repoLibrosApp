<?php

namespace App\Http\Controllers\profesor;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BookController extends Controller
{
    public function index()
    {
        Session::forget('idBookShow');
        return view('profesor.libros.index');
    }

    public function viewMisLibros()
    {
        Session::forget('idBookShow');
        return view('profesor.libros.mis-libros');
    }

    public function show($id)
    {
        $user = Auth::user(); // Obtener el usuario autenticado

        if ($user->hasRole('Administrador')) {
            // Si el usuario es admin, mostrar cualquier libro sin restricciones
            $libro = Book::findOrFail($id);
        } else {
            // Si es profesor, validar que el libro esté asignado a él
            $libro = Book::whereHas('assignedTeachers', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })->findOrFail($id);
        }

        Session::put('idBookShow', $id);

        return view('profesor.libros.show', compact('libro'));
    }


    public function list(Request $request)
    {
        //dd('ssss');
        // Consulta base: solo libros activos
        $query = Book::where('estado', 1);

        // Si hay un término de búsqueda, aplicarlo al título
        if (!empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Ordenar por ID descendente y paginar (5 por página)
        $books = $query->orderBy('id', 'desc')->paginate(12);

        return response()->json($books);
    }



    public function misLibros(Request $request)
    {
        $user = Auth::user(); // Obtener el usuario autenticado

        // Obtener los libros asignados al usuario desde la tabla assigned_books
        $books = Book::where('estado', 1)
            ->whereIn('id', function ($query) use ($user) {
                $query->select('book_id')
                    ->from('assigned_books')
                    ->where('teacher_id', $user->id);
            });

        // Filtrar por búsqueda si hay un término de búsqueda
        if ($request->search) {
            $books->where('title', 'like', '%' . $request->search . '%');
        }

        // Paginar y ordenar los resultados
        $books = $books->orderBy('id', 'desc')->paginate(5);

        return response()->json($books);
    }


    public function libro($id)
    {
        $user = Auth::user(); // Obtener el usuario autenticado

        // Buscar el libro asignado al usuario
        $book = Book::where('estado', 1)
            ->whereIn('id', function ($query) use ($user) {
                $query->select('book_id')
                    ->from('assigned_books')
                    ->where('teacher_id', $user->id);
            })
            ->with('versions') // Cargar las versiones del libro
            ->where('id', $id) // Asegurar que sea el libro específico solicitado
            ->first();

        // Si el libro no está asignado, devolver un error 404
        if (!$book) {
            return response()->json(['error' => 'Libro no asignado o no encontrado'], 404);
        }

        return response()->json($book);
    }
}
