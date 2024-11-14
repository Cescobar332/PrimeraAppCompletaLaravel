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
            'ruta_foto' => 'nullable|image|max:2048' // Validación para la imagen
        ]);

        // Crear un nuevo usuario con los datos del formulario
        $entrada = $request->all();

        // Si se sube una imagen, guardarla y asignar la ruta
        if ($archivo = $request->file('ruta_foto')) {
            $nombre = time() . '_' . $archivo->getClientOriginalName(); // Usar un nombre único para evitar conflictos
            $archivo->move(public_path('images'), $nombre); // Mueve la imagen a la carpeta `public/images`
            $entrada['ruta_foto'] = 'images/' . $nombre; // Guarda la ruta completa
        }

        // Crear el usuario con la información del formulario
        User::create([
            'name' => $entrada['name'],
            'email' => $entrada['email'],
            'password' => bcrypt($entrada['password']),
            'role_id' => $entrada['role_id'],
            'ruta_foto' => $entrada['ruta_foto'] ?? null // Asigna null si no se sube foto
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
