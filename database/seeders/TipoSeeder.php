<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos')->insert([
            [
                'name' => 'Materiales',
                'estado' => 1
            ],
            [
                'name' => 'Carpeta pedagógica',
                'estado' => 1
            ],
        ]);
    }
}
