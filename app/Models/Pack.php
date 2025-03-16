<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{


    use HasFactory;

    protected $table = 'packs'; // Nombre de la tabla en la BD

    protected $fillable = ['name', 'imagen', 'estado'];

    /**
     * Relación muchos a muchos con Book usando la tabla pivot pack_books
     */
    public function books()
    {
        return $this->belongsToMany(Book::class, 'pack_books', 'pack_id', 'book_id')
                    ->withTimestamps(); // Agrega timestamps a la tabla pivot si existen
    }
}
