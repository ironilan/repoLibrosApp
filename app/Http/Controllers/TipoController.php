<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TipoController extends Controller
{
    public function index()
    {


        return view('admin.tipos.index');
    }

    public function list(Request $request)
    {

        $tipos = Tipo::where('estado', 1);

        if($request->search)
        {
            $tipos->where('name', 'like', '%'.$request->search.'%');
        }

        $tipos = $tipos->orderBy('id', 'desc')->paginate(5);


        $tipos->getCollection()->transform(function ($tipo) {
            return [
                'id' => $tipo->id,
                'name' => $tipo->name,
                'imagen' => "/storage/$tipo->imagen"
            ];
        });

        return response()->json($tipos);
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
                $coverPath = $request->file('imagen')->store('tipos', 'public');
            }

            // Crear el pack
            $tipo = Tipo::create([
                'name' => $request->name,
                'imagen' => $coverPath
            ]);



            DB::commit();

            return response()->json(['message' => 'tipo creada con éxito', 'tipo' => $tipo], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Ocurrió un error al guardar el tipo'.$th], 500);
        }
    }

    /**
     * Ver detalles de una carpeta
     */
    public function show($id)
    {
        $tipo = Tipo::findOrFail($id);


        return response()->json($tipo);
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
            $tipo = Tipo::findOrFail($id);

            // Verificar si se subió una nueva imagen
            if ($request->hasFile('imagen')) {
                // Eliminar la imagen anterior si existe
                if ($tipo->imagen) {
                    Storage::disk('public')->delete($tipo->imagen);
                }

                // Guardar la nueva imagen
                $path = $request->file('imagen')->store('tipos', 'public');
                $tipo->imagen = $path;
            }

            // Actualizar solo si hay cambios en el nombre
            if ($tipo->name !== $validatedData['name']) {
                $tipo->name = $validatedData['name'];
            }

            // Guardar cambios del tipo
            $tipo->save();



            DB::commit();

            return response()->json(['message' => 'tipo actualizado con éxito', 'tipo' => $tipo], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el tipo', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar una carpeta (Solo Admin)
     */
    public function destroy($id)
    {
        $tipo = Tipo::findOrFail($id);
        $tipo->estado = 0;

        $tipo->save();

        return response()->json(['message' => 'Carpeta eliminada']);
    }

}
