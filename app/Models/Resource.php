<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $table = 'resources'; // Nombre de la tabla en la BD

    protected $fillable = ['folder_tipo_book_id', 'name', 'estado', 'tipo', 'path', 'size', 'drive_link'];


    public function folderTipoBook()
    {
        return $this->belongsTo(FolderTipoBook::class, 'folder_tipo_book_id');
    }
}
