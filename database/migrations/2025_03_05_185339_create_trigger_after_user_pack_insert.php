<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cada vez que un profesor compra un pack, se deben agregar sus libros en assigned_books
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER after_user_pack_insert
            AFTER INSERT ON pack_users
            FOR EACH ROW
            BEGIN
                INSERT INTO assigned_books (book_id, teacher_id, estado, created_at, updated_at)
                SELECT book_id, NEW.user_id, 1, NOW(), NOW()
                FROM pack_books WHERE pack_id = NEW.pack_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_user_pack_insert');
    }
};
