<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $config = Config::find(1);

        return view('admin.configuracion.index', compact('config'));
    }



    public function update(Request $request, $id)
    {
        // 🔍 Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|min:3',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:1024', // Máx 1MB
            'logo_horizontal' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:1024',
            'favicon' => 'nullable|image|mimes:png,ico|max:512', // Máx 512KB
        ]);

        // 🔄 Buscar la configuración en la base de datos
        $config = Config::findOrFail($id);

        // 📁 Asegurar que la carpeta de almacenamiento existe
        if (!Storage::exists('public/configuracion')) {
            Storage::makeDirectory('public/configuracion');
        }

        // 📁 Manejo de imágenes (logo)
        if ($request->hasFile('logo')) {
            // Eliminar el logo anterior si existe
            if ($config->logo && Storage::exists("public/configuracion/{$config->logo}")) {
                Storage::delete("public/configuracion/{$config->logo}");
            }
            // Guardar nuevo logo con un nombre único

            $path = $request->file('logo')->store('configuracion', 'public');
            $config->logo = $path;
        }

        // 📁 Manejo de imágenes (logo horizontal)
        if ($request->hasFile('logo_horizontal')) {
            if ($config->logo_horizontal && Storage::exists("public/configuracion/{$config->logo_horizontal}")) {
                Storage::delete("public/configuracion/{$config->logo_horizontal}");
            }

            $pathLogoHor = $request->file('logo_horizontal')->store('configuracion', 'public');
            $config->logo_horizontal = $pathLogoHor;
        }

        // 📁 Manejo de imágenes (favicon)
        if ($request->hasFile('favicon')) {
            if ($config->favicon && Storage::exists("public/configuracion/{$config->favicon}")) {
                Storage::delete("public/configuracion/{$config->favicon}");
            }

            $pathFavicon = $request->file('favicon')->store('configuracion', 'public');
            $config->favicon = $pathFavicon;
        }

        // 🔄 Guardar el título
        $config->titulo = $request->name;

        // 💾 Guardar cambios en la base de datos
        $config->save();

        // ✅ Retornar respuesta JSON
        return response()->json([
            'message' => 'Configuración actualizada con éxito',
            'config' => $config
        ], 200);
    }



    public function show($id)
    {
        // Buscar la configuración en la base de datos
        $config = Config::findOrFail($id);

        // Construir la respuesta en formato JSON
        return response()->json([
            'id' => $config->id,
            'name' => $config->titulo,
            'logo' => $config->logo ? asset("storage/" . $config->logo) : null,
            'logo_horizontal' => $config->logo_horizontal ? asset("storage/" . $config->logo_horizontal) : null,
            'favicon' => $config->favicon ? asset("storage/" . $config->favicon) : null,
        ]);
    }
}
