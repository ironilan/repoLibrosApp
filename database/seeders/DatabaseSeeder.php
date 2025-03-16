<?php

namespace Database\Seeders;

use App\Models\BookVersion;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        User::factory()->create([
            'name' => 'Alexis',
            'celular' => '972843376',
            'tipo_doc' => 'dni',
            'num_doc' => '46894256',
            'password' => Hash::make('123456789'),
            'email' => 'alexis@gmail.com',
        ]);

        // User::factory(10)->create();

        // $this->call(BookSeeder::class);
        // $this->call(PackSeeder::class);
        // $this->call(TipoSeeder::class);
        // $this->call(FolderSeeder::class);
        // $this->call(BookVersionSeeder::class);
        //$this->call(RolesypermisosSeeder::class);



    }
}
