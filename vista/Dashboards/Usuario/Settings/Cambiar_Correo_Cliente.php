<title>Cambiar Correo</title>


<!-- Enlazamos la hoja de estilos principal para mantener la coherencia -->
<link rel="stylesheet" href="vista/css/main.css?v=1.1">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding-top: 2rem; padding-bottom: 2rem;">
    <div class="card p-4 shadow login-card" style="max-width: 500px; width: 100%;">
        <div class="card-body">
            
            <div class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/2991/2991144.png" alt="Icono de Correo" class="login-logo mb-3">
                <h1 class="h3 text-white">Cambiar Correo Electrónico</h1>
                <p class="text-white-50">Ingresa tu nuevo correo y confirma la operación con tu contraseña actual.</p>
            </div>

            <form id="form_cambiar_correo_cliente">

                <div class="mb-3">
                    <label for="new_email" class="form-label">Nuevo Correo Electrónico</label>
                    <input type="email" class="form-control" id="id_correo_config_cliente" name="new_email" required>
                </div>

                <div class="mb-3">
                    <label for="confirm_new_email" class="form-label">Confirmar Nuevo Correo</label>
                    <input type="email" class="form-control" id="id_confirm_correo_config_cliente" name="confirm_new_email" required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100 py-2">
                    Actualizar Correo
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
