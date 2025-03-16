<?php

namespace App\Http\Controllers\profesor;

use App\Http\Controllers\Controller;
use App\Models\FolderTipoBook;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecursoController extends Controller
{
    public function getRecursos($idFolderTipoBook)
    {
        // Buscar la relación en la tabla pivote
        $folderTipoBook = FolderTipoBook::with('book', 'resources')->find($idFolderTipoBook);

        // Verificar si existe la relación
        if (!$folderTipoBook) {
            return response()->json(['error' => 'No se encontraron datos para este ID'], 404);
        }

        // Construir la respuesta con el libro y los recursos
        $data = [
            'book' => $folderTipoBook->book, // Información del libro asociado
            'resources' => $folderTipoBook->resources->where('estado', 1) // Recursos filtrados por estado activo
        ];

        return response()->json($data);
    }



    public function download($id)
    {
        // Buscar el recurso en la base de datos
        $resource = DB::table('resources')->where('id', $id)->first();

        if (!$resource) {
            return response()->json(['error' => 'Recurso no encontrado'], 404);
        }

        // Verificar si el tipo es PDF
        if ($resource->tipo !== 'pdf') {
            return response()->json(['error' => 'El recurso no es un PDF'], 403);
        }

        // Definir la ruta del archivo
        $filePath = storage_path('app/public/recursos/' . $resource->path);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        // Descargar el archivo
        return response()->download($filePath, $resource->name . '.pdf', [
            'Content-Type' => 'application/pdf'
        ]);
    }
}
