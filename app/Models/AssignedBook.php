<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedBook extends Model
{
    protected $fillable = ['book_id', 'teacher_id'];

    public function book() {
        return $this->belongsTo(Book::class);
    }

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
