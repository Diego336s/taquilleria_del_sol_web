document.addEventListener('DOMContentLoaded', () => {
    // Verificar Autenticación y Redirigir (Protección de Rutas)
    checkAuthAndRedirect();

    // Listar los eventos en la cartelera
    ctrListarEventos();

    // Poblar datos del usuario en la UI (ej. saludo en dashboard)
    populateUserData();

    // Event Listener para REGISTRO (formulario actualizar mi perfil)
    const update_Perfil = document.getElementById('form_actualizar_perfil');
    if (update_Perfil) {
        update_Perfil.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrupdatePerfil();
        });
    }

    // Event Listener para (formulario cambiar clave cliente/Config)
    const cambiar_clave_config = document.getElementById('form_cambiar_clave_config_cliente');
    if (cambiar_clave_config) {
        cambiar_clave_config.addEventListener('submit', async function (event) {
            console.log("prueba de envio");
            event.preventDefault();
            await ctrCambiarClaveConfigCliente();
        });
    };

    // Event Listener para (formulario cambiar correo cliente/Config)
    const cambiar_correo_config = document.getElementById('form_cambiar_correo_cliente');
    if (cambiar_correo_config) {
        cambiar_correo_config.addEventListener('submit', async function (event) {
            console.log("prueba de envio");
            event.preventDefault();
            await ctrCambiarCorreoConfigCliente();
        });
    }

    // Event Listener para el botón "Ver Detalles" de la próxima función
    const btnVerDetalles = document.getElementById('btnVerDetalles');
    if (btnVerDetalles) {
        btnVerDetalles.addEventListener('click', () => {
            // Aquí pasaríamos los datos del evento dinámicamente en un futuro
            mostrarDetallesEvento();
        });
    }

    //Cargar la proxima funncion del usuario
    cargarProximaFuncion();

    //Cargar el numero  de funciones vistas por el usuario
    cargarFuncionesVistas();

    //cargar el numero de próximas funciones del usuario
    cargarProximasFunciones();

    // Inicializar navegación de cartelera (flechas y estados)
    setupBillboardNav();
});



// =========================================================================
// FUNCION: ACTUALIZAR PERFIL CLIENTE
// =========================================================================

async function ctrupdatePerfil() {

    // 1. Obtener el token y los datos del usuario de la sesión
    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontraron datos de sesión. Por favor, inicia sesión de nuevo.');
        return;
    }

    // 2. Recolectar los datos del formulario
    const datos = {
        nombre: document.getElementById('profile_nombre')?.value.trim(),
        apellido: document.getElementById('profile_apellido')?.value.trim(),
        telefono: document.getElementById('profile_telefono')?.value.trim(),
        sexo: document.getElementById('profile_sexo')?.value,
    };

    // Validación simple de campos
    if (!datos.nombre || !datos.apellido || !datos.telefono || !datos.sexo) {
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
    const urlAPI = `${ApiConexion}actualizarCliente/${userData.id}`;


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
            const updatedUserData = data.cliente || data.user;
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

            //recargar la pagina despues de haber actualiza exitosamente
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

// =========================================================================
// FUNCION: CAMBIAR CLAVE CLIENTE/CONFIG
// =========================================================================

async function ctrCambiarClaveConfigCliente() {

    console.log("➡️ ctrCambiarClaveConfigCliente ejecutado");
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
    const urlAPI = `${ApiConexion}cambiar/clave/cliente/${userId}`;

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
                window.location.href = "index.php?ruta=configuracion_cliente";
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
// FUNCION: CAMBIAR CORREO CLIENTE/CONFIG
// =========================================================================

async function ctrCambiarCorreoConfigCliente() {

    // 1. Recolectar datos del formulario
    const correo = document.getElementById('id_correo_config_cliente')?.value;
    const confirmar_correo_nuevo = document.getElementById('id_confirm_correo_config_cliente')?.value;

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
    const urlAPI = `${ApiConexion}cambiar/correo/cliente/${userId}`;

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
                title: 'Correo Actualizada!',
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



// =========================================================================
// FUNCION: LISTAR EVENTOS EN EL DASHBOARD (MEJORADA)
// =========================================================================

async function ctrListarEventos() {
    const billboardContainer = document.querySelector('.billboard-list');
    if (!billboardContainer) {
        console.warn('No se encontró el contenedor de la cartelera (.billboard-list).');
        return;
    }

    // === LOADER TEATRAL ÉPICO ===
    billboardContainer.innerHTML = `
    <div id="loader-teatro" class="loader-teatro">
        <div class="spotlights"></div>
        <h1 class="loader-title">🎭 Preparando las funciones...</h1>
        <div class="stars">
          <span></span><span></span><span></span><span></span><span></span>
        </div>
    </div>
    `;

    // Esperar a que termine la animación antes de mostrar los eventos (10s)
    await new Promise(resolve => setTimeout(resolve, 2000));

    const urlAPI = ApiConexion + "eventos/disponibles";

    try {
        const respuesta = await fetch(urlAPI);
        if (!respuesta.ok) throw new Error(`Error HTTP ${respuesta.status} - ${respuesta.statusText}`);

        const data = await respuesta.json();
        billboardContainer.innerHTML = '';

        if (data.success && Array.isArray(data.eventos) && data.eventos.length > 0) {
            data.eventos.forEach(evento => {
                const imageUrl = evento.imagen || 'https://via.placeholder.com/320x200?text=Sin+Imagen';
                const descripcionCompleta = (evento.descripcion_corta || evento.descripcion || 'Sin descripción')
                    .trim().replace(/\n+/g, '<br>').replace(/\s{2,}/g, ' ');
                const maxChars = 120;
                const descripcionCorta =
                    descripcionCompleta.length > maxChars
                        ? descripcionCompleta.substring(0, maxChars) + '...'
                        : descripcionCompleta;

                const eventoCardHTML = `
                <div class="billboard-card">
                    <div class="card-image-container">
                        <img src="${imageUrl}" alt="${evento.titulo}" class="card-image">
                        <span class="genre-tag tag-drama">Categoría: ${evento.categoria?.nombre || 'General'}</span>
                        <span class="popularity-badge">⭐ 95%</span>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title">${evento.titulo}</h4>
                        <p class="card-description">${descripcionCorta}</p>
                        <div class="card-read-more">
                            ${descripcionCompleta.length > maxChars
                        ? `<a href="#" class="ver-mas" data-desc="${encodeURIComponent(descripcionCompleta)}">Ver más</a>`
                        : ''}
                        </div>
                        <div class="card-meta">
                            <span>🗓️ ${new Date(evento.fecha).toLocaleDateString('es-ES', {
                            day: 'numeric', month: 'long'
                        })}</span>
                            <span>🕒 ${new Date('1970-01-01T' + evento.hora_inicio)
                        .toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', hour12: true })}
                            </span>
                        </div>
                        <div class="card-footer">
                            <span class="popularity-text">Popularidad</span>
                            <div class="progress-bar-container small">
                                <div class="progress-bar-fill orange-bg" style="width: 95%;"></div>
                            </div>
                        </div>
                        <div class="card-booking">
                            <a href="index.php?ruta=seleccionar_asientos&eventoid=${evento.id}" class="btn btn-confirm">Reservar</a>
                        </div>
                    </div>
                </div>
                `;
                billboardContainer.insertAdjacentHTML('beforeend', eventoCardHTML);
            });

            document.querySelectorAll('.ver-mas').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const fullDesc = decodeURIComponent(btn.dataset.desc);
                    Swal.fire({
                        title: '<span style="color: #fff;">Descripción completa</span>',
                        html: `<p style="text-align:justify; color: #fff;">${fullDesc}</p>`,
                        background: 'rgba(10, 10, 10, 0.8)',
                        backdrop: `rgba(0,0,0,0.7)`,
                        confirmButtonText: 'Cerrar',
                        confirmButtonColor: '#ff8c00'
                    });
                });
            });

            setupBillboardNav();
        } else {
            billboardContainer.innerHTML = `<p class="text-white text-center">No hay eventos disponibles en este momento.</p>`;
        }

    } catch (error) {
        console.error("Error al listar eventos:", error);
        billboardContainer.innerHTML = `<p class="text-danger text-center">No se pudieron cargar los eventos. Inténtalo más tarde.</p>`;
    }
}

// =========================================================================
// FUNCION: MOSTRAR MODAL CON DETALLES DEL EVENTO
// =========================================================================

function mostrarDetallesEvento(dataFuncion) {

    const evento = dataFuncion.evento;
    const asientos = dataFuncion.asientos || [];

    // Elementos del modal
    const modal = document.getElementById("modalDetalles");
    const detalleTitulo = document.getElementById("detalleTitulo");
    const detalleFecha = document.getElementById("detalleFecha");
    const detalleHora = document.getElementById("detalleHora");
    const detalleAsientos = document.getElementById("detalleAsientos");

    // Poner datos
    detalleTitulo.textContent = evento.titulo;

    // Fecha
    detalleFecha.textContent = new Date(evento.fecha_evento).toLocaleDateString("es-ES", {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    // Hora
    detalleHora.textContent = new Date(`1970-01-01T${evento.hora_inicio}`).toLocaleTimeString(
        'es-ES',
        { hour: '2-digit', minute: '2-digit', hour12: true }
    );

    // Asientos
    detalleAsientos.innerHTML = "";
    if (asientos.length === 0) {
        detalleAsientos.innerHTML = `<li>Sin asientos reservados</li>`;
    } else {
        asientos.forEach(a => {
            detalleAsientos.innerHTML += `
                <li>${a.fila}${a.numero} (${a.ubicacion})</li>
            `;
        });
    }

    // Mostrar modal
    modal.style.display = "flex";

    // Cerrar modal
    document.getElementById("cerrarModalDetalles").onclick = () => {
        modal.style.display = "none";
    };

    window.onclick = (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    };
}



// =========================================================================
// FUNCION: CARGAR LA PRÓXIMA FUNCIÓN DEL USUARIO
// =========================================================================
async function cargarProximaFuncion() {

    // 1. Verificar sesión
    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        console.warn("No hay sesión activa para cargar la próxima función.");
        return;
    }

    const userData = JSON.parse(userDataString);
    const userId = userData.id;

    // 2. Contenedor donde se mostrarán los datos
    const container = document.getElementById('proxima-funcion-container');
    if (!container) {
        console.warn("No se encontró el contenedor #proxima-funcion-container");
        return;
    }

    // Mostrar loader
    container.innerHTML = `
        <div style="text-align:center; padding:10px; opacity:.6;">
            <i class="fas fa-spinner fa-spin"></i> Cargando próxima función...
        </div>
    `;

    // 3. Consumir el endpoint
    const urlAPI = `${ApiConexion}proxima-funcion/${userId}`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json'
            }
        });

        const data = await respuesta.json();

        // Si no hay función
        if (!data.success || !data.proxima_funcion) {
            container.innerHTML = `
                <div class="next-show-empty">
                    <h5>No tienes funciones programadas</h5>
                    <p>Cuando reserves una función, aparecerá aquí 📅🎭</p>
                </div>
            `;
            return;
        }

        // ==========================
        // 4. Extraer datos
        // ==========================
        const funcion = data.proxima_funcion.evento;

        const titulo = funcion.titulo || "Evento sin nombre";

        // Fecha corregida
        const fecha = funcion.fecha_evento
            ? new Date(funcion.fecha_evento).toLocaleDateString("es-ES", {
                day: 'numeric',
                month: 'long'
            })
            : "Sin fecha";

        // Hora corregida
        const hora = funcion.hora_inicio
            ? new Date(`1970-01-01T${funcion.hora_inicio}`).toLocaleTimeString(
                'es-ES',
                { hour: '2-digit', minute: '2-digit', hour12: true }
            )
            : "Sin hora";

        // Mostrar asientos REALMENTE devueltos
        const asientos = data.proxima_funcion.asientos?.length
            ? data.proxima_funcion.asientos
                .map(a => `${a.fila}${a.numero}`)
                .join(", ")
            : "Sin asientos asignados";

        // Imagen (si después agregas campo)

        // ==========================
        // 5. Render HTML
        // ==========================
        container.innerHTML = `
            <div class="next-show-card">
                <div class="next-show-content">
                    <h4>${titulo}</h4>

                    <p>
                        <strong>Fecha:</strong> ${fecha}<br>
                        <strong>Hora:</strong> ${hora}<br>
                        <strong>Asientos:</strong> ${asientos}
                    </p>

                    <button id="btnVerDetalles" class="btn btn-confirm" style="border: 1px solid rgba(255, 255, 255, 0.4);">
                        Ver detalles
                    </button>
                </div>
            </div>
        `;

        // ==========================
        // 6. Botón detalles
        // ==========================
        const btn = document.getElementById('btnVerDetalles');
        if (btn) {
            btn.addEventListener('click', () => {
                mostrarDetallesEvento(data.proxima_funcion);
            });
        }

    } catch (error) {
        console.error("Error al cargar próxima función:", error);
        container.innerHTML = `
            <div class="next-show-error">
                <p>Error al cargar la información. Intenta más tarde.</p>
            </div>
        `;
    }
}


async function cargarFuncionesVistas() {

    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        console.warn("No hay sesión activa.");
        return;
    }

    const userData = JSON.parse(userDataString);
    const userId = userData.id;

    const numeroWidget = document.querySelector("#obras_vistas");

    if (!numeroWidget) {
        console.warn("No se encontró el elemento #obras_vistas");
        return;
    }

    // Mostrar cargando mientras consulta
    numeroWidget.innerHTML = `<i class="fas fa-spinner fa-spin" style="font-size: 24px; opacity: 0.7;"></i>`;

    try {
        const respuesta = await fetch(`${ApiConexion}contador/funciones-vistas/${userId}`, {
            method: "GET",
            headers: {
                "Authorization": "Bearer " + token,
                "Content-Type": "application/json"
            }
        });

        const data = await respuesta.json();

        if (!data.success) {
            numeroWidget.textContent = "0";
            return;
        }

        numeroWidget.textContent = data.funciones_vistas;

    } catch (error) {
        console.error("Error al cargar funciones vistas:", error);
        numeroWidget.textContent = "0";
    }
}

async function cargarProximasFunciones() {

const token = sessionStorage.getItem('userToken');
const userDataString = sessionStorage.getItem('userData');

if (!token || !userDataString) {
    console.warn("No hay sesión activa.");
    return;
}

const userData = JSON.parse(userDataString);
const userId = userData.id;

const numeroWidget = document.querySelector("#proxima_funcion");

if (!numeroWidget) {
    console.warn("No se encontró el elemento #proxima_funcion");
    return;
}

// Spinner
numeroWidget.innerHTML =
    `<i class="fas fa-spinner fa-spin" style="font-size: 24px; opacity: 0.7;"></i>`;

try {
    const response = await fetch(`${ApiConexion}contador/proxima-funcion/${userId}`, {
        method: "GET",
        headers: {
            "Authorization": "Bearer " + token,
            "Content-Type": "application/json"
        }
    });

    const data = await response.json();

    console.log("Respuesta API:", data);

    // Si no viene éxito
    if (!data.success) {
        numeroWidget.textContent = "0";
        return;
    }

    // Mostrar el número devuelto por la API
    numeroWidget.textContent = data.proximas_funciones ?? "0";

} catch (e) {
    console.error("Error cargando próximas funciones:", e);
    numeroWidget.textContent = "0";
}


}


// =========================================================================
// FUNCION: LISTAR SILLAS DEL TEATRO
// =========================================================================



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

/* =========================================================================
   UI: Navegación para la Cartelera (scroll interno con flechas y rueda)
   ========================================================================= */
function setupBillboardNav() {
    const section = document.querySelector('.seccion-cartelera');
    if (!section) return;

    const list = section.querySelector('.billboard-list');
    const btnLeft = section.querySelector('.nav-left');
    const btnRight = section.querySelector('.nav-right');
    if (!list || !btnLeft || !btnRight) return;

    // Si ya está inicializado, fuerza un recalculo del estado de flechas
    if (list.dataset.navInitialized === 'true') {
        // Dispara un resize para que los listeners actualicen estado
        window.dispatchEvent(new Event('resize'));
        return;
    }

    const getGap = () => {
        const styles = getComputedStyle(list);
        const gapVal = styles.gap || styles.columnGap || '25px';
        const n = parseFloat(gapVal);
        return isNaN(n) ? 25 : n;
    };

    const getStep = () => {
        const first = list.querySelector('.billboard-card');
        if (first) {
            const rect = first.getBoundingClientRect();
            return Math.round(rect.width + getGap());
        }
        // Fallback si aún no hay tarjetas
        return Math.round(list.clientWidth * 0.9);
    };

    const scrollLeftBy = () => list.scrollBy({ left: -getStep(), behavior: 'smooth' });
    const scrollRightBy = () => list.scrollBy({ left: getStep(), behavior: 'smooth' });

    btnLeft.addEventListener('click', scrollLeftBy);
    btnRight.addEventListener('click', scrollRightBy);

    // Convierte la rueda vertical en desplazamiento horizontal SOLO dentro de la lista
    list.addEventListener('wheel', (e) => {
        const { deltaX, deltaY } = e;
        if (Math.abs(deltaY) > Math.abs(deltaX)) {
            list.scrollBy({ left: deltaY, behavior: 'auto' });
            e.preventDefault();
        }
    }, { passive: false });

    function updateArrows() {
        const maxScrollLeft = list.scrollWidth - list.clientWidth - 1;
        const isAtStart = list.scrollLeft <= 0;
        const isAtEnd = list.scrollLeft >= maxScrollLeft;

        btnLeft.classList.toggle('disabled', list.scrollLeft <= 0);
        btnRight.classList.toggle('disabled', list.scrollLeft >= maxScrollLeft);
        section.classList.toggle('at-start', isAtStart);
        section.classList.toggle('at-end', isAtEnd);
    }

    list.addEventListener('scroll', updateArrows);
    window.addEventListener('resize', updateArrows);

    // Estado inicial en el siguiente frame (asegura medidas correctas)
    requestAnimationFrame(updateArrows);

    list.dataset.navInitialized = 'true';
}

// =========================================================================
// FUNCION: ELIMINAR CUENTA DE CLIENTE
// =========================================================================

async function ctrEliminarCuenta() {
    // 1. Obtener token y datos de sesión
    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'No se puede realizar esta acción sin una sesión activa.');
        return;
    }

    // 2. Mostrar alerta de "cargando"
    Swal.fire({
        title: 'Eliminando tu cuenta...',
        text: 'Este proceso puede tardar unos segundos. No cierres esta ventana.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // 3. Preparar y ejecutar la llamada a la API
    try {
        const userData = JSON.parse(userDataString);
        const userId = userData.id;
        const urlAPI = `${ApiConexion}eliminar/cliente/${userId}`;

        const respuesta = await fetch(urlAPI, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (data.success === true) {
            // 4. Limpiar sesión y redirigir
            sessionStorage.clear();
            Swal.fire({
                icon: 'success',
                title: 'Cuenta Eliminada',
                text: data.message || 'Tu cuenta ha sido eliminada con éxito. Esperamos verte de nuevo.',
                showConfirmButton: false,
                timer: 2500
            }).then(() => {
                window.location.replace("index.php?ruta=login");
            });
        } else {
            mostrarAlerta('error', 'Error al eliminar', data.message || 'No se pudo completar la eliminación de la cuenta.');
        }
    } catch (error) {
        Swal.close();
        console.error("Error al eliminar cuenta:", error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor para eliminar la cuenta.');
    }
}
