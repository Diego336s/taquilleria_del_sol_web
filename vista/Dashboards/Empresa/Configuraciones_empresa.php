<title>Configuraciones</title>
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

    .config-option .option-text {
        flex-grow: 1;
    }

    .config-option .option-icon {
        width: 30px;
    }
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding-top: 2rem; padding-bottom: 2rem;">
    <div class="card p-4 shadow login-card" style="max-width: 700px; width: 100%;">
        <div class="card-body">

            <div class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/2040/2040504.png" alt="Icono de Configuración" class="login-logo mb-3">
                <h1 class="h3 text-white">Configuración de la Cuenta</h1>
                <p class="text-white-50">Ajusta tus preferencias y seguridad.</p>
            </div>

            <!-- Sección de Seguridad -->
            <h5 class="text-white mt-4 mb-3 border-bottom pb-2">
                <i class="fas fa-shield-alt me-2"></i>Seguridad
            </h5>

            <!-- Cambiar Contraseña -->
            <div class="mb-4">
                <button class="btn btn-outline-light w-100 mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePassword" aria-expanded="false" aria-controls="collapsePassword">
                    <i class="fas fa-key me-2"></i>Cambiar Contraseña
                </button>
                <div class="collapse" id="collapsePassword">
                    <form id="form_cambiar_clave_config_empresa">
                        <div class="mb-3">
                            <label for="id_nueva_clave_config" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="id_nueva_clave_config" name="nueva_clave" placeholder="Nueva contraseña" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_confirm_nueva_clave_config" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="id_confirm_nueva_clave_config" name="confirmar_clave" placeholder="Confirmar nueva contraseña" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Cambiar Contraseña</button>
                    </form>
                </div>
            </div>

            <!-- Cambiar Correo -->
            <div class="mb-4">
                <button class="btn btn-outline-light w-100 mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEmail" aria-expanded="false" aria-controls="collapseEmail">
                    <i class="fas fa-envelope me-2"></i>Cambiar Correo
                </button>
                <div class="collapse" id="collapseEmail">
                    <form id="form_cambiar_correo_config_empresa">
                        <div class="mb-3">
                            <label for="id_correo_config_empresa" class="form-label">Nuevo Correo</label>
                            <input type="email" class="form-control" id="id_correo_config_empresa" name="nuevo_correo" placeholder="Nuevo correo electrónico" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_confirm_correo_config_empresa" class="form-label">Confirmar Nuevo Correo</label>
                            <input type="email" class="form-control" id="id_confirm_correo_config_empresa" name="confirmar_correo" placeholder="Confirmar nuevo correo electrónico" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Cambiar Correo</button>
                    </form>
                </div>
            </div>
            <div class="mb-4">
                <a href="index.php?ruta=TerminosYCondiciones" class="btn btn-outline-light w-100 mb-3"   aria-controls="collapseEmail">
                    <i class="fas fa-book"></i>

                    Terminos y Condiciones
                </a>

            </div>



            <!-- Volver al Dashboard -->
            <p class="mt-4 text-center small">
                <a href="index.php?ruta=dashboard-empresa">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
                </a>
            </p>

        </div>
    </div>
</div>

<script src='./vista/js/Empresa/Dashboard_Empresa.js'></script>