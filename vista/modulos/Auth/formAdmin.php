<form method="post">    
    <div class="mb-3">
        <label for="loginAdmin" class="form-label">Usuario de Administrador</label>
        <input type="text" class="form-control" id="loginAdmin" name="loginAdmin" required>
    </div>

    <div class="mb-4">
        <label for="passAdmin" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="passAdmin" name="passAdmin" required>
    </div>
    
    <a href="index.php?ruta=dashboard-admin" class="btn btn-secondary btn_admin w-100 py-2">Iniciar como Administrador</a>
</form>