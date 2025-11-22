// Archivo: Dashboard_Empresa.js

document.addEventListener('DOMContentLoaded', () => {

    populateUserData();

    // Event Listener para actualizar perfil
    const update_Empresa = document.getElementById('form_actualizar_perfil_empresa');
    if (update_Empresa) {
        update_Empresa.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrupdatePerfilEmpresa();
        });
    }
    
    // Event Listener cambiar clave
    const cambiar_clave_config = document.getElementById('form_cambiar_clave_config_empresa');
    if (cambiar_clave_config) {
        cambiar_clave_config.addEventListener('submit', async function (event) {
            console.log("prueba de envio");
            event.preventDefault();
            await ctrCambiarClaveConfigEmpresa();
        });
    }

    // Event listener cambiar correo
    const cambiar_correo_config = document.getElementById('form_cambiar_correo_config_empresa');
    if (cambiar_correo_config) {
        cambiar_correo_config.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrCambiarCorreoConfigEmpresa();
        });
    }

    // Event Listener eliminar cuenta
    const btn_eliminar_cuenta = document.getElementById('btn_eliminar_cuenta_empresa');
    if (btn_eliminar_cuenta) {
        btn_eliminar_cuenta.addEventListener('click', async function (event) {
            event.preventDefault();
            await ctrEliminarCuentaEmpresa();
        });
    }

// Event Listener registrar evento
const form_registrar_evento = document.getElementById('form_registrar_evento');
if (form_registrar_evento) {
    form_registrar_evento.addEventListener('submit', async function (event) {
        event.preventDefault();
        await ctrRegistrarEvento();
    });
}




});

// =========================================================================
// FUNCIÓN: ACTUALIZAR PERFIL EMPRESA
// =========================================================================
async function ctrupdatePerfilEmpresa() {

    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'Por favor inicia sesión nuevamente.');
        return;
    }

    const datos = {
        nombre_empresa: document.getElementById('profile_nombre').value.trim(),
        nit: document.getElementById('profile_nit').value.trim(),
        representante_legal: document.getElementById('profile_representante_legal').value.trim(),
        documento_representante: document.getElementById('profile_documento_representante').value.trim(),
        nombre_contacto: document.getElementById('profile_nombre_contacto').value.trim(),
        telefono: document.getElementById('profile_telefono').value.trim(),
    };

    // Validación
    if (!datos.nombre_empresa || !datos.nit || !datos.representante_legal ||
        !datos.documento_representante || !datos.nombre_contacto || !datos.telefono) {
        mostrarAlerta('error', 'Campos incompletos', 'Por favor, rellena todos los campos requeridos.');
        return;
    }

    if (!/^\d+$/.test(datos.nit)) {
        mostrarAlerta('error', 'NIT inválido', 'El NIT debe contener solo números.');
        return;
    }

    if (!/^\d+$/.test(datos.documento_representante)) {
        mostrarAlerta('error', 'Documento inválido', 'El documento del representante debe contener solo números.');
        return;
    }

    if (!/^\d+$/.test(datos.telefono)) {
        mostrarAlerta('error', 'Teléfono inválido', 'El teléfono debe contener solo números.');
        return;
    }

    Swal.fire({
        title: 'Actualizando perfil...',
        text: 'Por favor, espera un momento.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    const userData = JSON.parse(userDataString);
    const urlAPI = `${ApiConexion}actualizarEmpresa/${userData.id}`;

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
        console.log("Response status:", respuesta.status);
        console.log("Response data:", data);
        Swal.close();

        // *** CORRECCIÓN PRINCIPAL ***
        if (respuesta.ok) {

            // Si no viene data.empresa, usamos los datos enviados
            let updatedUserData = data.empresa || data.data || data || datos;

            sessionStorage.setItem('userData', JSON.stringify(updatedUserData));
            populateUserData();

            Swal.fire({
                icon: 'success',
                title: '¡Perfil Actualizado!',
                text: data.message || 'La información se ha guardado correctamente.',
                timer: 2000,
                showConfirmButton: false
            });

            setTimeout(() => {
                window.location.reload();
            }, 2000);

        } else {
            mostrarAlerta('error', 'Error al actualizar', data.message || 'No se pudieron guardar los cambios.');
        }

    } catch (error) {
        Swal.close();
        console.error("Error al actualizar perfil de empresa:", error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
    }
}

// =========================================================================
// FUNCIÓN AUXILIAR: MOSTRAR ALERTAS
// =========================================================================
function mostrarAlerta(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        confirmButtonColor: '#3085d6'
    });
}

// =========================================================================
// FUNCIÓN: CAMBIAR CLAVE EMPRESA
// =========================================================================

async function ctrCambiarClaveConfigEmpresa() {

    console.log("➡️ ctrCambiarClaveConfigEmpresa ejecutado");
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
    const urlAPI = `${ApiConexion}cambiarClave/${userId}`;

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
                window.location.href = "index.php?ruta=configuraciones_empresa";
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





// =========================================================================
// FUNCIÓN: CAMBIAR CORREO EMPRESA
// =========================================================================

async function ctrCambiarCorreoConfigEmpresa() {

    const correo = document.getElementById('id_correo_config_empresa')?.value;
    const confirmar_correo_nuevo = document.getElementById('id_confirm_correo_config_empresa')?.value;

    if (!correo || !confirmar_correo_nuevo) {
        mostrarAlerta('error', 'Campos incompletos', 'Por favor, rellena todos los campos.');
        return;
    }

    if (correo !== confirmar_correo_nuevo) {
        mostrarAlerta('error', 'Error de validación', 'Los correos no coinciden.');
        return;
    }

    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'Por favor inicia sesión nuevamente.');
        return;
    }

    const userData = JSON.parse(userDataString);
    const userId = userData.id;

    Swal.fire({
        title: 'Actualizando correo...',
        text: 'Por favor, espera un momento.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    const datos = { correo: correo };
    const urlAPI = `${ApiConexion}cambiar/correo/empresa/${userId}`;

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
        console.log("Respuesta:", data);
        Swal.close();

        if (respuesta.ok) {

            sessionStorage.removeItem('userToken');
            sessionStorage.removeItem('userData');

            Swal.fire({
                icon: 'success',
                title: 'Correo actualizado',
                text: data.message || 'Tu correo ha sido cambiado.',
                timer: 2000,
                showConfirmButton: false
            });

            setTimeout(() => {
                window.location.href = "index.php?ruta=login";
            }, 2000);

        } else {
            mostrarAlerta('error', 'Error al actualizar', data.message || 'No se pudo cambiar el correo.');
        }

    } catch (error) {
        Swal.close();
        console.error("Error al cambiar correo:", error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
    }
}

// =========================================================================
// FUNCIÓN: Registrar Evento
// =========================================================================
async function ctrRegistrarEvento() {

    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'Por favor inicia sesión nuevamente.');
        return;
    }

    // Obtener valores del formulario
    const titulo = document.getElementById("titulo").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();
    const fecha = document.getElementById("fecha").value;

    const hora_inicio_raw = document.getElementById("hora_inicio").value;
    const hora_final_raw = document.getElementById("hora_fin").value;

    const hora_inicio = hora_inicio_raw + ":00";
    const hora_final = hora_final_raw + ":00";

    const precioPrimerPiso = document.getElementById("precio_primer_piso").value;
    const precioSegundoPiso = document.getElementById("precio_segundo_piso").value;
    const precioGeneral = document.getElementById("precio_general").value;

    const imagen = document.getElementById("imagen").files[0];
    const estado = document.getElementById("estado").value;
    const categoria_id = document.getElementById("categoria_id").value;

    if (!titulo || !descripcion || !fecha || !hora_inicio || !hora_final ||
        !precioPrimerPiso || !precioSegundoPiso || !precioGeneral ||
        !estado || !categoria_id || !imagen) {

        mostrarAlerta('error', 'Campos incompletos', 'Por favor, rellena todos los campos.');
        return;
    }

    const userData = JSON.parse(userDataString);
    const empresa_id = userData.id;

    Swal.fire({
        title: 'Registrando evento...',
        text: 'Por favor, espera un momento.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    // FormData
    const formData = new FormData();
    formData.append("titulo", titulo);
    formData.append("descripcion", descripcion);
    formData.append("fecha", fecha);
    formData.append("hora_inicio", hora_inicio);
    formData.append("hora_final", hora_final);
    formData.append("precio_primer_piso", precioPrimerPiso);
    formData.append("precio_segundo_piso", precioSegundoPiso);
    formData.append("precio_general", precioGeneral);
    formData.append("estado", estado);
    formData.append("categoria_id", categoria_id);
    formData.append("empresa_id", empresa_id);
    formData.append("imagen", imagen);

    try {
        const response = await fetch(`${ApiConexion}registrarEventos`, {
            method: "POST",
            headers: { "Authorization": "Bearer " + token },
            body: formData
        });

        const data = await response.json();
        Swal.close();

        if (response.ok && data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Evento Registrado!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });

            document.getElementById("form_registrar_evento").reset();
            cargarCategorias(); // refrescar categorías si se agregan nuevas
        } else {
            mostrarAlerta('error', 'Error al registrar evento', data.message);
        }

    } catch (error) {
        Swal.close();
        mostrarAlerta('error', 'Error de conexión', 'No se pudo comunicar con el servidor.');
        console.error(error);
    }
}

// =========================================================================
// FUNCIÓN: cargar categorías en el formulario de registro de eventos
// =========================================================================

document.addEventListener("DOMContentLoaded", cargarCategorias);


async function cargarCategorias() {
    const token = sessionStorage.getItem('userToken');
    if (!token) return;

    const select = document.getElementById("categoria_id");
    select.innerHTML = '<option value="">Cargando categorías...</option>';

    try {
        const respuesta = await fetch('listarCategorias', {
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });

        const resultado = await respuesta.json();

        // Verifica si el API devuelve success y data
        if (resultado.success && Array.isArray(resultado.data)) {
            select.innerHTML = '<option value="">Seleccione una categoría</option>';

            resultado.data.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.id;   // ajusta según tu campo
                option.textContent = categoria.nombre; // ajusta según tu campo
                select.appendChild(option);
            });
        } else {
            console.error("Formato inesperado de la API:", resultado);
            select.innerHTML = '<option value="">No se pudieron cargar las categorías</option>';
        }
    } catch (error) {
        console.error("Error cargando categorías:", error);
        select.innerHTML = '<option value="">Error cargando categorías</option>';
    }
}




