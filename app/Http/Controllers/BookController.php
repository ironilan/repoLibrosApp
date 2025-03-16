<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;
use App\Models\BookVersion;
use App\Models\Folder;
use App\Models\FolderTipoBook;
use App\Models\Resource;
use App\Models\Tipo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{

    public function index()
    {

        return view('admin.libros.index');
    }


    /**
     * Listar todos los libros (Admin puede ver todos, Profesor solo los asignados)
     */
    public function list(Request $request)
    {

        $search = $request->query('search');

        $books = Book::with('versions')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%")
                    ->orWhere('autor', 'LIKE', "%$search%")
                    ->orWhere('code', 'LIKE', "%$search%");
            })
            ->orderBy('id', 'desc')->paginate(10);

        return response()->json($books);
    }




    /**
     * Subir un nuevo libro (Solo Admin)
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'libro_profe' => 'required|string',
            'libro_alumno' => 'required|string',
            'fecha_publicacion' => 'required|date',
            //'file_profe' => 'required|mimes:pdf|max:51200',
            //'file_alumno' => 'required|mimes:pdf|max:51200',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Guardar la portada del libro
            $coverPath = null;
            if ($request->hasFile('cover_image')) {
                $coverPath = $request->file('cover_image')->store('book_covers', 'public');
            }

            // Guardar el archivo del profesor
            // $filePathProfe = null;
            // $fileSizeProfe = null;
            // $fileTypeProfe = null;
            // if ($request->hasFile('file_profe')) {
            //     $file = $request->file('file_profe');
            //     $filePathProfe = $file->store('pdfs/profesor', 'public');
            //     $fileSizeProfe = $file->getSize();
            //     $fileTypeProfe = $file->getClientOriginalExtension();
            // }

            // Guardar el archivo del alumno
            // $filePathAlumno = null;
            // $fileSizeAlumno = null;
            // $fileTypeAlumno = null;
            // if ($request->hasFile('file_alumno')) {
            //     $file = $request->file('file_alumno');
            //     $filePathAlumno = $file->store('pdfs/alumno', 'public');
            //     $fileSizeAlumno = $file->getSize();
            //     $fileTypeAlumno = $file->getClientOriginalExtension();
            // }

            // Crear el libro
            $book = Book::create([
                'title' => $request->title,
                'autor' => $request->author,
                'libro_alumno' => $request->libro_alumno,
                'libro_profe' => $request->libro_profe,
                'fecha_publicacion' => $request->fecha_publicacion,
                'code' => uniqid(),
                'estado' => 1,
                'cover_image' => $coverPath,
                'uploaded_by' => Auth::id(),
            ]);

            // Crear la versión del alumno
            // if ($filePathAlumno) {
            //     BookVersion::create([
            //         'book_id' => $book->id,
            //         'version_type' => 'alumno',
            //         'file_type' => $fileTypeAlumno,
            //         'file_size' => $fileSizeAlumno,
            //         'file_path' => $filePathAlumno
            //     ]);
            // }

            // // Crear la versión del profesor
            // if ($filePathProfe) {
            //     BookVersion::create([
            //         'book_id' => $book->id,
            //         'version_type' => 'profesor',
            //         'file_type' => $fileTypeProfe,
            //         'file_size' => $fileSizeProfe,
            //         'file_path' => $filePathProfe
            //     ]);
            // }

            DB::commit();

            return response()->json(['message' => 'Libro subido con éxito', 'book' => $book], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    /**
     * Ver detalles de un libro
     */
    public function show($id)
    {
        $book = Book::with('versions')->findOrFail($id);


        return response()->json($book);
    }

    /**
     * Actualizar un libro (Solo Admin)
     */



    public function update(Request $request, $id)
    {
       // dd($request->all());
        $book = Book::findOrFail($id);

        // Validar los datos del formulario
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:100',
            'fecha_publicacion' => 'nullable|date',
            'folder_id' => 'nullable|exists:folders,id',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024', // Máx 1MB
            'libro_profe' => 'required|string|min:3', // Solo formatos permitidos
            'libro_alumno' => 'required|string|min:3',
        ]);

        // Actualizar los campos en la tabla `books`
        if (isset($validatedData['title'])) {
            $book->title = $validatedData['title'];
        }
        if (isset($validatedData['author'])) {
            $book->autor = $validatedData['author'];
        }
        if (isset($validatedData['code'])) {
            $book->code = $validatedData['code'];
        }
        if (isset($validatedData['fecha_publicacion'])) {
            $book->fecha_publicacion = $validatedData['fecha_publicacion'];
        }
        if (isset($validatedData['folder_id'])) {
            $book->folder_id = $validatedData['folder_id'];
        }

        if (isset($validatedData['libro_alumno'])) {
            $book->libro_alumno = $validatedData['libro_alumno'];
        }

        if (isset($validatedData['libro_profe'])) {
            $book->libro_profe = $validatedData['libro_profe'];
        }

        // Guardar la imagen de portada en `book_covers/`
        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $path = $request->file('cover_image')->store('book_covers', 'public');
            $book->cover_image = $path; // Se guarda solo 'book_covers/archivo.jpg'
        }

        $book->save();

        // Manejo de archivos en `book_versions`
        //$this->updateBookVersion($request, $book->id, 'profesor', 'file_profe', 'pdfs/profesor');
        //$this->updateBookVersion($request, $book->id, 'alumno', 'file_alumno', 'pdfs/alumno');

        return response()->json(['message' => 'Libro actualizado correctamente', 'book' => $book]);
    }

    // Función para actualizar o crear registros en `book_versions`
    private function updateBookVersion(Request $request, $bookId, $versionType, $fileInput, $storagePath)
    {
        if ($request->hasFile($fileInput)) {
            // Buscar si ya existe un registro para este tipo de versión
            $bookVersion = BookVersion::where('book_id', $bookId)
                ->where('version_type', $versionType)
                ->first();

            // Si existe, eliminar el archivo anterior
            if ($bookVersion && $bookVersion->file_path) {
                Storage::disk('public')->delete($bookVersion->file_path);
            } else {
                // Si no existe, crear un nuevo registro
                $bookVersion = new BookVersion();
                $bookVersion->book_id = $bookId;
                $bookVersion->version_type = $versionType;
            }

            // Guardar el nuevo archivo en la carpeta correspondiente
            $path = $request->file($fileInput)->store($storagePath, 'public');
            $bookVersion->file_path = $path; // Se guarda solo 'pdfs/profesor/archivo.pdf' o 'pdfs/alumno/archivo.pdf'
            $bookVersion->file_type = $request->file($fileInput)->getClientOriginalExtension();
            $bookVersion->file_size = $request->file($fileInput)->getSize();
            $bookVersion->estado = 1; // Activo por defecto

            $bookVersion->save();
        }
    }




    /**
     * Eliminar un libro (Solo Admin)
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        $book->update([
            'estado' => 0
        ]);

        return response()->json(['message' => 'Libro eliminado']);
    }

    /**
     * Asignar un libro a un profesor (Solo Admin)
     */
    public function assignBookToTeacher(Request $request)
    {


        $request->validate([
            'book_id' => 'required|exists:books,id',
            'teacher_id' => 'required|exists:users,id',
        ]);

        $teacher = User::findOrFail($request->teacher_id);
        $book = Book::findOrFail($request->book_id);

        if (!$teacher->hasRole('profesor')) {
            return response()->json(['error' => 'El usuario no es un profesor'], 400);
        }

        $teacher->assignedBooks()->syncWithoutDetaching([$book->id]);

        return response()->json(['message' => 'Libro asignado con éxito']);
    }

    /**
     * Obtener libros asignados a un profesor
     */
    public function getAssignedBooks()
    {
        $user = Auth::user();
        if (!$user->hasRole('profesor')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $books = $user->assignedBooks()->with('versions')->get();

        return response()->json($books);
    }




    public function viewLibrosProfesor()
    {
        return view('admin.libros.profesores');
    }


    public function profesores(Request $request)
    {
        // Filtrar solo usuarios activos con el rol "profesor"
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'profesor'); // Filtra solo usuarios con el rol "profesor"
        })
            ->with('roles') // Cargar los roles de cada usuario
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

        // Formatear la respuesta para incluir los roles
        $users->getCollection()->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'num_doc' => $user->num_doc,
                'tipo_doc' => $user->tipo_doc,
                'celular' => $user->celular,
                'estado' => $user->estado,
                'books' => $user->assignedBooks,
                'packs' => $user->packs,
                'roles' => $user->roles->pluck('name'), // Obtener los roles como un array
            ];
        });

        return response()->json($users);
    }


    public function desactivarProfesor($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'estado' => 0
        ]);

        return response()->json(['message' => 'profesor desactivado']);
    }


    public function activarProfesor($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'estado' => 1
        ]);

        return response()->json(['message' => 'profesor activado']);
    }


    public function asignarLibrosProfesor(Request $request)
    {
        $profesor = User::find($request->user_id);

        $profesor->assignedBooks()->sync($request->books);

        return response()->json(['message' => 'Se han asignado los libros con éxito']);
    }




    public function viewPacksProfesor()
    {
        return view('admin.packs.profesores');
    }


    public function asignarPackProfesor(Request $request)
    {
        $profesor = User::find($request->user_id);

        if (!$profesor) {
            return response()->json(['message' => 'Profesor no encontrado'], 404);
        }

        // Obtener los packs anteriores antes de hacer el cambio
        $packsAnteriores = $profesor->packs()->pluck('packs.id');

        // Asignar los nuevos packs
        $profesor->packs()->sync($request->packs);

        // Si se cambió el pack o se envió vacío, eliminar los libros asignados anteriormente
        if ($packsAnteriores->isNotEmpty()) {
            // Obtener los libros que estaban relacionados con los packs anteriores
            $librosAnteriores = DB::table('pack_books')
                ->whereIn('pack_id', $packsAnteriores)
                ->pluck('book_id');

            // Eliminar los libros de la tabla assigned_books para el profesor
            DB::table('assigned_books')
                ->where('teacher_id', $profesor->id)
                ->whereIn('book_id', $librosAnteriores)
                ->delete();
        }

        return response()->json(['message' => 'Se han asignado los packs y libros correctamente']);
    }



}
