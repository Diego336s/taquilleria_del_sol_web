  <title>Mi Perfil</title>


<!-- Enlazamos la hoja de estilos principal para mantener la coherencia -->
<link rel="stylesheet" href="vista/css/main.css?v=1.1">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding-top: 2rem; padding-bottom: 2rem;">
    <div class="card p-4 shadow login-card" style="max-width: 700px; width: 100%;">
        <div class="card-body">

            <div class="text-center mb-4">
                <img src="https://cdn3d.iconscout.com/3d/premium/thumb/manager-3d-icon-download-in-png-blend-fbx-gltf-file-formats--business-businessman-man-model-canvas-pack-icons-9616909.png?f=webp" alt="Icono de Perfil" class="login-logo mb-3" id="profile_icon">
                <h1 class="h3 text-white">Mi Perfil</h1>
                <p class="text-white-50">Gestiona tu información personal y tu cuenta.</p>
            </div>

            <form id="form_actualizar_perfil_admin">
                <input type="hidden" id="profile_id_documento" name="id_documento">
                <div class="row">
                    <!-- Columna Izquierda -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="profile_nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="profile_nombre" name="nombre" placeholder="Tu nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="profile_apellido" name="apellido" placeholder="Tu apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_documento" class="form-label">Documento</label>
                            <input type="text" class="form-control" id="profile_documento" name="documento" placeholder="Tu documento" required readonly>
                            <small class="text-white-50">El documento no se puede modificar.</small>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="profile_correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="profile_correo" name="correo" placeholder="tu@correo.com" required readonly>
                            <small class="text-white-50">El correo no se puede modificar.</small>
                        </div>
                        <div class="mb-3">
                            <label for="profile_telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="profile_telefono" name="telefono" placeholder="Tu teléfono" required>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100 py-2">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>

            <!-- Separador y Acciones Adicionales -->
            <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">

            <p class="mt-4 text-center small">
                <a href="index.php?ruta=dashboard-admin">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
                </a>
            </p>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Verificar que el usuario esté autenticado
        checkAuthAndRedirect();

        populateUserData();

        // Cargar datos desde sessionStorage SOLO UNA VEZ
        const userDataString = sessionStorage.getItem('userData');
        if (userDataString) {
            try {
                const userData = JSON.parse(userDataString);

                // Rellenar formulario
                document.getElementById('profile_nombre').value = userData.nombres || '';
                document.getElementById('profile_apellido').value = userData.apellidos || '';
                document.getElementById('profile_documento').value = userData.documento || '';
                document.getElementById('profile_correo').value = userData.correo || '';
                document.getElementById('profile_id_documento').value = userData.documento || '';
                document.getElementById('profile_telefono').value = userData.telefono || '';
            } catch (e) {
                console.error('Error al parsear userData:', e);
            }
        }

        // Evento de actualización
        const update_Perfil = document.getElementById('form_actualizar_perfil_admin');
        if (update_Perfil) {
            update_Perfil.addEventListener('submit', async function(event) {
                event.preventDefault();
                await ctrupdatePerfilAdmin();
            });
        }
    });






    async function ctrupdatePerfilAdmin() {

        // 1. Obtener el token y los datos del usuario de la sesión
        const token = sessionStorage.getItem('userToken');
        const userDataString = sessionStorage.getItem('userData');

        if (!token || !userDataString) {
            mostrarAlerta('error', 'Sesión inválida', 'No se encontraron datos de sesión. Por favor, inicia sesión de nuevo.');
            return;
        }

        // 2. Recolectar los datos del formulario
        const datos = {
            nombres: document.getElementById('profile_nombre')?.value.trim(),
            apellidos: document.getElementById('profile_apellido')?.value.trim(),
            telefono: document.getElementById('profile_telefono')?.value.trim(),

        };

        // Validación simple de campos
        if (!datos.nombres || !datos.apellidos || !datos.telefono) {
            mostrarAlerta('error', 'Campos incompletos', 'Por favor, rellena todos los campos requeridos.');
            return;
        }

        // 3. Mostrar alerta de carga
        Swal.fire({
            title: 'Actualizando perfil...',
            text: 'Por favor, espera un momento.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // 4. Realizar la petición a la API
        const userData = JSON.parse(userDataString);
        const urlAPI = `${ApiConexion}actualizarAdministradores/${userData.id}`;


        try {
            const respuesta = await fetch(urlAPI, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify(datos)
            });

            const data = await respuesta.json();
            Swal.close();

            if (data.success === true) {
                // 5. Actualizar sessionStorage con los nuevos datos
                const updatedUserData = data.administrador;
                if (updatedUserData) {
                    sessionStorage.setItem('userData', JSON.stringify(updatedUserData));
                    // Refrescar el nombre en el saludo del dashboard si existe
                    populateUserData();
                }

                Swal.fire({
                    icon: 'success',
                    title: '¡Perfil Actualizado!',
                    text: data.message || 'Tus datos se han guardado correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                });




                //recargar la pagina despues de haber actualizado exitosamente
                setTimeout(() => {
                    window.location.reload();
                }, 2000);

            } else {
                mostrarAlerta('error', 'Error al actualizar', data.message || 'No se pudieron guardar los cambios.');
            }
        } catch (error) {
            Swal.close();
            console.error("Error al actualizar perfil:", error);
            mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
        }
    }
</script>