<title>Mi Perfil</title>

<!-- Enlazamos la hoja de estilos principal para mantener la coherencia -->
<link rel="stylesheet" href="vista/css/main.css?v=1.1">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding-top: 2rem; padding-bottom: 2rem;">
    <div class="card p-4 shadow login-card" style="max-width: 700px; width: 100%;">
        <div class="card-body">

            <div class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png" alt="Icono de Perfil" class="login-logo mb-3" id="profile_icon">
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
                            <input type="text" class="form-control" id="profile_documento" name="documento" placeholder="Tu documento" required>
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

                        <div class="mb-3">
                            <label for="profile_sexo" class="form-label">Género</label>
                            <select class="form-select" id="profile_sexo" name="sexo" required>
                                <option value="">Seleccionar</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
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
                <a href="index.php?ruta=dashboard-usuario">
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

                // Cambiar el ícono según el sexo
                if (userData.sexo === 'M') {
                    profileIcon.src = 'https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg'; // Icono masculino
                } else if (userData.sexo === 'F') {
                    profileIcon.src = 'https://img.freepik.com/vector-premium/perfil-avatar-mujer-icono-redondo_24640-14042.jpg'; // Icono femenino
                }

                // Rellenar los campos del formulario
                document.getElementById('profile_nombre').value = userData.nombre || '';
                document.getElementById('profile_apellido').value = userData.apellido || '';
                document.getElementById('profile_documento').value = userData.documento || '';
                document.getElementById('profile_correo').value = userData.correo || '';
                document.getElementById('profile_telefono').value = userData.telefono || '';
                document.getElementById('profile_sexo').value = userData.sexo || '';
            } catch (e) {
                console.error('Error al cargar datos del perfil:', e);
            }
        }
    });
</script>


<script src='./vista/js/Usuario/Dashboard_Usuario.js'></script> <!-- Lógica del dashboard de usuario -->