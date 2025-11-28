<title>Perfil empresa</title>
<!-- Enlazamos la hoja de estilos principal para mantener la coherencia -->
<link rel="stylesheet" href="vista/css/main.css?v=1.1">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding-top: 2rem; padding-bottom: 2rem;">
    <div class="card p-4 shadow login-card" style="max-width: 700px; width: 100%;">
        <div class="card-body">

            <div class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png" alt="Icono de Perfil" class="login-logo mb-3" id="profile_icon">
                <h1 class="h3 text-white">Mi Perfil Empresa</h1>
                <p class="text-white-50">Gestiona tu información personal y tu cuenta.</p>
            </div>

            <form id="form_actualizar_perfil_empresa">
                <div class="row">
                    <!-- Columna Izquierda -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="profile_nombre" class="form-label">Nombre Empresa</label>
                            <input type="text" class="form-control" id="profile_nombre" name="nombre_empresa" placeholder="Nombre de la empresa" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_nit" class="form-label">NIT</label>
                            <input type="text" class="form-control" id="profile_nit" name="nit" placeholder="Número NIT" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_representante_legal" class="form-label">Representante Legal</label>
                            <input type="text" class="form-control" id="profile_representante_legal" name="representante_legal" placeholder="Representante Legal" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_documento_representante" class="form-label">Documento Representante</label>
                            <input type="text" class="form-control" id="profile_documento_representante" name="documento_representante" placeholder="Documento Representante" required>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="profile_nombre_contacto" class="form-label">Nombre Contacto</label>
                            <input type="text" class="form-control" id="profile_nombre_contacto" name="nombre_contacto" placeholder="Nombre Contacto" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="profile_telefono" name="telefono" placeholder="Teléfono de contacto" required>
                        </div>

                        <div class="mb-3">
                            <label for="profile_correo" class="form-label">Correo Representante</label>
                            <input type="email" class="form-control" id="profile_correo" name="correo" placeholder="tu@correo.com" required readonly>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100 py-2">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>

            <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">

            <p class="mt-4 text-center small">
                <a href="index.php?ruta=dashboard-empresa">
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
                const userData = JSON.parse(userDataString);
                const profileIcon = document.getElementById('profile_icon');

                // Cambiar el ícono según el sexo (opcional)
                if (userData.sexo === 'M') {
                    profileIcon.src = 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
                } else if (userData.sexo === 'F') {
                    profileIcon.src = 'https://cdn-icons-png.flaticon.com/512/3135/3135789.png';
                }

                // Rellenar los campos del formulario
                document.getElementById('profile_nombre').value = userData.nombre_empresa || '';
                document.getElementById('profile_nit').value = userData.nit || '';
                document.getElementById('profile_representante_legal').value = userData.representante_legal || '';
                document.getElementById('profile_documento_representante').value = userData.documento_representante || '';
                document.getElementById('profile_nombre_contacto').value = userData.nombre_contacto || '';
                document.getElementById('profile_telefono').value = userData.telefono || '';
                document.getElementById('profile_correo').value = userData.correo || '';

            } catch (e) {
                console.error('Error al cargar datos del perfil:', e);
            }
        }
    });
</script>


<script src='./vista/js/Empresa/Dashboard_Empresa.js'></script> <!-- Lógica del dashboard de usuario -->
