<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = ['name', 'parent_id', 'user_id', 'imagen', 'estado'];

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id')->where('estado', 1);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // Relación con tipos y libros (a través de folder_tipo_books)
    public function tiposBooks()
    {
        return $this->belongsToMany(Tipo::class, 'folder_tipo_books', 'folder_id', 'tipo_id')
            ->withPivot('book_id')
            ->withTimestamps();
    }


    public function resources()
    {
        return $this->hasMany(Resource::class, 'folder_tipo_book_id');
    }
}
