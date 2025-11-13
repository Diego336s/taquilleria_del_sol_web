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


    /* REDES SOCIALES */
    .social-section {
        text-align: center;
        margin-top: 40px;
    }

    .social-section a {
        color: #fff;
        margin: 0 15px;
        font-size: 1.8rem;
        transition: 0.3s;
    }

    .social-section a:hover {
        color: #ff6b1f;
        transform: scale(1.2);
    }

    /* Colores específicos para cada red social en hover */
    .social-section a[title="WhatsApp"]:hover {
        color: #25D366; 
    }

    .social-section a[title="Facebook"]:hover {
        color: #1877F2; 
    }

    .social-section a[title="Instagram"]:hover {
        background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent; 
        color: #e1306c;
    }

    .social-section a[title="Maps"]:hover {
        color: #EA4335; /* Rojo de Google Maps */
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
                    <a href="#" id="btn-eliminar-cuenta" class="config-option text-danger">
                        <i class="fas fa-trash-alt option-icon me-3"></i>
                        <span class="option-text">Eliminar Cuenta</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </form>

            <div class="social-section">
                <a href="https://wa.me/573213988446" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                <a href="https://facebook.com" target="_blank" title="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="https://instagram.com" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://maps.app.goo.gl/V4UH4scFH5QWNFWg9" target="_blank" title="Maps"><i class="fas fa-map-marker-alt"></i></a>

            </div>
        </div>

        <!-- Volver al Dashboard -->
        <p class="mt-4 text-center small">
            <a href="index.php?ruta=dashboard-usuario">
                <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
            </a>
        </p>

    </div>
</div>
</div>

<script src='./vista/js/Usuario/Dashboard_Usuario.js'></script> <!-- Lógica del dashboard de usuario -->

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnEliminar = document.getElementById('btn-eliminar-cuenta');
    if (btnEliminar) {
        btnEliminar.addEventListener('click', (e) => {
            e.preventDefault(); // Prevenir la acción por defecto del enlace
            
            Swal.fire({
                title: '¿Estás absolutamente seguro?',
                text: "Esta acción es irreversible. Todos tus datos, historial y reservas serán eliminados permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar mi cuenta',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    ctrEliminarCuenta(); // Llamar a la función que maneja la eliminación
                }
            });
        });
    }
});
</script>
</div>