<form id="form_login_usuario">

    <div class="mb-3">
        <label for="id_correo" class="form-label">Usuario o correo</label>
        <input type="text" class="form-control" id="id_correo" name="login_User_correo" required>
    </div>

    <div class="mb-4">
        <label for="id_Password" class="form-label">Contraseña</label>
        <div class="input-group">
            <input type="password" class="form-control" id="id_Password_Usuario" name="clave" required>
            <button class="btn btn-outline-secondary" type="button" id="toggleBtn_Usuario">
                <i class="fas fa-eye" id="toggleIcon_Usuario"></i>
            </button>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="index.php?ruta=fogout_contraseña">¿Olvidaste tu contraseña?</a>

    </div>
    <button type="submit" class="btn btn-secondary btn_usuario w-100 py-2">Iniciar como Usuario</button>
    <p class="mt-3 text-center small">
        ¿No tienes cuenta? <a href="registro">Regístrate aquí</a>
    </p>
</form>