<form method="post">

    <div class="mb-3">
        <label for="loginUser" class="form-label">Usuario o correo</label>
        <input type="text" class="form-control" id="loginUser" name="loginUser" required>
    </div>

    <div class="mb-4">
        <label for="loginPassword" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="index.php?ruta=fogout_contraseña">¿Olvidaste tu contraseña?</a>

    </div>

    <button type="submit" class="btn btn-secondary btn_usuario w-100 py-2">Iniciar como Usuario</button>
    <p class="mt-3 text-center small">
        ¿No tienes cuenta? <a href="registro">Regístrate aquí</a>
    </p>
</form>