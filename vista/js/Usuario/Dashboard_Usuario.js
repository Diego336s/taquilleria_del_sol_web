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

function mostrarDetallesEvento(evento) {
    // Datos de ejemplo (en un futuro, estos vendrían del objeto 'evento')
    const datosEvento = {
        titulo: "Don Juan Tenorio",
        imagenUrl: "https://www.clasicostelemundo.com/images/2019/10/29/don-juan-tenorio.jpg",
        descripcion: "La clásica obra de José Zorrilla sobre el seductor más famoso de la literatura española. Una historia de amor, arrepentimiento y redención que desafía las barreras entre la vida y la muerte. Vive una noche de pasión y drama en el Teatro del Sol.",
        fecha: "12 de Enero",
        hora: "8:30 PM",
        asientos: "Palco A12, A13"
    };

    Swal.fire({
        title: '',
        html: `
            <div style="color: #fff; text-align: left;">
                <img src="${datosEvento.imagenUrl}" alt="${datosEvento.titulo}" style="width: 100%; border-radius: 15px; margin-bottom: 1.5rem;">
                <h2 style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">${datosEvento.titulo}</h2>
                <p style="color: #e0e0e0; margin-bottom: 2rem;">${datosEvento.descripcion}</p>
                
                <div style="background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 10px;">
                    <h4 style="border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 0.5rem; margin-bottom: 1rem;">Detalles de tu Reserva</h4>
                    <div style="display: flex; justify-content: space-around; font-size: 1.1rem;">
                        <span><i class="fas fa-calendar-alt me-2"></i> ${datosEvento.fecha}</span>
                        <span><i class="fas fa-clock me-2"></i> ${datosEvento.hora}</span>
                        <span><i class="fas fa-chair me-2"></i> ${datosEvento.asientos}</span>
                    </div>
                </div>
            </div>
        `,
        background: 'var(--card-background)',
        backdrop: `
            rgba(0,0,0,0.4)
            url("https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExY2l2b2dnbWJ1dGk5c3JjYjd0Y2w5c244bXlrc3J2Z2tndmRkdjZpZCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9cw/l3q2zbskZp2j8wniE/giphy.gif")
            center top
            no-repeat
        `,
        background: 'rgba(10, 10, 10, 0.8)', // Fondo más oscuro y sólido
        backdrop: `rgba(0,0,0,0.7)`, // Fondo exterior más oscuro sin GIF
        showCloseButton: true,
        showConfirmButton: false,
        width: '800px',
        padding: '2em',
        customClass: {
            popup: 'swal2-glass-popup',
            closeButton: 'swal2-close-button-custom'
        }
    });
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
