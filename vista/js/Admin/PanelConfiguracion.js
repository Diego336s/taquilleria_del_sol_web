document.addEventListener('DOMContentLoaded', () => {
    // Verificar Autenticación y Redirigir (Protección de Rutas)
    checkAuthAndRedirect();

    // Poblar datos del administrador en la UI
    populateAdminData();

    // Cargar configuración actual
    ctrCargarConfiguracion();

    // Aplicar configuración visual al cargar
    const config = JSON.parse(localStorage.getItem('adminConfig') || '{}');
    aplicarConfiguracionVisual(config);
});

// =========================================================================
// 🔐 FUNCIÓN: PROTECCIÓN DE RUTAS PARA ADMIN
// =========================================================================

function checkAuthAndRedirect() {
    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontraron datos de sesión. Redirigiendo al login.');
        window.location.replace("../../../index.php?ruta=login");
        return;
    }

    try {
        const userData = JSON.parse(userDataString);
        const userRole = userData.rol || userData.role || userData.tipo || 'user';
        if (userRole.toLowerCase() !== 'admin' && userRole.toLowerCase() !== 'administrator') {
            console.warn('Usuario no es admin, rol actual:', userRole);
            window.location.replace("../../../index.php?ruta=dashboard-usuario");
            return;
        }
    } catch (e) {
        console.error('Error parseando userData:', e);
        sessionStorage.clear();
        window.location.replace("../../../index.php?ruta=login");
        return;
    }
}

// =========================================================================
// ✨ FUNCIÓN: POBLAR DATOS DEL ADMIN EN LA UI
// =========================================================================

function populateAdminData() {
    const userDataString = sessionStorage.getItem('userData');

    if (!userDataString) {
        console.warn('⚠️ No hay datos de administrador en sessionStorage.');
        return;
    }

    try {
        const userData = JSON.parse(userDataString);
        console.log("👨‍💼 Datos del administrador cargados desde sessionStorage:", userData);
        // Aquí podríamos poblar algún elemento si fuera necesario
    } catch (e) {
        console.error('❌ Error al parsear los datos del administrador desde sessionStorage:', e);
    }
}

// =========================================================================
// ⚙️ FUNCIÓN: CARGAR CONFIGURACIÓN
// =========================================================================

async function ctrCargarConfiguracion() {
    // Cargar configuración desde localStorage (sin backend)
    cargarConfiguracionLocalStorage();
}

// =========================================================================
// 💾 FUNCIÓN: CARGAR CONFIGURACIÓN DESDE LOCALSTORAGE
// =========================================================================

function cargarConfiguracionLocalStorage() {
    const config = JSON.parse(localStorage.getItem('adminConfig') || '{}');

    // Tema y apariencia
    document.getElementById('modoOscuro').value = config.modoOscuro || 'false';
    document.getElementById('colorPrincipal').value = config.colorPrincipal || '#ff6b1f';
    document.getElementById('logoTema').value = config.logoTema || '';
    document.getElementById('tamañoFuenteSelect').value = config.tamañoFuente || '14';

    // Sistema
    document.getElementById('precioBase').value = config.precioBase || 50000;
    document.getElementById('horaApertura').value = config.horaApertura || '08:00';
    document.getElementById('horaCierre').value = config.horaCierre || '22:00';
    document.getElementById('correoNotificaciones').value = config.correoNotificaciones || 'admin@taquilleria.com';
    document.getElementById('estadoSistema').value = config.estadoSistema || 'activo';
    document.getElementById('mensajeMantenimiento').value = config.mensajeMantenimiento || '';

    // Notificaciones
    document.getElementById('notificacionesSonoras').checked = config.notificacionesSonoras !== false;
    document.getElementById('notificacionesVisuales').checked = config.notificacionesVisuales !== false;
    document.getElementById('notificacionesEmail').checked = config.notificacionesEmail !== false;
    document.getElementById('volumenNotificaciones').value = config.volumenNotificaciones || 50;

    // Idioma y regional
    document.getElementById('idioma').value = config.idioma || 'es';
    document.getElementById('zonaHoraria').value = config.zonaHoraria || 'America/Bogota';
    document.getElementById('formatoFecha').value = config.formatoFecha || 'DD/MM/YYYY';

    // Cargar estadísticas
    actualizarEstadisticas();

    // Aplicar configuración visual inicial
    aplicarConfiguracionVisual(config);
    aplicarTamañoFuente(config.tamañoFuente || '14');
}

// =========================================================================
// 💾 FUNCIÓN: GUARDAR CONFIGURACIÓN
// =========================================================================

async function guardarConfiguracion() {
    // Recolectar datos del formulario
    const configuracion = {
        // Tema y apariencia
        modoOscuro: document.getElementById('modoOscuro').value,
        colorPrincipal: document.getElementById('colorPrincipal').value,
        logoTema: document.getElementById('logoTema').value.trim(),
        tamañoFuente: document.getElementById('tamañoFuenteSelect').value,

        // Sistema
        precioBase: document.getElementById('precioBase').value,
        horaApertura: document.getElementById('horaApertura').value,
        horaCierre: document.getElementById('horaCierre').value,
        correoNotificaciones: document.getElementById('correoNotificaciones').value,
        estadoSistema: document.getElementById('estadoSistema').value,
        mensajeMantenimiento: document.getElementById('mensajeMantenimiento').value.trim(),

        // Notificaciones
        notificacionesSonoras: document.getElementById('notificacionesSonoras').checked,
        notificacionesVisuales: document.getElementById('notificacionesVisuales').checked,
        notificacionesEmail: document.getElementById('notificacionesEmail').checked,
        volumenNotificaciones: document.getElementById('volumenNotificaciones').value,

        // Idioma y regional
        idioma: document.getElementById('idioma').value,
        zonaHoraria: document.getElementById('zonaHoraria').value,
        formatoFecha: document.getElementById('formatoFecha').value
    };

    // Validaciones básicas
    if (!configuracion.precioBase || configuracion.precioBase < 0) {
        mostrarAlerta('error', 'Precio inválido', 'El precio base debe ser un número positivo.');
        return;
    }

    if (!configuracion.correoNotificaciones || !configuracion.correoNotificaciones.includes('@')) {
        mostrarAlerta('error', 'Correo inválido', 'Por favor ingresa un correo electrónico válido.');
        return;
    }

    // Guardar solo en localStorage
    localStorage.setItem('adminConfig', JSON.stringify(configuracion));
    mostrarAlerta('success', 'Configuración guardada', 'Los cambios se han guardado localmente. ✅');

    // Aplicar cambios visuales inmediatamente
    aplicarConfiguracionVisual(configuracion);

    // Aplicar idioma si cambió
    aplicarIdioma(configuracion.idioma);

    // Aplicar tamaño de fuente
    aplicarTamañoFuente(configuracion.tamañoFuente);

    // Actualizar indicadores de estado
    actualizarIndicadoresEstado(configuracion);
}

// =========================================================================
// 🎨 FUNCIÓN: APLICAR CONFIGURACIÓN VISUAL
// =========================================================================

function aplicarConfiguracionVisual(config) {
    // Aplicar modo oscuro
    if (config.modoOscuro === 'true') {
        document.body.classList.add('dark-mode');
        // Cambiar estilos para modo oscuro
        document.body.style.backgroundColor = '#1a1a1a';
        document.querySelectorAll('.dashboard-container').forEach(el => {
            el.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            el.style.color = '#fff';
        });
        document.getElementById('temaActual').textContent = 'Oscuro';
    } else {
        document.body.classList.remove('dark-mode');
        // Restaurar estilos claros
        document.body.style.backgroundColor = '';
        document.querySelectorAll('.dashboard-container').forEach(el => {
            el.style.backgroundColor = '';
            el.style.color = '';
        });
        document.getElementById('temaActual').textContent = 'Claro';
    }

    // Aplicar color principal
    document.documentElement.style.setProperty('--primary-color', config.colorPrincipal);

    // Aplicar logo si existe
    if (config.logoTema) {
        const logoElement = document.querySelector('.logo');
        if (logoElement) {
            logoElement.src = config.logoTema;
        }
    }

    // Aplicar mensaje de mantenimiento si existe
    if (config.mensajeMantenimiento && config.estadoSistema === 'mantenimiento') {
        mostrarMensajeMantenimiento(config.mensajeMantenimiento);
    } else {
        ocultarMensajeMantenimiento();
    }

    // Actualizar indicadores de notificaciones
    actualizarIndicadoresNotificaciones(config);
}

// =========================================================================
// 📏 FUNCIÓN: APLICAR TAMAÑO DE FUENTE
// =========================================================================

function aplicarTamañoFuente(tamaño) {
    const sizeMap = {
        '12': '12px',
        '14': '14px',
        '16': '16px',
        '18': '18px'
    };

    document.documentElement.style.setProperty('--font-size-base', sizeMap[tamaño] || '14px');
    document.getElementById('tamañoFuente').textContent = sizeMap[tamaño] || '14px';

    // Aplicar a elementos específicos
    document.body.style.fontSize = sizeMap[tamaño] || '14px';
}

// =========================================================================
// 🔄 FUNCIÓN: CAMBIAR TEMA
// =========================================================================

function cambiarTema() {
    const select = document.getElementById('modoOscuro');
    const currentValue = select.value;
    select.value = currentValue === 'true' ? 'false' : 'true';

    // Aplicar cambio inmediatamente
    const config = JSON.parse(localStorage.getItem('adminConfig') || '{}');
    config.modoOscuro = select.value;
    aplicarConfiguracionVisual(config);

    mostrarAlerta('success', 'Tema cambiado', `Tema cambiado a ${select.value === 'true' ? 'oscuro' : 'claro'}.`);
}

// =========================================================================
// 🔍 FUNCIÓN: VERIFICAR ESTADO DEL SISTEMA
// =========================================================================

async function verificarEstadoSistema() {
    mostrarAlerta('info', 'Verificando...', 'Comprobando estado del sistema...');

    try {
        // Simular verificación de API
        const response = await fetch(`${ApiConexion}usuarios`, {
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + sessionStorage.getItem('userToken') }
        });

        const serverStatus = response.ok ? 'OK' : 'Error';
        const dbStatus = response.ok ? 'OK' : 'Error';
        const apiStatus = response.ok ? 'OK' : 'Error';

        // Actualizar indicadores
        document.getElementById('serverStatus').innerHTML = `🖥️ Servidor <span class="status-${serverStatus.toLowerCase()}">● ${serverStatus}</span>`;
        document.getElementById('dbStatus').innerHTML = `💾 Base Datos <span class="status-${dbStatus.toLowerCase()}">● ${dbStatus}</span>`;
        document.getElementById('apiStatus').innerHTML = `🔗 API <span class="status-${apiStatus.toLowerCase()}">● ${apiStatus}</span>`;

        mostrarAlerta('success', 'Verificación completada', 'Estado del sistema actualizado.');

    } catch (error) {
        console.error('Error verificando estado:', error);
        mostrarAlerta('error', 'Error de conexión', 'No se pudo verificar el estado del sistema.');
    }
}

// =========================================================================
// 🔧 FUNCIONES DE CONFIGURACIÓN ADICIONALES
// =========================================================================

function actualizarIndicadoresEstado(config) {
    // Actualizar indicadores de notificaciones
    document.getElementById('notificacionesActivas').textContent = config.notificacionesSonoras || config.notificacionesVisuales ? 'ON' : 'OFF';
    document.getElementById('sonidoActivo').textContent = config.notificacionesSonoras ? 'ON' : 'OFF';
    document.getElementById('idiomaActual').textContent = config.idioma.toUpperCase();
}

function actualizarIndicadoresNotificaciones(config) {
    document.getElementById('notificacionesActivas').textContent = config.notificacionesSonoras || config.notificacionesVisuales ? 'ON' : 'OFF';
    document.getElementById('sonidoActivo').textContent = config.notificacionesSonoras ? 'ON' : 'OFF';
}

function restaurarConfiguracion() {
    if (confirm('¿Estás seguro de que quieres restaurar la configuración por defecto?')) {
        localStorage.removeItem('adminConfig');
        location.reload();
        mostrarAlerta('success', 'Configuración restaurada', 'Se ha restaurado la configuración por defecto.');
    }
}

function exportarConfiguracion() {
    const config = localStorage.getItem('adminConfig') || '{}';
    const dataStr = JSON.stringify(JSON.parse(config), null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

    const exportFileDefaultName = 'configuracion_admin.json';

    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();

    mostrarAlerta('success', 'Configuración exportada', 'El archivo de configuración se ha descargado.');
}

// =========================================================================
// 🌍 FUNCIÓN: APLICAR IDIOMA
// =========================================================================

function aplicarIdioma(idioma) {
    // Guardar idioma en localStorage
    localStorage.setItem('idioma', idioma);

    // Aquí se implementarían las traducciones
    // Por ahora solo guardamos la preferencia
    console.log('Idioma aplicado:', idioma);
}

// =========================================================================
// 📊 FUNCIÓN: ACTUALIZAR ESTADÍSTICAS
// =========================================================================

async function actualizarEstadisticas() {
    const token = sessionStorage.getItem('userToken');

    if (!token) {
        console.warn('No hay token para actualizar estadísticas');
        return;
    }

    try {
        // Obtener estadísticas de usuarios
        const usuariosResponse = await fetch(`${ApiConexion}usuarios`, {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const usuariosData = await usuariosResponse.json();

        // Obtener estadísticas de empresas
        const empresasResponse = await fetch(`${ApiConexion}empresas`, {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const empresasData = await empresasResponse.json();

        // Actualizar UI con datos simulados ya que no tenemos endpoints específicos
        const usuariosActivos = usuariosData.usuarios ? usuariosData.usuarios.filter(u => u.estado === 'Activo').length : 0;
        const empresasRegistradas = empresasData.empresas ? empresasData.empresas.length : 0;

        // Datos simulados para tickets e ingresos (ya que no están en el backend actual)
        const ticketsVendidos = Math.floor(Math.random() * 50) + 10;
        const ingresosDia = (ticketsVendidos * 50000).toLocaleString('es-CO', { style: 'currency', currency: 'COP' });

        // Actualizar elementos del DOM
        document.getElementById('usuariosActivosHoy').textContent = usuariosActivos;
        document.getElementById('empresasRegistradas').textContent = empresasRegistradas;
        document.getElementById('ticketsVendidosHoy').textContent = ticketsVendidos;
        document.getElementById('ingresosDia').textContent = ingresosDia;

        mostrarAlerta('success', 'Estadísticas actualizadas', 'Los datos se han actualizado correctamente.');

    } catch (error) {
        console.error('Error actualizando estadísticas:', error);
        mostrarAlerta('error', 'Error al actualizar', 'No se pudieron cargar las estadísticas.');
    }
}

// =========================================================================
// 🔧 FUNCIONES DE MANTENIMIENTO
// =========================================================================

function mostrarMensajeMantenimiento(mensaje) {
    // Crear banner de mantenimiento si no existe
    let banner = document.getElementById('maintenance-banner');
    if (!banner) {
        banner = document.createElement('div');
        banner.id = 'maintenance-banner';
        banner.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #ff6b1f;
            color: white;
            text-align: center;
            padding: 10px;
            z-index: 1000;
            font-weight: bold;
        `;
        document.body.insertBefore(banner, document.body.firstChild);
    }
    banner.textContent = mensaje;
    banner.style.display = 'block';
}

function ocultarMensajeMantenimiento() {
    const banner = document.getElementById('maintenance-banner');
    if (banner) {
        banner.style.display = 'none';
    }
}

// =========================================================================
// 🔙 FUNCIÓN: VOLVER AL DASHBOARD
// =========================================================================

function volverDashboard() {
    window.location.href = '../../../index.php?ruta=dashboard-admin';
}

// =========================================================================
// ℹ️ FUNCIÓN AUXILIAR: MOSTRAR ALERTAS
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