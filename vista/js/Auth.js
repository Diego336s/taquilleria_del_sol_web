// =========================================================================
// 🌐 CONFIGURACIÓN GLOBAL Y LISTENERS DE EVENTOS
// =========================================================================

document.addEventListener('DOMContentLoaded', () => {
    // Verificar Autenticación y Redirigir (Protección de Rutas)
    checkAuthAndRedirect();

    // Poblar datos del usuario en la UI (ej. saludo en dashboard)
    populateUserData();

    // Event Listener para REGISTRO (formulario-registro)
    const regForm = document.getElementById('formulario-registro');
    if (regForm) {
        regForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrRegistroUsuario();
        });
    }

    // Event Listener para LOGIN (Usuario)
    const loginForm = document.getElementById('form_login_usuario');
    if (loginForm) {
        loginForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrLoginUsuario();
        });
    }

    // Event Listener para LOGIN (Empresa)
    const loginFormEmpresa = document.getElementById('form_login_empresa');
    if (loginFormEmpresa) {
        loginFormEmpresa.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrLoginEmpresa();
        });
    }

    // Event Listener para LOGIN (Admin)
    const loginFormAdmin = document.getElementById('form_login_admin');
    if (loginFormAdmin) {
        loginFormAdmin.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrLoginAdmin();
        });
    }

    //Event Listener para enviar codigo de restablecimiento de contraseña
    const codigoVerificacion = document.getElementById('form_codigo_verificacion');
    if (codigoVerificacion) {
        codigoVerificacion.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrCodigoVerificacion();
        });
    }

    //Event Listener para restablecimiento de contraseña
    const restablecerClave = document.getElementById('form_restablecer_clave');
    if (restablecerClave) {
        restablecerClave.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrRestablecerClave();
        });
    }




    //Event Listener para LOGOUT MANUAL
    const logoutBtn = document.getElementById('logout-button');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', confirmLogout);
    }






    // Configurar la funcionalidad de "Ver/Ocultar Contraseña" para cada formulario de login
    setupPasswordToggle('toggleBtn_Usuario', 'id_Password_Usuario', 'toggleIcon_Usuario');
    setupPasswordToggle('toggleBtn_Empresa', 'id_Password_Empresa', 'toggleIcon_Empresa');
    setupPasswordToggle('toggleBtn_Admin', 'id_Password_Admin', 'toggleIcon_Admin');

    // Configurar la funcionalidad de "Ver/Ocultar Contraseña" para el formulario de registro
    setupPasswordToggle('toggleBtn_Clave', 'id_Clave', 'toggleIcon_Clave');
    setupPasswordToggle('toggleBtn_ClaveConfirm', 'id_ClaveConfirm', 'toggleIcon_ClaveConfirm');

    // Configurar la funcionalidad de "Ver/Ocultar Contraseña" para el formulario de restablecer contraseña
    setupPasswordToggle('toggleBtn_nueva_clave', 'id_nueva_clave', 'toggleIcon_nueva_clave');
    setupPasswordToggle('toggleBtn_confirmar_clave', 'id_confirmar_nueva_clave', 'toggleIcon_confirmar_clave');


});


// =========================================================================
// 🔐 FUNCIÓN: PROTECCIÓN DE RUTAS
// =========================================================================

function checkAuthAndRedirect() {
    const token = sessionStorage.getItem('userToken');

    // Obtener parámetro "ruta" de la URL
    const params = new URLSearchParams(window.location.search);
    const ruta = params.get('ruta') || '';

    const protectedRoutes = ["dashboard-usuario", "dashboard-empresa", "dashboard-admin"];
    const publicRoutes = ["login", "registro", "forgot_contraseña", "restablecer_contraseña"];

    const isProtectedRoute = protectedRoutes.includes(ruta);
    const isPublicRoute = publicRoutes.includes(ruta) || ruta === "";

    // Si está en ruta protegida y no hay token -> forzar login
    if (isProtectedRoute && !token) {
        window.location.replace("index.php?ruta=login");
        return;
    }

    // Si está en ruta pública (login, registro) y sí hay token -> ir al dashboard
    if (isPublicRoute && token) {
        window.location.replace("index.php?ruta=dashboard-usuario");
        return;
    }
}

//==========================================================================
// FUNCION: CODIGO DE VERIFICACIÓN (RESTABLECER CONTRASEÑA)
//==========================================================================

async function ctrCodigoVerificacion() {
    const correo = document.getElementById('id_correo_verificacion')?.value.trim();

    if (!correo) {
        mostrarAlerta('error', 'Campo requerido', 'Por favor ingresa tu correo.');
        return;
    }

    Swal.fire({
        title: 'Enviando código...',
        text: 'Por favor, espera mientras enviamos el correo.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const urlAPI = ApiConexion + "codigo/verificacion";

    try {
        const respuesta = await fetch(urlAPI, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ correo })
        });

        const data = await respuesta.json();
        Swal.close();

        // Verificar si el estado HTTP fue exitoso (200)
        if (respuesta.ok && (data.success || data.mensaje || data.message)) {
            Swal.fire({
                icon: "success",
                title: "Código enviado",
                text: data.mensaje || data.message,
                showConfirmButton: true,
            });
        } else {
            // Manejar errores del backend
            mostrarAlerta(
                'error',
                'Error',
                data.message || data.mensaje || data.errors?.correo?.[0] || "No se pudo enviar el código."
            );
        }

    } catch (error) {
        Swal.close();
        console.error("Error:", error);
        mostrarAlerta('error', 'Error de conexión', 'No se pudo conectar con el servidor.');
    }

}


//Restablecer contraseña
async function ctrRestablecerClave() {
    const datos = {
        codigo_recuperacion: document.getElementById('id_codigo')?.value || '',
        clave: document.getElementById('id_nueva_clave')?.value || '',
        confirmar_clave: document.getElementById('id_confirmar_nueva_clave')?.value || '',
    };

    if (datos.clave !== datos.confirmar_clave) {
        mostrarAlerta("error", "Error", "Las contraseñas no coinciden.");
        return;
    };


    if (datos.clave.length < 6) {
        mostrarAlerta("errror", "Error", "Las contraseñas deben tener al menos 6 caracteres.");
        return;
    };

    Swal.fire({
        title: 'Validando Datos...',
        text: 'Por favor, espere un momento.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const urlAPI = ApiConexion + "restablecer/clave";

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        const data = await respuesta.json();
        Swal.close();

        if (data.success === true) {
            Swal.fire({
                icon: "success",
                title: 'Restablecimiento Exitoso!',
                text: data.message || 'Ahora puedes Iniciar Sesión.',
                showConfirmButton: false,
                timer: 1000
            });

            setTimeout(() => {
                window.location.replace("index.php?ruta=login");
            }, 1000);

        } else if (data.success === false) {
            let mensajeDetallado = data.message;
            mostrarAlerta('error', 'Error al restablecer contraseña:', mensajeDetallado);
        } else {
            mostrarAlerta('error', 'Error al registrar', `Ocurrió un error inesperado del servidor. (Código: ${respuesta.status})`);
        }

    } catch (error) {
        Swal.close();
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor API.');
    }


}



// =========================================================================
// 📝 FUNCIÓN: REGISTRO DE USUARIO (CLIENTE)
// =========================================================================

async function ctrRegistroUsuario() {
    const datos = {
        nombre: document.getElementById('id_Nombre')?.value || '',
        apellido: document.getElementById('id_Apellido')?.value || '',
        sexo: document.getElementById('id_Sexo')?.value || '',
        documento: document.getElementById('id_Documento')?.value || '',
        fecha_nacimiento: document.getElementById('id_fecha_Nacimiento')?.value || '',
        telefono: document.getElementById('id_Telefono')?.value || '',
        correo: document.getElementById('id_Email')?.value || '',
        clave: document.getElementById('id_Clave')?.value || '',
        confirmar_clave: document.getElementById('id_ClaveConfirm')?.value || ''
    };

    // Validaciones básicas
    if (!datos.clave || !datos.confirmar_clave || datos.clave.length < 6) {
        mostrarAlerta('error', 'Error', 'Las contraseñas deben tener al menos 6 caracteres.');
        return;
    }
    if (datos.clave !== datos.confirmar_clave) {
        mostrarAlerta('error', 'Error', 'Las contraseñas no coinciden.');
        return;
    }

    Swal.fire({
        title: 'Procesando registro...',
        text: 'Por favor, espere un momento.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const urlAPI = ApiConexion + "registrar/cliente";
    try {
        const respuesta = await fetch(urlAPI, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        const data = await respuesta.json();
        Swal.close();

        if (data.success === true) {
            Swal.fire({
                icon: "success",
                title: '¡Registro Exitoso!',
                text: data.message || 'Ahora puedes iniciar sesión.',
                showConfirmButton: false,
                timer: 1000
            });

            setTimeout(() => {
                window.location.replace("index.php?ruta=login");
            }, 1000);

        } else if (data.success === false) {
            let mensajeDetallado = data.message;
            mostrarAlerta('error', 'Error al registrar', mensajeDetallado);
        } else {
            mostrarAlerta('error', 'Error al registrar', `Ocurrió un error inesperado del servidor. (Código: ${respuesta.status})`);
        }

    } catch (error) {
        Swal.close();
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor API.');
    }
}


// =========================================================================
// 🔑 FUNCIÓN: INICIO DE SESIÓN (LOGIN USUARIO)
// =========================================================================

async function ctrLoginUsuario() {
    const datos = {
        correo: document.getElementById('id_correo')?.value || '',
        clave: document.getElementById('id_Password_Usuario')?.value || ''
    };

    Swal.fire({
        title: 'Iniciando Sesión...',
        text: 'Verificando credenciales.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const urlAPI = ApiConexion + "login/cliente";
    try {
        const respuesta = await fetch(urlAPI, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        const data = await respuesta.json();

        if (data.success === true) {
            if (data.token) {
                sessionStorage.setItem('userToken', data.token);
                if (data.user) {
                    sessionStorage.setItem('userData', JSON.stringify(data.user));
                }
            } else {
                Swal.close();
                mostrarAlerta('error', 'Error de Seguridad', 'Login exitoso, pero el token no fue recibido.');
                return;
            }

            Swal.fire({
                icon: "success",
                title: '¡Login Exitoso!',
                text: data.message || 'Has iniciado sesión correctamente.',
                showConfirmButton: false,
                timer: 1000
            });

            setTimeout(() => {
                window.location.replace("index.php?ruta=dashboard-usuario");
            }, 1000);

        } else if (data.success === false) {
            Swal.close();
            let mensajeDetallado = data.message;

            mostrarAlerta('error', 'Error al iniciar sesión', mensajeDetallado);

        } else {
            Swal.close();
            mostrarAlerta('error', 'Error al iniciar sesión', `Ocurrió un error inesperado del servidor. (Código: ${respuesta.status})`);
        }
    } catch (error) {
        Swal.close();
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor API.');
    }
}


// =========================================================================
// 🔑 FUNCIÓN: INICIO DE SESIÓN (LOGIN EMPRESA)
// =========================================================================

async function ctrLoginEmpresa() {
    const datos = {
        nit: document.getElementById('id_Nit_Empresa')?.value || '',
        clave: document.getElementById('id_Password_Empresa')?.value || ''
    };

    Swal.fire({
        title: 'Iniciando Sesión...',
        text: 'Verificando credenciales.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const urlAPI = ApiConexion + "login/empresa";
    try {
        const respuesta = await fetch(urlAPI, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        const data = await respuesta.json();

        if (data.success === true) {
            if (data.token) {
                sessionStorage.setItem('userToken', data.token);
                if (data.user) {
                    sessionStorage.setItem('userData', JSON.stringify(data.user));
                }
            } else {
                Swal.close();
                mostrarAlerta('error', 'Error de Seguridad', 'Login exitoso, pero el token no fue recibido.');
                return;
            }

            Swal.fire({
                icon: "success",
                title: '¡Login Exitoso!',
                text: data.message || 'Has iniciado sesión correctamente.',
                showConfirmButton: false,
                timer: 1000
            });

            setTimeout(() => {
                window.location.replace("index.php?ruta=dashboard-empresa");
            }, 1000);

        } else if (data.success === false) {
            Swal.close();
            let mensajeDetallado = data.message;

            mostrarAlerta('error', 'Error al iniciar sesión', mensajeDetallado);

        } else {
            Swal.close();
            mostrarAlerta('error', 'Error al iniciar sesión', `Ocurrió un error inesperado del servidor. (Código: ${respuesta.status})`);
        }
    } catch (error) {
        Swal.close();
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor API.');
    }
}


// =========================================================================
// 🔑 FUNCIÓN: INICIO DE SESIÓN (LOGIN ADMIN)
// =========================================================================

async function ctrLoginAdmin() {

    const datos = {
        documento: document.getElementById('id_documento')?.value || '',
        clave: document.getElementById('id_Password_Admin')?.value || ''
    };

    Swal.fire({
        title: 'Iniciando Sesión...',
        text: 'Verificando credenciales.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const urlAPI = ApiConexion + "login/admin";
    try {
        const respuesta = await fetch(urlAPI, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        const data = await respuesta.json();

        if (data.success === true) {
            if (data.token) {
                sessionStorage.setItem('userToken', data.token);
                if (data.user) {
                    sessionStorage.setItem('userData', JSON.stringify(data.user));
                }
            } else {
                Swal.close();
                mostrarAlerta('error', 'Error de Seguridad', 'Login exitoso, pero el token no fue recibido.');
                return;
            }

            Swal.fire({
                icon: "success",
                title: '¡Login Exitoso!',
                text: data.message || 'Has iniciado sesión correctamente.',
                showConfirmButton: false,
                timer: 1000
            });

            setTimeout(() => {
                window.location.replace("index.php?ruta=dashboard-admin");
            }, 1000);

        } else if (data.success === false) {
            Swal.close();
            let mensajeDetallado = data.message;

            mostrarAlerta('error', 'Error al iniciar sesión', mensajeDetallado);

        } else {
            Swal.close();
            mostrarAlerta('error', 'Error al iniciar sesión', `Ocurrió un error inesperado del servidor. (Código: ${respuesta.status})`);
        }
    } catch (error) {
        Swal.close();
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor API.');
    }
}


// =========================================================================
// 🚪 FUNCIÓN: CERRAR SESIÓN USUARIOS (LOGOUT) CON MODAL DE CONFIRMACIÓN
// =========================================================================

function confirmLogout() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas cerrar tu sesión actual?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            ctrLogoutUsuario();
        }
    });
}

async function ctrLogoutUsuario() {
    const token = sessionStorage.getItem('userToken');

    if (!token) {
        // Si no hay token, redirige directamente al login
        window.location.replace("index.php?ruta=login");
        return;
    }

    const urlAPI = ApiConexion + "logout/cliente";

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (respuesta.ok) {
            // Logout exitoso
            sessionStorage.removeItem('userToken');
            sessionStorage.removeItem('userData');
            mostrarAlerta('success', 'Sesión cerrada', 'Has cerrado sesión correctamente.');

            // Redirige después de un pequeño delay
            setTimeout(() => {
                window.location.replace("index.php?ruta=login");
            }, 1000);
        } else {
            // Si el backend devuelve error
            mostrarAlerta('error', 'Error al cerrar sesión', data.message || 'Ocurrió un problema al cerrar sesión.');
        }

    } catch (error) {
        console.error("Error al cerrar sesión:", error);
        mostrarAlerta('error', 'Error', 'No se pudo conectar con el servidor API.');
    }
}


// =========================================================================
// 🚪 FUNCIÓN: CERRAR SESIÓN EMPRESAS (LOGOUT) CON MODAL DE CONFIRMACIÓN
// =========================================================================

function confirmLogoutEmpresas() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas cerrar tu sesión actual?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            ctrLogoutEmpresas();
        }
    });
}

async function ctrLogoutEmpresas() {
    const token = sessionStorage.getItem('userToken');

    Swal.fire({
        title: 'Cerrando Sesión...',
        text: 'Espere un momento..',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    if (!token) {
        // Si no hay token, redirige directamente al login
        window.location.replace("index.php?ruta=login");
        return;
    }

    const urlAPI = ApiConexion + "logout/empresa";

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (respuesta.ok) {
            // Logout exitoso
            sessionStorage.removeItem('userToken');
            sessionStorage.removeItem('userData');
            Swal.close();
            mostrarAlerta('success', 'Sesión cerrada', 'Has cerrado sesión correctamente.');


            // Redirige después de un pequeño delay
            setTimeout(() => {
                window.location.replace("index.php?ruta=login");
            }, 1000);
        } else {
            // Si el backend devuelve error
            mostrarAlerta('error', 'Error al cerrar sesión', data.message || 'Ocurrió un problema al cerrar sesión.');
        }

    } catch (error) {
        console.error("Error al cerrar sesión:", error);
        mostrarAlerta('error', 'Error', 'No se pudo conectar con el servidor API.');
    }
}


// =========================================================================
// 🚪 FUNCIÓN: CERRAR SESIÓN ADMIN (LOGOUT) CON MODAL DE CONFIRMACIÓN
// =========================================================================

function confirmLogoutAdmin() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas cerrar tu sesión actual?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            ctrLogoutAdmin();
        }
    });
}

async function ctrLogoutAdmin() {
    const token = sessionStorage.getItem('userToken');

    Swal.fire({
        title: 'Cerrando Sesión...',
        text: 'Espere un momento..',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    if (!token) {
        // Si no hay token, redirige directamente al login
        window.location.replace("index.php?ruta=login");
        return;
    }

    const urlAPI = ApiConexion + "logout/admin";

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (respuesta.ok) {
            // Logout exitoso
            sessionStorage.removeItem('userToken');
            sessionStorage.removeItem('userData');
            Swal.close();
            mostrarAlerta('success', 'Sesión cerrada', 'Has cerrado sesión correctamente.');


            // Redirige después de un pequeño delay
            setTimeout(() => {
                window.location.replace("index.php?ruta=login");
            }, 1000);
        } else {
            // Si el backend devuelve error
            mostrarAlerta('error', 'Error al cerrar sesión', data.message || 'Ocurrió un problema al cerrar sesión.');
        }

    } catch (error) {
        console.error("Error al cerrar sesión:", error);
        mostrarAlerta('error', 'Error', 'No se pudo conectar con el servidor API.');
    }
}




// =========================================================================
// ✨ FUNCIÓN: POBLAR DATOS DE USUARIO EN LA UI
// =========================================================================

function populateUserData() {
    const userDataString = sessionStorage.getItem('userData');
    if (userDataString) {
        try {
            const userData = JSON.parse(userDataString);
            const nombreUsuarioElement = document.getElementById('nombre-usuario');

            if (nombreUsuarioElement && userData.nombre) {
                // Capitalizamos la primera letra del nombre para un mejor formato
                const nombreCapitalizado = userData.nombre.charAt(0).toUpperCase() + userData.nombre.slice(1).toLowerCase();
                nombreUsuarioElement.textContent = nombreCapitalizado;
            }
        } catch (e) {
            console.error('Error al parsear los datos del usuario desde sessionStorage:', e);
        }
    }
}


// =========================================================================
// ℹ️ FUNCIONES AUXILIARES
// =========================================================================

function mostrarAlerta(icon, title, text) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: icon,
            title: title,
            html: text,
            showConfirmButton: true,
            confirmButtonText: "Aceptar"
        });
    } else {
        alert(`${title} (${icon}): ${text.replace(/<br>/g, '\n')}`);
    }
}

function formatValidationErrors(errors) {
    let html = '';
    for (const field in errors) {
        errors[field].forEach(error => {
            html += `• ${error}<br>`;
        });
    }
    return html;
}

/**
 * Configura la funcionalidad de mostrar/ocultar para un campo de contraseña específico.
 * @param {string} buttonId El ID del botón que activa la función.
 * @param {string} inputId El ID del campo de contraseña.
 * @param {string} iconId El ID del ícono dentro del botón.
 */
function setupPasswordToggle(buttonId, inputId, iconId) {
    const toggleButton = document.getElementById(buttonId);
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (toggleButton && passwordInput && icon) {
        toggleButton.addEventListener('click', function () {
            // Cambiar el tipo de input
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Cambiar el ícono
            if (type === 'password') {
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });
    }
}
