<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'tipo_doc',
        'num_doc',
        'celular',
        'email',
        'estado',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function folders() {
        return $this->hasMany(Folder::class);
    }

    public function uploadedBooks() {
        return $this->hasMany(Book::class, 'uploaded_by');
    }

    public function assignedBooks() {
        return $this->belongsToMany(Book::class, 'assigned_books', 'teacher_id', 'book_id');
    }


    public function libros() {
        return $this->belongsToMany(Book::class, 'assigned_books', 'teacher_id', 'book_id');
    }

    public function packs() {
        return $this->belongsToMany(Pack::class, 'pack_users', 'user_id', 'pack_id');
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
}
