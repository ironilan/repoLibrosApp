<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = ['titulo', 'logo', 'logo_horizontal', 'favicon', 'estado'];
}
