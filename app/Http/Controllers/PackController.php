<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class PackController extends Controller
{
    public function index()
    {
        // $role = Role::where('name', 'Administrador')->first();

        // // Obtener los permisos asignados a ese rol
        // $permissions = $role->permissions;

        // return $permissions;
        return view('admin.packs.index');
    }

    public function list(Request $request)
    {
        $search = $request->query('search');

        $packs = Pack::with('books')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            })
            ->orderBy('id', 'desc')->paginate(10);


        $packs->getCollection()->transform(function ($pack) {
            return [
                'id' => $pack->id,
                'name' => $pack->name,
                'imagen' => "/storage/$pack->imagen",
                'books' => $pack->books,
            ];
        });

        return response()->json($packs);
    }




    /**
     * Subir un nuevo libro (Solo Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'books' => 'nullable|array', // Validar que books es un array
            'books.*' => 'exists:books,id' // Validar que los libros existen en la BD
        ]);

        DB::beginTransaction();
        try {
            // Guardar la imagen de portada si existe
            $coverPath = null;
            if ($request->hasFile('imagen')) {
                $coverPath = $request->file('imagen')->store('packs', 'public');
            }

            // Crear el pack
            $pack = Pack::create([
                'name' => $request->name,
                'imagen' => $coverPath,
            ]);

            // Asignar libros al pack (si se seleccionaron)
            if ($request->has('books')) {
                $pack->books()->attach($request->books); // Guarda en la tabla pivot `pack_books`
            }

            DB::commit();

            return response()->json(['message' => 'Pack creado con éxito', 'pack' => $pack], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Ocurrió un error al guardar el pack'], 500);
        }
    }



    /**
     * Ver detalles de un libro
     */
    public function show($id)
    {
        $book = Pack::with('books')->findOrFail($id);


        return response()->json($book);
    }

    /**
     * Actualizar un libro (Solo Admin)
     */


    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'books' => 'nullable|array', // Validar que sea un array
            'books.*' => 'exists:books,id' // Validar que los libros existen en la BD
        ]);

        DB::beginTransaction();
        try {
            $pack = Pack::findOrFail($id);

            // Verificar si se subió una nueva imagen
            if ($request->hasFile('imagen')) {
                // Eliminar la imagen anterior si existe
                if ($pack->imagen) {
                    Storage::disk('public')->delete($pack->imagen);
                }

                // Guardar la nueva imagen
                $path = $request->file('imagen')->store('packs', 'public');
                $pack->imagen = $path;
            }

            // Actualizar solo si hay cambios en el nombre
            if ($pack->name !== $validatedData['name']) {
                $pack->name = $validatedData['name'];
            }

            // Guardar cambios del pack
            $pack->save();

            // Actualizar los libros en la tabla pivot `pack_books`
            if ($request->has('books')) {
                $pack->books()->sync($request->books); // Actualiza libros seleccionados
            } else {
                $pack->books()->detach(); // Si no se envían libros, eliminarlos del pack
            }

            DB::commit();

            return response()->json(['message' => 'Pack actualizado con éxito', 'pack' => $pack], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el pack', 'details' => $e->getMessage()], 500);
        }
    }





    /**
     * Eliminar un libro (Solo Admin)
     */
    public function destroy($id)
    {
        $book = Pack::findOrFail($id);

        $book->update([
            'estado' => 0
        ]);

        return response()->json(['message' => 'Libro eliminado']);
    }
}
