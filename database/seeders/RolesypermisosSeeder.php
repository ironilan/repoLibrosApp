<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesypermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de permisos
        $permissions = [
            'users', 'roles', 'books', 'folders', 'tipos', 'resources', 'downloads', 'reports', 'settings'
        ];

        // Crear permisos CRUD para cada entidad
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => "view-$permission"]);
            Permission::firstOrCreate(['name' => "create-$permission"]);
            Permission::firstOrCreate(['name' => "edit-$permission"]);
            Permission::firstOrCreate(['name' => "delete-$permission"]);
        }

        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $teacherRole = Role::firstOrCreate(['name' => 'Profesor']);

        // Asignar todos los permisos al Administrador
        $adminRole->syncPermissions(Permission::all());

        // Asignar permisos específicos al Profesor
        $teacherPermissions = [
            'view-books', 'view-folders', 'view-tipos', 'view-resources', 'view-downloads', 'view-reports', 'view-resources'
        ];
        $teacherRole->syncPermissions($teacherPermissions);


        $roleName = 'Administrador';
        $roleNameProfe = 'Profesor';
        $user = User::find(1);
        $user->assignRole($roleName);

        $profes = User::where('id', '<>', 1)->get();

        foreach ($profes as $key => $profe) {
            $prof = User::find($profe->id);
            $prof->assignRole($roleNameProfe);
        }
    }
}
