<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'code', 'fecha_publicacion', 'autor', 'cover_image', 'uploaded_by', 'estado', 'libro_profe', 'libro_alumno'];



    public function uploader() {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function versions() {
        return $this->hasMany(BookVersion::class);
    }

    public function assignedTeachers() {
        return $this->belongsToMany(User::class, 'assigned_books', 'book_id', 'teacher_id');
    }


    public function folders()
    {
        return $this->belongsToMany(Folder::class, 'folder_tipo_books', 'book_id', 'folder_id')
                    ->withPivot('tipo_id')
                    ->withTimestamps();
    }

    public function packs()
    {
        return $this->belongsToMany(Pack::class, 'pack_books', 'book_id', 'pack_id')
                    ->withTimestamps(); // Agrega timestamps a la tabla pivot si existen
    }
}
