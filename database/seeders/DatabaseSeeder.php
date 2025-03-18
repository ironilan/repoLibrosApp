<?php

namespace Database\Seeders;

use App\Models\BookVersion;
use App\Models\Config;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        // Crear un usuario específico con ID controlado
        $user = User::factory()->create([
            'name' => 'Alexis',
            'celular' => '972843376',
            'tipo_doc' => 'dni',
            'num_doc' => 46894256,
            'password' => Hash::make('123456789'),
            'email' => 'alexis@gmail.com',
        ]);

        Config::create([
            'logo' => 'logo.png',
            'logo_horizontal' => 'logo.png',
            'favicon' => 'logo.png',
            'titulo' => 'Libros App',
            'estado' => 1
        ]);

        // Crear más usuarios si es necesario
        User::factory(5)->create(); // Ajusta la cantidad según sea necesario

        // Ejecutar otros seeders después de asegurarnos de que hay usuarios
        $this->call([
            RolesypermisosSeeder::class,
            BookSeeder::class, // Ahora los libros tendrán usuarios válidos
            PackSeeder::class,
            TipoSeeder::class,
            FolderSeeder::class,
            BookVersionSeeder::class,
        ]);
    }
}
