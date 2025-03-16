<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('folders')->insert([
            [
                'name' => 'Canciones',
                'imagen' => 'icono.png',
                'parent_id' => null,
                'user_id' => 1,
                'estado' => 1
            ],
            [
                'name' => 'Troqueles',
                'imagen' => 'icono.png',
                'parent_id' => null,
                'user_id' => 1,
                'estado' => 1
            ],
            [
                'name' => 'Proyectos',
                'imagen' => 'icono.png',
                'parent_id' => null,
                'user_id' => 1,
                'estado' => 1
            ],
            [
                'name' => 'Refuerzos',
                'imagen' => 'icono.png',
                'parent_id' => null,
                'user_id' => 1,
                'estado' => 1
            ],
            [
                'name' => 'Programaciones',
                'imagen' => 'icono.png',
                'parent_id' => null,
                'user_id' => 1,
                'estado' => 1
            ],
            [
                'name' => 'Sesiones',
                'imagen' => 'icono.png',
                'parent_id' => null,
                'user_id' => 1,
                'estado' => 1
            ],
            [
                'name' => 'Lista de cotejo',
                'imagen' => 'icono.png',
                'parent_id' => null,
                'user_id' => 1,
                'estado' => 1
            ],
        ]);


    }
}
