<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $fillable = ['name', 'estado', 'imagen'];


    public function folders()
    {
        return $this->belongsToMany(Folder::class, 'folder_tipo_books', 'tipo_id', 'folder_id')
                    ->withPivot('book_id')
                    ->withTimestamps();
    }
}
