<form id="form_login_admin">
    <div class="mb-3">
        <label for="loginAdmin" class="form-label">Documento de Administrador</label>
        <input type="text" class="form-control" id="id_documento" name="loginAdmin" required>
    </div>

    <div class="mb-4">
        <label for="passAdmin" class="form-label">Contraseña</label>
        <div class="input-group">
            <input type="password" class="form-control" id="id_Password_Admin" name="login_User_Password" required>
            <button class="btn btn-outline-secondary" type="button" id="toggleBtn_Admin">
                <i class="fas fa-eye" id="toggleIcon_Admin"></i>
            </button>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="index.php?ruta=recibir_correo">¿Olvidaste tu contraseña?</a>
    </div>

    <button type="submit" class="btn btn-secondary btn_admin w-100 py-2">Iniciar como Administrador</button>

</form>