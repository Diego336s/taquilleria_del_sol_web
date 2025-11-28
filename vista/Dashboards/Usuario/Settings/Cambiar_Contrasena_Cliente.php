<title>Cambiar Contraseña</title>


<!-- Enlazamos la hoja de estilos principal para mantener la coherencia -->
<link rel="stylesheet" href="vista/css/main.css?v=1.1">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding-top: 2rem; padding-bottom: 2rem;">
    <div class="card p-4 shadow login-card" style="max-width: 500px; width: 100%;">
        <div class="card-body">
            
            <div class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/6146/6146587.png" alt="Icono de Candado" class="login-logo mb-3">
                <h1 class="h3 text-white">Cambiar Contraseña</h1>
                <p class="text-white-50">Para proteger tu cuenta, ingresa tu contraseña actual antes de elegir una nueva.</p>
            </div>

            <form id="form_cambiar_clave_config_cliente">

                <div class="mb-3">
                    <label for="new_password" class="form-label">Nueva Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="id_nueva_clave_config" name="id_nueva_clave_config" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleBtn_nueva_clave_config">
                            <i class="fas fa-eye" id="toggleIcon_nueva_clave_config"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="confirm_new_password" class="form-label">Confirmar Nueva Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="id_confirm_nueva_clave_config" name="id_confirm_nueva_clave_config" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleBtn_confirm_nueva_clave_config">
                            <i class="fas fa-eye" id="toggleIcon_confirm_nueva_clave_config"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100 py-2">
                    Cambiar Contraseña
                    </button>
                </div>
            </form>

            <!-- Volver a Configuración -->
            <p class="mt-4 text-center small">
                <a href="index.php?ruta=configuracion_cliente">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Configuración
                </a>
            </p>

        </div>
    </div>
</div>

<script src='./vista/js/Usuario/Dashboard_Usuario.js'></script> <!-- Lógica del dashboard de usuario -->
