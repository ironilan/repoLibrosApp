<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\BookVersion;

class BookVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los primeros 10 libros
        $books = Book::limit(10)->get();

        if ($books->isEmpty()) {
            dump("⚠️ No hay libros en la base de datos. Verifica BookSeeder.");
            return;
        }

        foreach ($books as $book) {
            // Crear versión para profesor
            BookVersion::create([
                'book_id' => $book->id,
                'version_type' => 'profesor',
                'file_path' => 'books/' . $book->id . '_profesor.pdf',
                'file_type' => 'pdf',
                'file_size' => rand(500000, 5000000),
                'estado' => 1
            ]);

            // Crear versión para alumno
            BookVersion::create([
                'book_id' => $book->id,
                'version_type' => 'alumno',
                'file_path' => 'books/' . $book->id . '_alumno.pdf',
                'file_type' => 'pdf',
                'file_size' => rand(500000, 5000000),
                'estado' => 1
            ]);
        }

        dump("✅ BookVersionSeeder ejecutado correctamente.");
    }
}
