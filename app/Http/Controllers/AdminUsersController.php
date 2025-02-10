<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|confirmed|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'foto_id' => 'nullable|image|max:2048' // Validación para la imagen
        ]);

        $entrada = $request->all();
        $foto_id = null; // Inicializa la variable foto_id

        if ($archivo = $request->file('foto_id')) {
            $nombre = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('images'), $nombre);
            $ruta_foto = 'images/' . $nombre;

            // Guardamos la imagen en la tabla fotos y obtenemos su ID
            $foto_id = DB::table('fotos')->insertGetId([
                'ruta_foto' => $ruta_foto,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Crear el usuario con el ID de la foto
        User::create([
            'name' => $entrada['name'],
            'email' => $entrada['email'],
            'password' => bcrypt($entrada['password']),
            'role_id' => $entrada['role_id'],
            'foto_id' => $foto_id // Guardamos el ID de la foto en la tabla users
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Validaciones
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'foto_id' => 'nullable|image|max:2048'
        ]);

        // Actualizar datos del usuario
        $user->name = $request->name;
        $user->role_id = $request->role_id;

        // Si se proporciona una nueva contraseña, se actualiza
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Manejo de la foto
        if ($request->hasFile('foto_id')) {
            // Guardar la nueva imagen
            $archivo = $request->file('foto_id');
            $nombre = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('images'), $nombre);
            $ruta_foto = 'images/' . $nombre;

            // Si el usuario ya tenía una foto, eliminar la anterior
            if ($user->foto_id) {
                $foto = Foto::find($user->foto_id);
                if ($foto) {
                    unlink(public_path($foto->ruta_foto)); // Eliminar archivo
                    $foto->delete(); // Eliminar registro en la BD
                }
            }

            // Guardar la nueva foto en la base de datos
            $foto_id = DB::table('fotos')->insertGetId([
                'ruta_foto' => $ruta_foto,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Asignar el nuevo ID de la foto al usuario
            $user->foto_id = $foto_id;
        }

        // Guardar cambios
        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Si el usuario tiene una foto, eliminarla del sistema de archivos y de la BD
        if ($user->foto_id) {
            $foto = Foto::find($user->foto_id);
            if ($foto) {
                // Eliminar archivo físico si existe
                if (file_exists(public_path($foto->ruta_foto))) {
                    unlink(public_path($foto->ruta_foto));
                }
                $foto->delete(); // Eliminar el registro de la foto
            }
        }

        // Eliminar el usuario
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente');
    }
}
