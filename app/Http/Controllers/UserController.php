<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {

        $roles = Role::all();
        return view('admin.users.index', compact('roles'));
    }

    public function list(Request $request)
    {
        // Filtrar solo usuarios activos
        $users = User::where('estado', 1)
            ->with('roles') // Cargar los roles de cada usuario
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

        // Formatear la respuesta para incluir los roles
        $users->getCollection()->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'num_doc' => $user->num_doc,
                'tipo_doc' => $user->tipo_doc,
                'celular' => $user->celular,
                'estado' => $user->estado,
                'roles' => $user->roles->pluck('name'), // Obtener los roles como un array
            ];
        });

        return response()->json($users);
    }


    public function store(UserRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Generar la contraseña con num_doc y hashearla
        $data['password'] = Hash::make($data['num_doc']);

        $user = User::create($data);

        //$user->assignRole('Profesor');

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'user' => $user,
        ], 201);
    }


    public function update(UserRequest $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $data = $request->validated();

        // Solo actualizar la contraseña si el num_doc cambia
        if ($request->num_doc !== $user->num_doc) {
            $data['password'] = Hash::make($request->num_doc);
        }

        $user->update($data);


        // $user->assignRole('Administrador');

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'user' => $user,
        ]);
    }


    public function delete($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->estado = 0;


        $user->update();

        return response()->json([
            'message' => 'Usuario eliminado correctamente.'
        ]);
    }
}
