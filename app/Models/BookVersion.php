<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookVersion extends Model
{
    protected $table = 'book_versions';

    protected $fillable = ['book_id', 'version_type', 'file_path', 'file_type', 'file_size', 'estado'];

    public function book() {
        return $this->belongsTo(Book::class);
    }
}
