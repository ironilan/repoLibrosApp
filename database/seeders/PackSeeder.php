<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        // Insertar packs de libros
        DB::table('packs')->insert([
            [
                'name' => 'Pack 2 Libros',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pack 4 Libros',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Obtener IDs de libros existentes
        $bookIds = DB::table('books')->pluck('id')->toArray();

        // Verificar si hay suficientes libros para crear los packs
        if (count($bookIds) >= 6) {
            shuffle($bookIds);

            // Insertar libros en los packs
            DB::table('pack_books')->insert([
                // Pack 2 Libros
                [
                    'pack_id' => 1,
                    'book_id' => $bookIds[0],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'pack_id' => 1,
                    'book_id' => $bookIds[1],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                // Pack 4 Libros
                [
                    'pack_id' => 2,
                    'book_id' => $bookIds[2],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'pack_id' => 2,
                    'book_id' => $bookIds[3],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'pack_id' => 2,
                    'book_id' => $bookIds[4],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'pack_id' => 2,
                    'book_id' => $bookIds[5],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
    }
}
