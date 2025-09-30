<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 shadow login-card" style="max-width: 450px; width: 100%;">
        <div class="card-body">
            <form method="post">
                
                <h2 class="h4 mb-3 text-center">Restablecer Contraseña</h2>
                <p class="text-center text-muted small mb-4">Ingresa tu correo para recibir un código y poder restablecer tu contraseña.</p>
                
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="recEmail" name="recEmail" placeholder="Correo electrónico" required>
                    <label for="recEmail">Correo electrónico</label>
                </div>
                
                <button type="submit" class="btn btn-secondary btn_usuario w-100 py-2">Recibir correo</button>

                <p class="mt-3 text-center small">
                    <a href="index.php?ruta=login">Volver a Iniciar Sesión</a>
                </p>
            </form>
        </div>
    </div>
</div>