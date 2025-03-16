<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('books')->insert([
            [
                'title' => 'El Principito',
                'autor' => 'Antoine de Saint-Exupéry',
                'code' => Str::random(10),
                'fecha_publicacion' => '1943-04-06',
                'cover_image' => 'el_principito.jpg',
                'uploaded_by' => 1,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Cien años de soledad',
                'autor' => 'Gabriel García Márquez',
                'code' => Str::random(10),
                'fecha_publicacion' => '1967-05-30',
                'cover_image' => 'cien_anos.jpg',
                'uploaded_by' => 1,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => '1984',
                'autor' => 'George Orwell',
                'code' => Str::random(10),
                'fecha_publicacion' => '1949-06-08',
                'cover_image' => '1984.jpg',
                'uploaded_by' => 2,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Don Quijote de la Mancha',
                'autor' => 'Miguel de Cervantes',
                'code' => Str::random(10),
                'fecha_publicacion' => '1605-01-16',
                'cover_image' => 'don_quijote.jpg',
                'uploaded_by' => 2,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Orgullo y prejuicio',
                'autor' => 'Jane Austen',
                'code' => Str::random(10),
                'fecha_publicacion' => '1813-01-28',
                'cover_image' => 'orgullo_prejuicio.jpg',
                'uploaded_by' => 3,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Moby Dick',
                'autor' => 'Herman Melville',
                'code' => Str::random(10),
                'fecha_publicacion' => '1851-10-18',
                'cover_image' => 'moby_dick.jpg',
                'uploaded_by' => 3,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Ulises',
                'autor' => 'James Joyce',
                'code' => Str::random(10),
                'fecha_publicacion' => '1922-02-02',
                'cover_image' => 'ulises.jpg',
                'uploaded_by' => 4,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Crimen y castigo',
                'autor' => 'Fiódor Dostoyevski',
                'code' => Str::random(10),
                'fecha_publicacion' => '1866-01-01',
                'cover_image' => 'crimen_castigo.jpg',
                'uploaded_by' => 4,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'En busca del tiempo perdido',
                'autor' => 'Marcel Proust',
                'code' => Str::random(10),
                'fecha_publicacion' => '1913-11-14',
                'cover_image' => 'en_busca_del_tiempo_perdido.jpg',
                'uploaded_by' => 5,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'La Odisea',
                'autor' => 'Homero',
                'code' => Str::random(10),
                'fecha_publicacion' => '800-01-01',
                'cover_image' => 'la_odisea.jpg',
                'uploaded_by' => 5,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }
}
