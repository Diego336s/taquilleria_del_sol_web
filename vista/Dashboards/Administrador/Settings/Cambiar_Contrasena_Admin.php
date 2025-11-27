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

            <form id="form_cambiar_clave_config_admin">

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
                <a href="index.php?ruta=Configuracion_admin">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Configuración
                </a>
            </p>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Verificar Autenticación y Redirigir (Protección de Rutas)
        checkAuthAndRedirect();

        // Event Listener para (formulario cambiar clave cliente/Config)
        const cambiar_clave_config = document.getElementById('form_cambiar_clave_config_admin');
        if (cambiar_clave_config) {
            cambiar_clave_config.addEventListener('submit', async function(event) {
                console.log("prueba de envio");
                event.preventDefault();
                await ctrCambiarClaveConfigAdmin();
            });
        };


    });


    // =========================================================================
    // FUNCION: CAMBIAR CLAVE ADMIN/CONFIG
    // =========================================================================

    async function ctrCambiarClaveConfigAdmin() {

        console.log("➡️ ctrCambiarClaveConfigAdmin ejecutado");
        // 1. Recolectar datos del formulario
        const clave = document.getElementById('id_nueva_clave_config')?.value;
        const confirmar_clave_nueva = document.getElementById('id_confirm_nueva_clave_config')?.value;

        // 2. Validaciones
        if (!clave || !confirmar_clave_nueva) {
            mostrarAlerta('error', 'Campos incompletos', 'Por favor, rellena todos los campos.');
            return;
        }

        if (clave !== confirmar_clave_nueva) {
            mostrarAlerta('error', 'Error de validación', 'La nueva contraseña y su confirmación no coinciden.');
            return;
        }

        if (clave.length < 6) {
            mostrarAlerta('error', 'Contraseña débil', 'La nueva contraseña debe tener al menos 6 caracteres.');
            return;
        }

        // 3. Obtener token y ID de usuario
        const token = sessionStorage.getItem('userToken');
        const userDataString = sessionStorage.getItem('userData');

        if (!token || !userDataString) {
            mostrarAlerta('error', 'Sesión inválida', 'No se encontraron datos de sesión. Por favor, inicia sesión de nuevo.');
            return;
        }

        const userData = JSON.parse(userDataString);
        const userId = userData.id;

        // 4. Mostrar alerta de carga
        Swal.fire({
            title: 'Actualizando contraseña...',
            text: 'Por favor, espera un momento.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // 5. Preparar datos y URL para la API
        const datos = {
            clave: clave
        };
        const urlAPI = `${ApiConexion}cambiar/clave/admin/${userId}`;

        try {
            console.log("➡️ Enviando petición a:", urlAPI);
            const respuesta = await fetch(urlAPI, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify(datos)
            });

            const data = await respuesta.json();
            console.log("✅ Respuesta recibida:", respuesta);
            Swal.close();

            if (data.success === true) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Contraseña Actualizada!',
                    text: data.message || 'Tu contraseña se ha cambiado correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Redirigir a la página de configuración después del éxito
                setTimeout(() => {
                    window.location.href = "index.php?ruta=Configuracion_admin";
                }, 2000);
            } else {
                mostrarAlerta('error', 'Error al actualizar', data.message || 'No se pudo cambiar la contraseña. Verifica tu contraseña actual.');
            }
        } catch (error) {
            Swal.close();
            console.error("Error al cambiar la contraseña:", error);
            mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
        }
    }
</script>