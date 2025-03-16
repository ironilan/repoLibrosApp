<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function index()
    {

        return view('admin.carpetas.index');
    }


    public function list(Request $request)
    {

        $folders = Folder::where('estado', 1);

        if($request->search)
        {
            $folders->where('name', 'like', '%'.$request->search.'%');
        }

        $folders = $folders->orderBy('id', 'desc')->paginate(5);


        $folders->getCollection()->transform(function ($folder) {
            return [
                'id' => $folder->id,
                'name' => $folder->name,
                'imagen' => "/storage/$folder->imagen"
            ];
        });

        return response()->json($folders);
    }

    /**
     * Crear una nueva carpeta (Solo Admin)
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Guardar la imagen de portada si existe
            $coverPath = null;
            if ($request->hasFile('imagen')) {
                $coverPath = $request->file('imagen')->store('folders', 'public');
            }

            // Crear el pack
            $folder = Folder::create([
                'name' => $request->name,
                'imagen' => $coverPath,
                'estado' => 1,
                'user_id' => Auth::id(),
            ]);



            DB::commit();

            return response()->json(['message' => 'carpeta creada con éxito', 'folder' => $folder], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Ocurrió un error al guardar la carpeta'.$th], 500);
        }
    }

    /**
     * Ver detalles de una carpeta
     */
    public function show($id)
    {
        $folder = Folder::with(['children', 'books'])->findOrFail($id);


        return response()->json($folder);
    }

    /**
     * Actualizar una carpeta (Solo Admin)
     */
    public function update(Request $request, $id)
    {

        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $folder = Folder::findOrFail($id);

            // Verificar si se subió una nueva imagen
            if ($request->hasFile('imagen')) {
                // Eliminar la imagen anterior si existe
                if ($folder->imagen) {
                    Storage::disk('public')->delete($folder->imagen);
                }

                // Guardar la nueva imagen
                $path = $request->file('imagen')->store('folders', 'public');
                $folder->imagen = $path;
            }

            // Actualizar solo si hay cambios en el nombre
            if ($folder->name !== $validatedData['name']) {
                $folder->name = $validatedData['name'];
            }

            // Guardar cambios del folder
            $folder->save();



            DB::commit();

            return response()->json(['message' => 'folder actualizado con éxito', 'folder' => $folder], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el folder', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar una carpeta (Solo Admin)
     */
    public function destroy($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->estado = 0;

        $folder->save();

        return response()->json(['message' => 'Carpeta eliminada']);
    }
}
