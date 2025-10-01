<form method="post">
    
    <div class="mb-3">
        <label for="loginEmpresa" class="form-label">RUT o Nombre de Empresa</label>
        <input type="text" class="form-control" id="loginEmpresa" name="loginEmpresa" required>
    </div>

    <div class="mb-4">
        <label for="passEmpresa" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="passEmpresa" name="passEmpresa" required>
    </div>
    
    <!-- Se cambia el botón por un enlace que redirige directamente al dashboard de empresa -->
    <a href="index.php?ruta=dashboard-empresa" class="btn btn-secondary btn_empresa w-100 py-2">Iniciar como Empresa</a>
</form>