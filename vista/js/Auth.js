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
    const recibir_Correo = document.getElementById('form_correo');
    if (recibir_Correo) {
        recibir_Correo.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrRecibirCorreo();
        });
    }

    //Event Listener para verificar el codigo de restablecimiento de contraseña
    const codigoVerificacion = document.getElementById('form_verificar_codigo');
    if (codigoVerificacion) {
        codigoVerificacion.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrVerificarCodigo();
        });
    }

    const restablecerClave = document.getElementById('form_restablecer_clave');
    if (restablecerClave) {
        restablecerClave.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrRestablecerClaveGeneral();
        });
    }


    //Event Listener para LOGOUT MANUAL
    const logoutBtn = document.getElementById('logout-button') || document.getElementById('btnLogout');
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

    // Configurar la funcionalidad de "Ver/Ocultar Contraseña" para el formulario de restablecer contraseña en configuracion cliente
    setupPasswordToggle('toggleBtn_clave_actual_config', 'id_clave_actual_config', 'toggleIcon_clave_actual_config');
    setupPasswordToggle('toggleBtn_nueva_clave_config', 'id_nueva_clave_config', 'toggleIcon_nueva_clave_config');
    setupPasswordToggle('toggleBtn_confirm_nueva_clave_config', 'id_confirm_nueva_clave_config', 'toggleIcon_confirm_nueva_clave_config');


});


// =========================================================================
// 🔐 FUNCIÓN: PROTECCIÓN DE RUTAS
// =========================================================================

function checkAuthAndRedirect() {
    // Debug helper
    const log = (...args) => console.log("%c[Auth]", "color:#ff8c00;font-weight:bold", ...args);

    // Helper para resolver el rol de forma robusta
    const resolveRole = (raw) => {
        try {
            const u = raw || {};
            const candidates = [
                u.rol, u.role, u.tipo, u.tipo_usuario, u.tipoUsuario, u.perfil, u.user_type, u.userType,
                u?.rol?.nombre, u?.role?.name, u?.perfil?.nombre, u?.tipo?.nombre
            ].filter(Boolean);

            let role = candidates[0] ?? "";
            if (typeof role === "number") {
                // Mapeo numérico común (ajustar si aplica a tu backend)
                role = role === 1 ? "admin" : role === 2 ? "empresa" : "cliente";
            }
            role = String(role).toLowerCase();

            if (role.includes("admin")) return "admin";
            if (role.includes("empresa") || role.includes("Empresa") || role.includes("business")) return "empresa";
            if (role.includes("cliente") || role.includes("usuario") || role.includes("user")) return "cliente";
            return "cliente";
        } catch {
            return "cliente";
        }
    };

    const token = sessionStorage.getItem("userToken");
    const userDataString = sessionStorage.getItem("userData");
    const params = new URLSearchParams(window.location.search);
    const ruta = params.get("ruta") || "";

    // Usamos las rutas inyectadas desde PHP. Si no existen, usamos un fallback seguro.
    const routes = window.APP_ROUTES || { publicas: [], protegidas: { cliente: [], empresa: [], admin: [] } };
    const publicRoutes = routes.publicas;
    const protectedCliente = routes.protegidas.cliente;
    const protectedEmpresa = routes.protegidas.empresa;
    const protectedAdmin = routes.protegidas.admin;
    const protectedRoutes = [...protectedCliente, ...protectedEmpresa, ...protectedAdmin];
    const validRoutes = [...publicRoutes, ...protectedRoutes];

    log("Ruta actual:", ruta, "| token:", !!token, "| userDataString:", !!userDataString);

    // 🛑 CASO ESPECIAL: Si estás en la vista 404, no hacer NADA
    if (ruta === "404") {
        log("Vista 404 detectada. No se aplica protección/redirección.");
        return;
    }

    // 🔹 CASO 1: No hay parámetro ruta (ej. index.php)
    if (ruta === "") {
        if (token && userDataString) {
            try {
                const userData = JSON.parse(userDataString);
                const rol = resolveRole(userData);
                log("Redirigiendo desde raíz según rol:", rol);
                if (rol === "admin") {
                    window.location.replace("index.php?ruta=dashboard-admin");
                } else if (rol === "empresa") {
                    window.location.replace("index.php?ruta=dashboard-empresa");
                } else {
                    window.location.replace("index.php?ruta=dashboard-usuario");
                }
            } catch (e) {
                console.error("[Auth] Error al parsear userData:", e);
                window.location.replace("index.php?ruta=login");
            }
        } else {
            log("Sin sesión desde raíz. Enviando a login.");
            window.location.replace("index.php?ruta=login");
        }
        return;
    }

    // 🔹 CASO 2: Ruta no válida → 404 (antes de cualquier otro flujo)
    if (!validRoutes.includes(ruta)) {
        log("Ruta inválida:", ruta, "→ Enviando a 404");
        window.location.replace("index.php?ruta=404");
        return;
    }

    const isProtected = protectedRoutes.includes(ruta);
    const isPublic = publicRoutes.includes(ruta);

    // 🔹 CASO 3: Ruta protegida sin token → login
    if (isProtected && !token) {
        log("Ruta protegida sin token. Enviando a login.");
        window.location.replace("index.php?ruta=login");
        return;
    }

    // 🔹 CASO 4: Ruta pública con token → redirigir según rol
    if (isPublic && token && userDataString) {
        try {
            const userData = JSON.parse(userDataString);
            const rol = resolveRole(userData);
            log("Ruta pública con token. Rol:", rol, "Ruta actual:", ruta);

            if (rol === "admin" && ruta !== "dashboard-admin") {
                window.location.replace("index.php?ruta=dashboard-admin");
                return;
            }
            if (rol === "empresa" && ruta !== "dashboard-empresa") {
                window.location.replace("index.php?ruta=dashboard-empresa");
                return;
            }
            if (rol === "cliente" && ruta !== "dashboard-usuario") {
                window.location.replace("index.php?ruta=dashboard-usuario");
                return;
            }
        } catch (e) {
            console.error("[Auth] Error al leer userData:", e);
            window.location.replace("index.php?ruta=login");
        }
        return;
    }

    // 🔹 CASO 5: Ruta válida → continuar normalmente
    log("Ruta válida sin redirección automática:", ruta);
}



//==========================================================================
// FUNCION: RECIBIR CODIGO DE VERIFICACIÓN (RESTABLECER CONTRASEÑA)
//==========================================================================

async function ctrRecibirCorreo() {
    const email = document.getElementById('id_correo_verificacion')?.value.trim();

    if (!email) {
        mostrarAlerta('error', 'Campo requerido', 'Por favor ingresa tu correo.');
        return;
    }

    Swal.fire({
        title: 'Enviando código...',
        text: 'Por favor, espera mientras enviamos el correo.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const urlAPI = ApiConexion + "envio/codigo/verificacion";

    try {
        const respuesta = await fetch(urlAPI, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email })
        });

        const data = await respuesta.json();
        Swal.close();

        // Verificar si el estado HTTP fue exitoso (200)
        if (respuesta.ok && data.success === true) {

            Swal.fire({
                icon: "success",
                title: "Código enviado",
                text: data.mensaje || data.message,
                showConfirmButton: true,
            });

            //Redirigir a la pestaña de verificar codigo
            setTimeout(() => {
                window.location.replace("index.php?ruta=verificar_codigo&email=" + email);
            }, 1000)
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

//==========================================================================
//FUNCION: VERIFICACIÓN DE CODIGO (RESTABLECER CONTRASEÑA)
//==========================================================================

async function ctrVerificarCodigo() {

    //Trae correo como parametro
    const params = new URLSearchParams(window.location.search);
    const correo = params.get('email');

    const codigo = document.getElementById('id_codigo_verificacion')?.value.trim();

    if (!correo | !codigo) {
        mostrarAlerta('error', 'Campo requerido', 'Por favor ingresa tu código.');
        return;
    }

    Swal.fire({
        title: 'Verificando código...',
        text: 'Por favor, espera mientras verificamos el código.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }

    });

    const urlAPI = ApiConexion + "verificar/codigo";

    try {
        const respuesta = await fetch(urlAPI, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ correo, codigo })
        });

        const data = await respuesta.json();
        Swal.close();

        // Verificar si el estado HTTP fue exitoso (200)
        if (respuesta.ok && (data.success || data.mensaje || data.message)) {
            Swal.fire({
                icon: "success",
                title: "Código Verificado",
                text: data.mensaje || data.message,
                showConfirmButton: true,
            });

            //Redirigir a la pestaña de restablecer contraseña
            setTimeout(() => {
                window.location.replace("index.php?ruta=restablecer_contraseña&correo=" + correo);
            }, 1000)
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

//==========================================================================
//FUNCION: RESTABLECER CONTRASEÑA CLIENTE
//==========================================================================

async function ctrRestablecerClaveGeneral() {
    const params = new URLSearchParams(window.location.search);
    const correo = params.get('correo') || params.get('email');
    const clave = document.getElementById('id_nueva_clave')?.value.trim();
    const confirmar_nueva_clave = document.getElementById('id_confirmar_nueva_clave')?.value.trim();

    if (!clave || !confirmar_nueva_clave) {
        mostrarAlerta("error", "Error", "Todos los campos son obligatorios.");
        return;
    }

    if (clave !== confirmar_nueva_clave) {
        mostrarAlerta("error", "Error", "Las contraseñas no coinciden.");
        return;
    }

    if (clave.length < 6) {
        mostrarAlerta("error", "Error", "Las contraseñas deben tener al menos 6 caracteres.");
        return;
    }

    Swal.fire({
        title: 'Restableciendo...',
        text: 'Por favor espera un momento.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    const endpoints = [
        ApiConexion + "olvide/clave/admin",
        ApiConexion + "olvide/clave/empresa",
        ApiConexion + "olvide/clave/cliente"
    ];

    let exito = false;
    let mensajeError = "";

    // 🔹 Función auxiliar para evitar [object Object]
    const obtenerMensaje = (data) => {
        if (!data) return "Error desconocido.";
        if (typeof data.message === "string") return data.message;
        if (typeof data.mensaje === "string") return data.mensaje;
        if (typeof data.message === "object") return JSON.stringify(data.message);
        return "Error inesperado del servidor.";
    };
    for (const url of endpoints) {
        try {
            const respuesta = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ correo, clave })
            });

            const data = await respuesta.json();

            if (data.success === true) {
                exito = true;
                Swal.fire({
                    icon: "success",
                    title: "Restablecimiento Exitoso",
                    text: obtenerMensaje(data) || "Contraseña actualizada correctamente",
                    showConfirmButton: false,
                    timer: 2000
                });

                setTimeout(() => window.location.replace("index.php?ruta=login"), 1500);
                break;
            } else {
                // Guardamos el mensaje de error si la respuesta no es de éxito.
                mensajeError = obtenerMensaje(data);
            }
        } catch (error) {
            console.error("Error al intentar en:", url, error);
            mensajeError = "No se pudo conectar con el servidor. Revisa tu conexión a internet.";
        }
    }

    if (!exito) {
        Swal.close(); // Cerramos el 'loading' solo si no hubo éxito.
        mostrarAlerta("error", "Error", mensajeError || "No se encontró ninguna cuenta con este correo.");
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

    // Validación del checkbox de términos y condiciones
    const terminosAceptados = document.getElementById('check_terminos')?.checked;
    if (!terminosAceptados) {
        mostrarAlerta('warning', 'Términos y Condiciones', 'Debes aceptar los términos y condiciones para poder registrarte.');
        return;
    }

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

    //validar formato de correo
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(datos.correo)) {
        mostrarAlerta('error', 'Error de formato', 'Por favor ingresa un correo válido.');
    }

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

        console.log("🔹 Respuesta completa del backend:", data);
        console.log("🔹 Estado HTTP:", respuesta.status);

        // Normalizar estructura de respuesta desde el backend
        const success = data?.success === true || data?.status === true || data?.status === 'success';
        const token = data?.token || data?.access_token || data?.data?.token;
        const user = data?.user || data?.data?.user || data?.cliente;

        console.log("🔹 Success:", success);
        console.log("🔹 Token recibido:", token);
        console.log("🔹 Usuario recibido:", user);

        if (success) {
            if (token) {
                sessionStorage.setItem('userToken', token);
                console.log("Token", data);
                if (user) {
                    sessionStorage.setItem('userData', JSON.stringify(user));
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

        } else {
            Swal.close();
            let mensajeDetallado = 'Credenciales incorrectas o solicitud inválida.';

            // Si el backend envía un objeto con mensajes de error (Validator::errors())
            if (data?.message && typeof data.message === 'object') {
                mensajeDetallado = Object.values(data.message).join("\n");
            } else if (typeof data.message === 'string') {
                mensajeDetallado = data.message;
            }

            mostrarAlerta('error', 'Error al iniciar sesión', mensajeDetallado);

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

        // Normalizar estructura de respuesta desde el backend
        const success = data?.success === true || data?.status === true || data?.status === 'success';
        const token = data?.token || data?.access_token || data?.data?.token;
        const user = data?.user || data?.data?.user || data?.empresa;

        if (success) {
            if (token) {
                sessionStorage.setItem('userToken', token);
                if (user) {
                    user.rol = "empresa";
                    sessionStorage.setItem('userData', JSON.stringify(user));
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

        } else {
            Swal.close();
            const mensajeDetallado = data?.message || 'Credenciales incorrectas o solicitud inválida.';
            mostrarAlerta('error', 'Error al iniciar sesión', mensajeDetallado);
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

        // Normalizar estructura de respuesta desde el backend
        const success = data?.success === true || data?.status === true || data?.status === 'success';
        const token = data?.token || data?.access_token || data?.data?.token;
        const user = data?.user || data?.data?.user || data?.admin;

        if (success) {
            if (token) {
                sessionStorage.setItem('userToken', token);
                if (user) {
                    user.rol = "admin";
                    sessionStorage.setItem('userData', JSON.stringify(user));
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

        } else {
            Swal.close();
            const mensajeDetallado = data?.message || 'Credenciales incorrectas o solicitud inválida.';
            mostrarAlerta('error', 'Error al iniciar sesión', mensajeDetallado);
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

    Swal.fire({
        title: 'Cerrando Sesión...',
        text: 'Espere un momento.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

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
            Swal.fire({
                icon: "success",
                title: '¡Cerrar Sesión Exitoso!',
                text: data.message || 'Has cerrado sesión correctamente.',
                showConfirmButton: false,
                timer: 1000
            });

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

    if (!userDataString) {
        console.warn('⚠️ No hay datos de usuario en sessionStorage.');
        return;
    }

    try {
        const userData = JSON.parse(userDataString);
        console.log("👤 Datos del usuario cargados desde sessionStorage:", userData);

        // -----------------------------
        // 🧍 Dashboard de Usuario
        // -----------------------------
        const nombreUsuarioEl = document.getElementById('nombreUsuario');
        if (nombreUsuarioEl) {
            const nombre = userData.nombre;
            if (nombre) {
                nombreUsuarioEl.textContent = " " + nombre;
            }
        }

        // -----------------------------
        // 🏢 Dashboard de Empresa
        // -----------------------------
        const nombreEmpresaEl = document.getElementById('nombreEmpresa');
        if (nombreEmpresaEl) {
            const nombreEmpresa = userData.nombre_empresa;
            if (nombreEmpresa) {
                nombreEmpresaEl.textContent = " " + nombreEmpresa;
            }
        }

        // -----------------------------
        // 👨‍💼 Dashboard de Administrador
        // -----------------------------
        const nombreAdminEl = document.getElementById('nombreAdmin');
        if (nombreAdminEl) {
            const nombreAdmin = userData.nombres || userData.apellidos;
            if (nombreAdmin) {
                nombreAdminEl.textContent = " " + nombreAdmin;
            }
        }

    } catch (e) {
        console.error('❌ Error al parsear los datos del usuario desde sessionStorage:', e);
    }
}

// ✅ Ejecutar al cargar la página
document.addEventListener("DOMContentLoaded", populateUserData);




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
