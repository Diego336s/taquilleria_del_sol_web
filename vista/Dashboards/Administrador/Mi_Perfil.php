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

            <form id="form_actualizar_perfil">
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
        const userDataString = sessionStorage.getItem('userData');
        if (userDataString) {
            try {
                const profileIcon = document.getElementById('profile_icon');
                const userData = JSON.parse(userDataString);



                // Rellenar los campos del formulario
                document.getElementById('profile_nombre').value = userData.nombres || '';
                document.getElementById('profile_apellido').value = userData.apellidos || '';
                document.getElementById('profile_documento').value = userData.documento || '';
                document.getElementById('profile_correo').value = userData.correo || '';
                document.getElementById('profile_telefono').value = userData.telefono || '';
            } catch (e) {
                console.error('Error al cargar datos del perfil:', e);
            }
        }
    });
</script>
<script src='./vista/js/Admin/Mi_Perfil.js'></script>