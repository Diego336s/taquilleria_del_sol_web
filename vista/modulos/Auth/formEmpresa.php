<form id="form_login_empresa">

    <div class="mb-3">
        <label for="loginEmpresa" class="form-label">NIT de Empresa</label>
        <input type="number" class="form-control" id="id_Nit_Empresa" name="loginEmpresa" required>
    </div>

    <div class="mb-4">
        <label for="passEmpresa" class="form-label">Contraseña</label>
        <div class="input-group">
            <input type="password" class="form-control" id="id_Password_Empresa" name="login_User_Password" required>
            <button class="btn btn-outline-secondary" type="button" id="toggleBtn_Empresa">
                <i class="fas fa-eye" id="toggleIcon_Empresa"></i>
            </button>
        </div>
    </div>

    <!-- Se cambia el botón por un enlace que redirige directamente al dashboard de empresa -->
    <button type="submit" class="btn btn-secondary btn_empresa w-100 py-2">Iniciar como Usuario</button>

</form>