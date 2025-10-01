<form method="post">
    
    <h2 class="h4 mb-4 text-center">Registro de Usuario</h2>
    
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_Nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="id_Nombre" name="regNombre" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_Apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="id_Apellido" name="regApellido" required>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="id_Documento" class="form-label">Documento</label>
                <input type="number" class="form-control" id="id_Documento" name="Documento" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_fecha_Nacimiento" class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" id="id_fecha_Nacimiento" name="fecha_Nacimiento" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_Telefono" class="form-label">Teléfono</label>
                <input type="number" class="form-control" id="id_Telefono" name="Telefono" required>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="regEmail" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="regEmail" name="regEmail" required>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="regPassword" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="regPassword" name="regPassword" required>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="regPasswordConfirm" class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control" id="regPasswordConfirm" name="regPasswordConfirm" required>
            </div>
        </div>
    </div>
    
    <button type="submit" class="btn btn-success w-100 py-2 mt-4">Registrarme</button>

    <p class="mt-3 text-center small">
        ¿Ya tienes cuenta? <a href="login">Inicia sesión aquí</a>
    </p>

</form>