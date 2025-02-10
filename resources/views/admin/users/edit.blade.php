<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar usuario</title>

    <!-- Incluir Bootstrap CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Editar usuario</h1>

        <!-- Mostrar errores de validación si existen -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario para editar el usuario -->
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data"
            class="shadow p-4 bg-light rounded" id="userForm">
            @csrf
            @method('PUT') <!-- Indica que es una actualización -->

            <!-- Campo Nombre -->
            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                    required>
            </div>

            <!-- Campo Email (no editable en muchos casos, pero puedes quitar el readonly si lo necesitas) -->
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                    readonly>
            </div>

            <!-- Campo Contraseña (opcional, solo si se desea cambiar) -->
            <div class="mb-3">
                <label for="password" class="form-label">Nueva Contraseña (dejar en blanco si no cambia):</label>
                <input type="password" name="password" class="form-control">
            </div>

            <!-- Campo Confirmación de Contraseña -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar nueva contraseña:</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <!-- Campo Rol -->
            <div class="mb-3">
                <label for="role_id" class="form-label">Rol:</label>
                <select name="role_id" class="form-select" required>
                    <option value="">Seleccionar rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                            {{ $role->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Campo para cambiar foto -->
            <div class="mb-3">
                <label for="foto_id" class="form-label">Foto:</label>
                <input type="file" name="foto_id" class="form-control" accept="image/*">

                <!-- Mostrar la imagen actual si existe -->
                @if ($user->foto)
                    <div class="mt-2">
                        <p>Foto actual:</p>
                        <img src="{{ asset($user->foto->ruta_foto) }}" alt="Foto del usuario" class="img-thumbnail"
                            width="150">
                    </div>
                @endif
            </div>

            <!-- Botones para actualizar y cancelar -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Actualizar usuario</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Incluir Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
