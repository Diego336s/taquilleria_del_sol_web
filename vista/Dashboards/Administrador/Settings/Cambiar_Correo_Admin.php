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

            <form id="form_cambiar_correo_admin">

                <div class="mb-3">
                    <label for="new_email" class="form-label">Nuevo Correo Electrónico</label>
                    <input type="email" class="form-control" id="id_correo_config_admin" name="new_email" required>
                </div>

                <div class="mb-3">
                    <label for="confirm_new_email" class="form-label">Confirmar Nuevo Correo</label>
                    <input type="email" class="form-control" id="id_confirm_correo_config_admin" name="confirm_new_email" required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100 py-2">
                        Actualizar Correo
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

        // Event Listener para (formulario cambiar correo admin/Config)
        const cambiar_correo_config = document.getElementById('form_cambiar_correo_admin');
        if (cambiar_correo_config) {
            cambiar_correo_config.addEventListener('submit', async function(event) {
                console.log("prueba de envio");
                event.preventDefault();
                await ctrCambiarCorreoConfigAdmin();
            });
        }


    });
    // =========================================================================
    // FUNCION: CAMBIAR CORREO ADMIN/CONFIG
    // =========================================================================

    async function ctrCambiarCorreoConfigAdmin() {

        // 1. Recolectar datos del formulario
        const correo = document.getElementById('id_correo_config_admin')?.value;
        const confirmar_correo_nuevo = document.getElementById('id_confirm_correo_config_admin')?.value;

        // 2. Validaciones
        if (!correo || !confirmar_correo_nuevo) {
            mostrarAlerta('error', 'Campos incompletos', 'Por favor, rellena todos los campos.');
            return;
        }

        if (correo !== confirmar_correo_nuevo) {
            mostrarAlerta('error', 'Error de validación', 'El nuevo correo y su confirmación no coinciden.');
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
            title: 'Actualizando Correo...',
            text: 'Por favor, espera un momento.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // 5. Preparar datos y URL para la API
        const datos = {
            correo: correo
        };
        const urlAPI = `${ApiConexion}cambiar/correo/admin/${userId}`;

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
                // Eliminar token y datos de sesión
                sessionStorage.removeItem('userToken');
                sessionStorage.removeItem('userData');

                Swal.fire({
                    icon: 'success',
                    title: 'Correo Actualizado!',
                    text: data.message || 'Tu Correo se ha cambiado correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Redirigir a la página de configuración después del éxito
                setTimeout(() => {
                    window.location.href = "index.php?ruta=login";
                }, 2000);
            } else {
                mostrarAlerta('error', 'Error al actualizar', data.message || 'No se pudo cambiar el correo. Verifica tu correo actual.');
            }
        } catch (error) {
            Swal.close();
            console.error("Error al cambiar el correo:", error);
            mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
        }
    }
</script>