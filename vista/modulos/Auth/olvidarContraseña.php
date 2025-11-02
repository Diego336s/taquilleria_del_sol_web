<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 shadow login-card" style="max-width: 450px; width: 100%;">
        <div class="card-body">
            <form id="form_codigo_verificacion">

                <h1 class="h4 mb-3 text-center">Restablecer Contraseña</h1>
                <p class="text-center text-muted small mb-4">Ingresa tu correo para recibir un código y poder restablecer tu contraseña.</p>

                <div class="mb-3">
                    <label for="id_correo" class="form-label">Usuario o correo</label>
                    <input type="text" class="form-control" id="id_correo_verificacion" name="login_User_correo" required>
                </div>

                <button type="submit" class="btn btn-secondary btn_usuario w-100 py-2 mb-3">Recibir correo</button>
                
                <div class="text-center">
                    <a href="index.php?ruta=restablecer_contraseña" class="btn btn-outline-light restore-link mb-2 w-100">
                        <i class="fas fa-key me-2"></i>Restablecer Contraseña
                    </a>
                </div>
                <p class="mt-3 text-center small">
                    <a href="index.php?ruta=login">Volver a Iniciar Sesión</a>
                </p>



            </form>
        </div>
    </div>
</div>