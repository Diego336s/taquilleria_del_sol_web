<!-- Enlazamos la hoja de estilos principal para mantener la coherencia -->
<link rel="stylesheet" href="vista/css/main.css?v=1.1">
<style>
    .config-option {
        display: flex;
        align-items: center;
        padding: 1rem;
        color: #fff;
        text-decoration: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        transition: background-color 0.2s ease-in-out;
    }
    .config-option:first-child {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    .config-option:hover {
        background-color: rgba(255, 255, 255, 0.05);
        color: #fff;
    }
    .config-option .option-text { flex-grow: 1; }
    .config-option .option-icon { width: 30px; }
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding-top: 2rem; padding-bottom: 2rem;">
    <div class="card p-4 shadow login-card" style="max-width: 700px; width: 100%;">
        <div class="card-body">
            
            <div class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/2040/2040504.png" alt="Icono de Configuración" class="login-logo mb-3">
                <h1 class="h3 text-white">Configuración de la Cuenta</h1>
                <p class="text-white-50">Ajusta tus preferencias y seguridad.</p>
            </div>

            <form id="form_configuracion_usuario">
                <!-- Sección de Seguridad -->
                <h5 class="text-white mt-4 mb-3 border-bottom pb-2">
                    <i class="fas fa-shield-alt me-2"></i>Seguridad
                </h5>
                <div class="config-options-container">
                    <a href="index.php?ruta=cambiar_contrasena_cliente" class="config-option">
                        <i class="fas fa-key option-icon me-3"></i>
                        <span class="option-text">Cambiar Contraseña</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="index.php?ruta=cambiar_correo_cliente" class="config-option">
                        <i class="fas fa-envelope option-icon me-3"></i>
                        <span class="option-text">Cambiar Correo</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="#" class="config-option text-danger">
                        <i class="fas fa-trash-alt option-icon me-3"></i>
                        <span class="option-text">Eliminar Cuenta</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </form>

            <!-- Volver al Dashboard -->
            <p class="mt-4 text-center small">
                <a href="index.php?ruta=dashboard-admin">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
                </a>
            </p>

        </div>
    </div>
</div>