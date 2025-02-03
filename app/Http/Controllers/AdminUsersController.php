<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Role;
use App\Models\User;
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
            $foto_id = \DB::table('fotos')->insertGetId([
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
