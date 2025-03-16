<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolderTipoBook extends Model
{
    protected $table = 'folder_tipo_books';

    protected $fillable = ['folder_id', 'tipo_id', 'book_id'];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    public function resources()
    {
        return $this->hasMany(Resource::class, 'folder_tipo_book_id');
    }
}
