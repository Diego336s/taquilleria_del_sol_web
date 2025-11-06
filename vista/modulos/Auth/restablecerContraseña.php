<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 shadow login-card" style="max-width: 450px; width: 100%;">
        <div class="card-body">
            <form id="form_restablecer_clave">

                <h1 class="h4 mb-3 text-center">Restablecer Contraseña</h1>
                <p class="text-center text-white-50 small mb-4">
                    Ingresa tu nueva contraseña.
                </p>

                <div class="mb-3">
                    <label for="id_nueva_clave" class="form-label">Nueva Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="id_nueva_clave" name="nueva_clave" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleBtn_nueva_clave">
                            <i class="fas fa-eye" id="toggleIcon_nueva_clave"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="id_confirmar_nueva_clave" class="form-label">Confirmar Nueva Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="id_confirmar_nueva_clave" name="confirmar_nueva_clave" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleBtn_confirmar_clave">
                            <i class="fas fa-eye" id="toggleIcon_confirmar_clave"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2">Cambiar Contraseña</button>
                <p class="mt-3 text-center small">
                    <a href="index.php?ruta=login">Volver a Iniciar Sesión</a>
                </p>
            </form>
        </div>
    </div>
</div>