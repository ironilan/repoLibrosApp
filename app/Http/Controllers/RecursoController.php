<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Folder;
use App\Models\FolderTipoBook;
use App\Models\Resource;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecursoController extends Controller
{
    public function viewCarpetasLibros()
    {
        $libros = Book::where('estado', 1)->orderBy('title', 'asc')->get();
        $tipos = Tipo::where('estado', 1)->orderBy('name', 'asc')->get();
        $folders = Folder::where('estado', 1)->orderBy('name', 'asc')->get();
        return view('admin.carpetas.libros', compact('tipos', 'folders', 'libros'));
    }


    public function listarRecursos(Request $request)
    {
        $search = $request->query('search');

        $tipos = Resource::where('estado', 1)->with(['folderTipoBook'])
            ->when($search, function ($query) use ($search) {
                $query->where('resoruce.name', 'LIKE', "%$search%");
            })
            ->orderBy('id', 'desc')->paginate(10);


        $tipos->getCollection()->transform(function ($pack) {
            return [
                'id' => $pack->id,
                'folder' => $pack->folderTipoBook ? $pack->folderTipoBook->folder->name : '',
                'folder_id' => $pack->folderTipoBook->folder_id,
                'book_id' => $pack->folderTipoBook->book_id,
                'tipo_id' => $pack->folderTipoBook->tipo_id,
                'tipo' => $pack->folderTipoBook->tipo ? $pack->folderTipoBook->tipo->name : '',
                'recurso' => $pack->name,
                'path' => "/storage/$pack->path",
                "drive_link" => $pack->drive_link,
                "name" => $pack->name,
                'book' => $pack->folderTipoBook->book ? $pack->folderTipoBook->book->title : ''
            ];
        });

        return response()->json($tipos);
    }


    public function updateRecursoFile(Request $request, $id)
    {
        // Validación
        $request->validate([
            'name' => 'required|string',
            'file' => 'nullable|file|max:20000', // El archivo es opcional, pero debe tener máximo 20MB
        ]);

        DB::beginTransaction();
        try {
            // Buscar el recurso
            $recurso = Resource::find($id);
            if (!$recurso) {
                return response()->json(['success' => false, 'message' => 'Recurso no encontrado.'], 404);
            }

            // Actualizar el nombre
            $recurso->name = $request->name;

            // Si se subió un nuevo archivo
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Eliminar el archivo anterior si existe
                if ($recurso->path) {
                    Storage::disk('public')->delete($recurso->path);
                }

                // Guardar el nuevo archivo
                $path = $file->store('recursos', 'public');

                // Actualizar los datos del archivo
                $recurso->tipo = $file->getClientOriginalExtension();
                $recurso->size = $file->getSize();
                $recurso->path = $path;
            }

            // Guardar cambios
            $recurso->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Recurso actualizado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar recurso: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al actualizar el recurso.', 'error' => $e->getMessage()], 500);
        }
    }


    public function updateRecursoAntes(Request $request, $id)
    {
        //dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'drive_link' => 'required|string',
            'tipo_id' => 'required|integer',
            'folder_id' => 'required|integer',
            'book_id' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {


            $recurso = Resource::findOrFail($id);
            $recurso->name = $request->name;
            $recurso->drive_link = $request->drive_link;
            $recurso->tipo_id = $request->tipo_id;
            $recurso->folder_id = $request->folder_id;
            $recurso->book_id = $request->book_id;
            $recurso->save();




            DB::commit();
            return response()->json(['success' => true, 'message' => 'Recurso actualizado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar recurso: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al actualizar el recurso.', 'error' => $e->getMessage()], 500);
        }
    }


    public function eliminar($id)
    {
        $combinacion = Resource::find($id);
        $combinacion->estado = 0;
        $combinacion->save();

        return response()->json(['message' => 'Combinación desactivadaa con éxito'], 200);
    }




    public function crearRecursosAntes(Request $request)
    {


        ini_set('memory_limit', '256M');
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        //dd($request->all());
        // Validación de la solicitud
        $request->validate([
            'tipo_id' => 'required|integer',
            'folder_id' => 'required|integer',
            'book_id' => 'required|integer',
            'files.*' => 'required|file|max:81920'
        ]);

        DB::beginTransaction();
        try {
            // **Verificar si el folder_tipo_book ya existe**
            $folderTipoBook = FolderTipoBook::firstOrCreate([
                'tipo_id' => $request->tipo_id,
                'folder_id' => $request->folder_id,
                'book_id' => $request->book_id,
            ]);

            // Verificar si se subieron archivos
            if ($request->hasFile('files')) {
                $files = $request->file('files');

                // Si los archivos vienen como array, recorrerlos
                if (is_array($files)) {
                    foreach ($files as $file) {
                        $this->guardarArchivo($file, $folderTipoBook->id);
                    }
                } else {
                    // Si solo se subió un archivo, guardarlo directamente
                    $this->guardarArchivo($files, $folderTipoBook->id);
                }
            } else {
                \Log::error('No se detectaron archivos en la solicitud.');
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Recurso creado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear recurso: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al crear el recurso.', 'error' => $e->getMessage()], 500);
        }
    }


    public function crearRecurso(Request $request)
    {
        //dd($request->all());
        // Validación de la solicitud
        $request->validate([
            'tipo_id' => 'required|integer',
            'folder_id' => 'required|integer',
            'book_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'drive_link' => 'required|url'
        ]);

        DB::beginTransaction();
        try {
            // Buscar o crear el registro en la tabla pivot folder_tipo_books
            $folderTipoBook = FolderTipoBook::firstOrCreate([
                'tipo_id' => $request->tipo_id,
                'folder_id' => $request->folder_id,
                'book_id' => $request->book_id,
                'estado' => 1
            ]);

            //dd($folderTipoBook);

            // Buscar el recurso en la tabla resources
            $recurso = new Resource();

            // Actualizar el recurso con los nuevos datos
            $recurso->folder_tipo_book_id  = $folderTipoBook->id;
            $recurso->name = $request->name;
            $recurso->drive_link = $request->drive_link;
            $recurso->estado = 1;
            $recurso->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Recurso actualizado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar recurso: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al actualizar el recurso.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateRecurso(Request $request, $id)
    {
        //dd($request->all());
        // Validación de la solicitud
        $request->validate([
            'tipo_id' => 'required|integer',
            'folder_id' => 'required|integer',
            'book_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'drive_link' => 'required|url'
        ]);

        DB::beginTransaction();
        try {
            // Buscar o crear el registro en la tabla pivot folder_tipo_books
            $folderTipoBook = FolderTipoBook::firstOrCreate([
                'tipo_id' => $request->tipo_id,
                'folder_id' => $request->folder_id,
                'book_id' => $request->book_id,
                'estado' => 1
            ]);

            //dd($folderTipoBook);

            // Buscar el recurso en la tabla resources
            $recurso = Resource::where('id', $id)
                ->where('folder_tipo_book_id', $folderTipoBook->id)
                ->firstOrFail();

            // Actualizar el recurso con los nuevos datos
            $recurso->name = $request->name;
            $recurso->drive_link = $request->drive_link;
            $recurso->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Recurso actualizado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar recurso: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al actualizar el recurso.', 'error' => $e->getMessage()], 500);
        }
    }



    private function guardarArchivo($file, $folderTipoBookId)
    {
        $path = $file->store('recursos', 'public');

        Resource::create([
            'folder_tipo_book_id' => $folderTipoBookId,
            'name' => str_replace('-', ' ', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), // Reemplaza guiones por espacios pero mantiene espacios normales
            'tipo' => $file->getClientOriginalExtension(),
            'path' => $path,
            'size' => $file->getSize(),
            'estado' => 1
        ]);
    }




    public function downloadFromDrive(Request $request)
    {
        $url = $request->input('drive_link');

        if (!$url) {
            return response()->json(['error' => 'No se proporcionó un enlace válido'], 400);
        }

        try {
            // Extraer el ID del archivo desde la URL de Google Drive
            preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches);
            if (!isset($matches[1])) {
                return response()->json(['error' => 'Enlace de Google Drive no válido'], 400);
            }

            $fileId = $matches[1];
            $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";

            // Crear un nombre de archivo temporal
            $fileName = "archivo_drive_" . time() . ".pdf"; // Ajusta la extensión si es necesario
            $filePath = storage_path("app/public/temp/{$fileName}");

            // Iniciar cURL para obtener el archivo
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $downloadUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecciones
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
            $fileContents = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Verificar si la respuesta es un error
            if ($httpCode !== 200 || empty($fileContents)) {
                return response()->json(['error' => 'No se pudo descargar el archivo de Google Drive. Código: ' . $httpCode], 500);
            }

            // Guardar el archivo temporalmente
            file_put_contents($filePath, $fileContents);

            // Verificar si el archivo realmente se guardó antes de intentar descargarlo
            if (!file_exists($filePath) || filesize($filePath) == 0) {
                return response()->json(['error' => 'El archivo descargado está vacío o no se pudo guardar correctamente.'], 500);
            }

            // Retornar el archivo como respuesta de descarga y eliminarlo después de enviarlo
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al descargar el archivo: ' . $e->getMessage()], 500);
        }
    }
}
