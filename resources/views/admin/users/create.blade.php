<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crear usuario</title>

    <!-- Incluir Bootstrap CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Crear nuevo usuario</h1>

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

        <!-- Formulario para crear un nuevo usuario -->
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="shadow p-4 bg-light rounded" id="userForm">
            @csrf <!-- Protección contra ataques CSRF -->

            <!-- Campo Nombre -->
            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <!-- Campo Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <!-- Campo Verificación de Email -->
            <div class="mb-3">
                <label for="email_confirmation" class="form-label">Confirmar email:</label>
                <input type="email" name="email_confirmation" class="form-control" value="{{ old('email_confirmation') }}" required>
            </div>

            <!-- Campo Contraseña -->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <!-- Campo Confirmación de Contraseña -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar contraseña:</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <!-- Campo Rol -->
            <div class="mb-3">
                <label for="role_id" class="form-label">Rol:</label>
                <select name="role_id" class="form-select" required>
                    <option value="">Seleccionar rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Campo para subir foto -->
            <div class="mb-3">
                <label for="foto_id" class="form-label">Foto:</label>
                <input type="file" name="foto_id" class="form-control" accept="image/*">
            </div>

            <!-- Botones para crear y limpiar formulario -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Crear usuario</button>
                <button type="button" class="btn btn-secondary" onclick="clearForm()">Limpiar</button>
            </div>
        </form>
    </div>

    <!-- JavaScript para limpiar el formulario -->
    <script>
        function clearForm() {
            document.getElementById("userForm").reset();
        }
    </script>

    <!-- Incluir Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>