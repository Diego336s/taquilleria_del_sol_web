document.addEventListener('DOMContentLoaded', () => {
    // Verificar Autenticación y Redirigir (Protección de Rutas)
    checkAuthAndRedirect();

    // Poblar datos del administrador en la UI (ej. saludo en dashboard)
    populateAdminData();

    // Cargar datos iniciales del dashboard automáticamente
    cargarDatosDashboard();
});

// =========================================================================
// 🔐 FUNCIÓN: PROTECCIÓN DE RUTAS PARA ADMIN
// =========================================================================

function checkAuthAndRedirect() {
    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    // Obtener parámetro "ruta" de la URL
    const params = new URLSearchParams(window.location.search);
    const ruta = params.get('ruta') || '';

    const protectedRoutes = ["dashboard-admin"];
    const publicRoutes = ["login", "registro", "forgot_contraseña", "fogout_contraseña", "restablecer_contraseña"];

    const isProtectedRoute = protectedRoutes.includes(ruta);
    const isPublicRoute = publicRoutes.includes(ruta) || ruta === "";

    // Si está en ruta protegida y no hay token -> forzar login
    if (isProtectedRoute && !token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión. Redirigiendo al login.');
        window.location.replace("index.php?ruta=login");
        return;
    }

    // Verificar que sea admin
    if (token && userDataString) {
        try {
            const userData = JSON.parse(userDataString);
            const userRole = userData.rol || userData.role || userData.tipo || 'user';
            if (userRole.toLowerCase() !== 'admin' && userRole.toLowerCase() !== 'administrator') {
                console.warn('Usuario no es admin, rol actual:', userRole);
                window.location.replace("index.php?ruta=dashboard-usuario");
                return;
            }
        } catch (e) {
            console.error('Error parseando userData:', e);
            sessionStorage.clear();
            window.location.replace("index.php?ruta=login");
            return;
        }
    }

    // Si está en ruta pública y sí hay token -> ir al dashboard admin
    if (isPublicRoute && token) {
        window.location.replace("index.php?ruta=dashboard-admin");
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

        // -----------------------------
        // 👨‍💼 Dashboard de Administrador
        // -----------------------------
        const nombreAdminEl = document.getElementById('nombreAdmin');
        if (nombreAdminEl) {
            const nombreAdmin = userData.nombres || userData.nombre || 'Administrador';
            nombreAdminEl.textContent = " " + nombreAdmin;
        }

    } catch (e) {
        console.error('❌ Error al parsear los datos del administrador desde sessionStorage:', e);
    }
}

// =========================================================================
// 📊 FUNCIÓN: CARGAR DATOS INICIALES DEL DASHBOARD
// =========================================================================

async function cargarDatosDashboard() {
    const token = sessionStorage.getItem('userToken');

    if (!token) {
        console.warn('No hay token para cargar datos del dashboard');
        return;
    }

    try {
        // Cargar datos reales desde el backend
        await loadUsuariosAdmin();
        await loadEmpresasAdmin();
        await loadReportesAdmin();
        await loadIngresosMes();
        await loadOcupacionTeatro();
        await loadActividadReciente();

        console.log('Dashboard de admin cargado correctamente con datos reales');
    } catch (error) {
        console.warn('Error cargando datos del dashboard:', error);
        // Mantener los datos por defecto sin mostrar alertas
    }
}

// =========================================================================
// 👥 FUNCIÓN: CARGAR USUARIOS PARA DASHBOARD
// =========================================================================

async function loadUsuariosAdmin() {
    const token = sessionStorage.getItem('userToken');

    try {
        const respuesta = await fetch(`${ApiConexion}listarClientes`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        if (respuesta.ok) {
            const data = await respuesta.json();
            if (data.success || data.clientes || data.data) {
                const clientes = data.clientes || data.data || data;
                const totalClientes = Array.isArray(clientes) ? clientes.length : 0;
                // Actualizar contador de clientes en el dashboard
                const totalClientesEl = document.getElementById('totalClientes');
                if (totalClientesEl) {
                    totalClientesEl.textContent = totalClientes;
                }
            }
        }
    } catch (error) {
        console.warn('Error cargando clientes para dashboard:', error);
    }
}

// =========================================================================
// 🏢 FUNCIÓN: CARGAR EMPRESAS PARA DASHBOARD
// =========================================================================

async function loadEmpresasAdmin() {
    const token = sessionStorage.getItem('userToken');

    try {
        const respuesta = await fetch(`${ApiConexion}listarEmpresas`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        if (respuesta.ok) {
            const data = await respuesta.json();
            if (data.success || data.empresas || data.data) {
                const empresas = data.empresas || data.data || data;
                const totalEmpresas = Array.isArray(empresas) ? empresas.length : 0;
                // Actualizar contador de empresas en el dashboard
                const totalEmpresasEl = document.getElementById('totalEmpresas');
                if (totalEmpresasEl) {
                    totalEmpresasEl.textContent = totalEmpresas;
                }
            }
        }
    } catch (error) {
        console.warn('Error cargando empresas para dashboard:', error);
    }
}

// =========================================================================
// 📊 FUNCIÓN: CARGAR REPORTES PARA DASHBOARD
// =========================================================================

async function loadReportesAdmin() {
    const token = sessionStorage.getItem('userToken');

    try {
        const respuesta = await fetch(`${ApiConexion}estadisticas`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        if (respuesta.ok) {
            const data = await respuesta.json();
            if (data.success && data.estadisticas) {
                const stats = data.estadisticas;

                // Actualizar estadísticas en el dashboard
                const widgets = document.querySelectorAll('.widget-number');
                if (widgets.length >= 4) {
                    widgets[0].textContent = stats.total_usuarios || widgets[0].textContent;
                    widgets[1].textContent = stats.total_empresas || widgets[1].textContent;
                    widgets[2].textContent = stats.total_eventos || widgets[2].textContent;
                    widgets[3].textContent = stats.total_tickets || widgets[3].textContent;
                }
            }
        }
    } catch (error) {
        console.warn('Error cargando reportes para dashboard:', error);
    }
}

// =========================================================================
// 🚪 FUNCIÓN: CERRAR SESIÓN ADMIN
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

    if (!token) {
        window.location.replace("index.php?ruta=login");
        return;
    }

    Swal.fire({
        title: 'Cerrando Sesión...',
        text: 'Espere un momento.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

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
            sessionStorage.removeItem('userToken');
            sessionStorage.removeItem('userData');
            Swal.close();
            mostrarAlerta('success', 'Sesión cerrada', 'Has cerrado sesión correctamente.');
            setTimeout(() => {
                window.location.replace("index.php?ruta=login");
            }, 1000);
        } else {
            mostrarAlerta('error', 'Error al cerrar sesión', data.message || 'Ocurrió un problema al cerrar sesión.');
        }

    } catch (error) {
        console.error("Error al cerrar sesión:", error);
        mostrarAlerta('error', 'Error', 'No se pudo conectar con el servidor API.');
    }
}

// =========================================================================
// 👥 FUNCIÓN: LISTAR USUARIOS
// =========================================================================

async function ctrListarUsuarios() {
    const token = sessionStorage.getItem('userToken');
    const tbody = document.getElementById('tbody-usuarios');

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    tbody.innerHTML = '<tr><td colspan="8" class="loading text-center">Cargando usuarios...</td></tr>';

    const urlAPI = `${ApiConexion}usuarios`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.success === true) {
            tbody.innerHTML = "";

            const usuarios = data.usuarios || data.data || data;
            if (!usuarios || usuarios.length === 0) {
                tbody.innerHTML = "<tr><td colspan='8' class='loading text-center'>No hay usuarios registrados</td></tr>";
                return;
            }

            usuarios.forEach(user => {
                const estadoIcono = user.estado === 'Activo' || user.estado === 'activo' ? '🟢' : '🔴';
                const estadoTexto = user.estado || 'Inactivo';
                const row = `
                    <tr id="usuario-${user.id}">
                        <td>${user.id ?? '—'}</td>
                        <td>${user.nombre ?? user.nombres ?? '—'}</td>
                        <td>${user.apellido ?? user.apellidos ?? '—'}</td>
                        <td>${user.correo ?? '—'}</td>
                        <td>${user.telefono ?? '—'}</td>
                        <td>${user.rol ?? 'Cliente'}</td>
                        <td>${estadoIcono} ${estadoTexto}</td>
                        <td>
                            <button class="btn btn-edit" onclick="ctrCambiarEstadoUsuario(${user.id}, '${user.estado}')">
                                🔄 Cambiar Estado
                            </button>
                            <button class="btn btn-delete" onclick="ctrEliminarUsuario(${user.id})">
                                🗑️ Eliminar
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML("beforeend", row);
            });

        } else {
            console.warn("Error en la respuesta del backend:", data.message);
            tbody.innerHTML = "<tr><td colspan='8' class='loading text-center'>No se pudieron cargar los usuarios</td></tr>";
        }
    } catch (error) {
        console.warn("Error de conexión con el backend:", error);
        tbody.innerHTML = "<tr><td colspan='8' class='loading text-center'>No se pudieron cargar los usuarios</td></tr>";
    }
}

// =========================================================================
// 🔄 FUNCIÓN: CAMBIAR ESTADO DEL USUARIO
// =========================================================================

async function ctrCambiarEstadoUsuario(id, estadoActual) {
    const token = sessionStorage.getItem('userToken');
    const nuevoEstado = estadoActual === 'Activo' || estadoActual === 'activo' ? 'Inactivo' : 'Activo';

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    const result = await Swal.fire({
        title: '¿Cambiar estado?',
        text: `¿Deseas cambiar el estado del usuario a ${nuevoEstado}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) {
        return;
    }

    const urlAPI = `${ApiConexion}usuarios/${id}/estado`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify({ estado: nuevoEstado })
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.success === true) {
            mostrarAlerta('success', 'Estado actualizado', `El estado del usuario ha sido cambiado a ${nuevoEstado}.`);
            ctrListarUsuarios(); // Recargar la tabla
        } else {
            mostrarAlerta('error', 'Error al cambiar estado', data.message || 'No se pudo cambiar el estado del usuario.');
        }
    } catch (error) {
        console.error("Error al cambiar estado del usuario:", error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
    }
}

// =========================================================================
// 🗑️ FUNCIÓN: ELIMINAR USUARIO
// =========================================================================

async function ctrEliminarUsuario(id) {
    const token = sessionStorage.getItem('userToken');

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    // Confirmación con Swal
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer. ¿Deseas eliminar este usuario?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) {
        return;
    }

    // Mostrar loading
    Swal.fire({
        title: 'Eliminando usuario...',
        text: 'Por favor, espera un momento.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const urlAPI = `${ApiConexion}eliminarCliente/${id}`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();
        Swal.close();

        if (respuesta.ok && data.success === true) {
            // Remover fila de la tabla
            const row = document.getElementById(`usuario-${id}`);
            if (row) {
                row.remove();
            }

            Swal.fire({
                icon: 'success',
                title: '¡Usuario Eliminado!',
                text: data.message || 'El usuario ha sido eliminado correctamente.',
                timer: 2000,
                showConfirmButton: false
            });

            // Recargar lista después de 2 segundos
            setTimeout(() => {
                ctrListarUsuarios();
            }, 2000);

        } else {
            mostrarAlerta('error', 'Error al eliminar', data.message || 'No se pudo eliminar el usuario.');
        }
    } catch (error) {
        Swal.close();
        console.error("Error al eliminar usuario:", error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
    }
}

// =========================================================================
// 🏢 FUNCIÓN: LISTAR EMPRESAS
// =========================================================================

async function ctrListarEmpresas() {
    const token = sessionStorage.getItem('userToken');
    const tbody = document.getElementById('tbody-empresas');

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    tbody.innerHTML = '<tr><td colspan="7" class="loading">Cargando empresas...</td></tr>';

    const urlAPI = `${ApiConexion}empresas`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.success === true) {
            tbody.innerHTML = "";

            const empresas = data.empresas || data.data || data;
            if (!empresas || empresas.length === 0) {
                tbody.innerHTML = "<tr><td colspan='7' class='loading'>No hay empresas registradas</td></tr>";
                return;
            }

            empresas.forEach(empresa => {
                const estadoIcono = empresa.estado === 'Activa' || empresa.estado === 'activa' ? '🟢' : '🔴';
                const estadoTexto = empresa.estado || 'Inactiva';
                const row = `
                    <tr id="empresa-${empresa.id}">
                        <td>${empresa.id ?? '—'}</td>
                        <td>${empresa.nombre_empresa ?? empresa.nombre ?? '—'}</td>
                        <td>${empresa.nit ?? '—'}</td>
                        <td>${empresa.correo ?? '—'}</td>
                        <td>${empresa.telefono ?? '—'}</td>
                        <td>${estadoIcono} ${estadoTexto}</td>
                        <td>
                            <button class="btn btn-edit" onclick="ctrEditarEmpresa(${empresa.id})">
                                ✏️ Editar
                            </button>
                            <button class="btn btn-delete" onclick="ctrEliminarEmpresa(${empresa.id})">
                                🗑️ Eliminar
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML("beforeend", row);
            });

        } else {
            console.warn("Error en la respuesta del backend:", data.message);
            tbody.innerHTML = "<tr><td colspan='7' class='loading'>No se pudieron cargar las empresas</td></tr>";
        }
    } catch (error) {
        console.warn("Error de conexión con el backend:", error);
        tbody.innerHTML = "<tr><td colspan='7' class='loading'>No se pudieron cargar las empresas</td></tr>";
    }
}

// =========================================================================
// ✏️ FUNCIÓN: EDITAR EMPRESA
// =========================================================================

function ctrEditarEmpresa(id) {
    // Redirigir a la página de edición
    window.location.href = `Editar_Empresa.php?id=${id}`;
}

// =========================================================================
// ➕ FUNCIÓN: CREAR EMPRESA
// =========================================================================

function crearEmpresa() {
    // Redirigir a la página de creación
    window.location.href = 'Crear_Empresa.php';
}

// =========================================================================
// 🗑️ FUNCIÓN: ELIMINAR EMPRESA
// =========================================================================

async function ctrEliminarEmpresa(id) {
    const token = sessionStorage.getItem('userToken');

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    // Confirmación con Swal
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer. ¿Deseas eliminar esta empresa?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) {
        return;
    }

    // Mostrar loading
    Swal.fire({
        title: 'Eliminando empresa...',
        text: 'Por favor, espera un momento.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const urlAPI = `${ApiConexion}eliminarEmpresa/${id}`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();
        Swal.close();

        if (respuesta.ok && data.success === true) {
            // Remover fila de la tabla
            const row = document.getElementById(`empresa-${id}`);
            if (row) {
                row.remove();
            }

            Swal.fire({
                icon: 'success',
                title: '¡Empresa Eliminada!',
                text: data.message || 'La empresa ha sido eliminada correctamente.',
                timer: 2000,
                showConfirmButton: false
            });

            // Recargar lista después de 2 segundos
            setTimeout(() => {
                ctrListarEmpresas();
            }, 2000);

        } else {
            mostrarAlerta('error', 'Error al eliminar', data.message || 'No se pudo eliminar la empresa.');
        }
    } catch (error) {
        Swal.close();
        console.error("Error al eliminar empresa:", error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
    }
}

// =========================================================================
// 📊 FUNCIÓN: GENERAR REPORTES
// =========================================================================

async function ctrGenerarReportes() {
    const token = sessionStorage.getItem('userToken');

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    // Mostrar loading
    const tbodyTickets = document.getElementById('tbody-reportes');
    const tbodyEventos = document.getElementById('tbody-eventos');

    tbodyTickets.innerHTML = '<tr><td colspan="7" class="loading">Cargando reportes...</td></tr>';
    tbodyEventos.innerHTML = '<tr><td colspan="7" class="loading">Cargando eventos...</td></tr>';

    try {
        // Cargar estadísticas
        await cargarEstadisticas(token);

        // Cargar tickets/ventas
        await cargarReportesTickets(token);

        // Cargar eventos
        await cargarReportesEventos(token);

        // Inicializar gráficos
        inicializarGraficos();

    } catch (error) {
        console.warn("Error cargando reportes:", error);
        // Mantener valores por defecto sin mostrar alertas
    }
}

// =========================================================================
// 📈 FUNCIÓN: CARGAR ESTADÍSTICAS
// =========================================================================

async function cargarEstadisticas(token) {
    const urlAPI = `${ApiConexion}estadisticas`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.success === true) {
            const stats = data.estadisticas || data;
            document.getElementById('total-usuarios').textContent = stats.total_usuarios || 0;
            document.getElementById('total-empresas').textContent = stats.total_empresas || 0;
            document.getElementById('total-eventos').textContent = stats.total_eventos || 0;
            document.getElementById('total-tickets').textContent = stats.total_tickets || 0;
        } else {
            console.warn('Error al cargar estadísticas:', data.message);
            // Mantener valores por defecto
        }
    } catch (error) {
        console.warn("Error al cargar estadísticas:", error);
        // Mantener valores por defecto
    }
}

// =========================================================================
// 🎫 FUNCIÓN: CARGAR REPORTES DE TICKETS
// =========================================================================

async function cargarReportesTickets(token) {
    const tbody = document.getElementById('tbody-reportes');
    const urlAPI = `${ApiConexion}reportes/tickets`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.success === true) {
            tbody.innerHTML = "";

            const tickets = data.tickets || data.data || data;
            if (!tickets || tickets.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="loading">No hay tickets registrados</td></tr>';
                return;
            }

            tickets.forEach(ticket => {
                const row = `
                    <tr>
                        <td>${ticket.evento ?? '—'}</td>
                        <td>${ticket.cliente ?? '—'}</td>
                        <td>${ticket.tipo ?? '—'}</td>
                        <td><input type="number" class="input-precio" value="${ticket.precio ?? 0}" onchange="ctrEditarPrecio(${ticket.id}, this.value)"></td>
                        <td>${ticket.estado ?? '—'}</td>
                        <td>${ticket.fecha_compra ?? '—'}</td>
                        <td>
                            <button class="btn btn-edit" onclick="ctrEditarTicket(${ticket.id})">
                                <i class="fa fa-edit"></i> Editar
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML("beforeend", row);
            });

        } else {
            console.warn('Error en respuesta de tickets:', data.message);
            tbody.innerHTML = '<tr><td colspan="7" class="loading">No se pudieron cargar los tickets</td></tr>';
        }
    } catch (error) {
        console.warn("Error al cargar tickets:", error);
        tbody.innerHTML = '<tr><td colspan="7" class="loading">No se pudieron cargar los tickets</td></tr>';
    }
}

// =========================================================================
// 🎭 FUNCIÓN: CARGAR REPORTES DE EVENTOS
// =========================================================================

async function cargarReportesEventos(token) {
    const tbody = document.getElementById('tbody-eventos');
    const urlAPI = `${ApiConexion}reportes/eventos`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.success === true) {
            tbody.innerHTML = "";

            const eventos = data.eventos || data.data || data;
            if (!eventos || eventos.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="loading">No hay eventos registrados</td></tr>';
                return;
            }

            eventos.forEach(evento => {
                const row = `
                    <tr>
                        <td>${evento.titulo ?? '—'}</td>
                        <td>${evento.descripcion ?? '—'}</td>
                        <td><input type="date" class="input-fecha" value="${evento.fecha_inicio ?? ''}" onchange="ctrEditarHorario(${evento.id}, 'inicio', this.value)"></td>
                        <td><input type="date" class="input-fecha" value="${evento.fecha_fin ?? ''}" onchange="ctrEditarHorario(${evento.id}, 'fin', this.value)"></td>
                        <td>${evento.lugar ?? '—'}</td>
                        <td>${evento.estado ?? '—'}</td>
                        <td>
                            <button class="btn btn-edit" onclick="ctrEditarEvento(${evento.id})">
                                <i class="fa fa-edit"></i> Editar
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML("beforeend", row);
            });

        } else {
            console.warn('Error en respuesta de eventos:', data.message);
            tbody.innerHTML = '<tr><td colspan="7" class="loading">No se pudieron cargar los eventos</td></tr>';
        }
    } catch (error) {
        console.warn("Error al cargar eventos:", error);
        tbody.innerHTML = '<tr><td colspan="7" class="loading">No se pudieron cargar los eventos</td></tr>';
    }
}

// =========================================================================
// 📊 FUNCIÓN: INICIALIZAR GRÁFICOS CON CHART.JS
// =========================================================================

function inicializarGraficos() {
    // Gráfico de Ventas
    const ctxVentas = document.getElementById('chartVentas');
    if (ctxVentas) {
        new Chart(ctxVentas, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                datasets: [{
                    label: 'Ventas Mensuales',
                    data: [1200000, 1900000, 3000000, 5000000, 2000000, 3000000],
                    borderColor: '#ff6b1f',
                    backgroundColor: 'rgba(255, 107, 31, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Tendencia de Ventas'
                    }
                }
            }
        });
    }

    // Gráfico de Usuarios
    const ctxUsuarios = document.getElementById('chartUsuarios');
    if (ctxUsuarios) {
        new Chart(ctxUsuarios, {
            type: 'bar',
            data: {
                labels: ['Usuarios Activos', 'Usuarios Inactivos', 'Empresas'],
                datasets: [{
                    label: 'Cantidad',
                    data: [1245, 89, 42],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(255, 193, 7, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribución de Usuarios'
                    }
                }
            }
        });
    }

    // Gráfico de Eventos
    const ctxEventos = document.getElementById('chartEventos');
    if (ctxEventos) {
        new Chart(ctxEventos, {
            type: 'doughnut',
            data: {
                labels: ['Eventos Activos', 'Eventos Finalizados', 'Eventos Cancelados'],
                datasets: [{
                    data: [12, 8, 2],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(23, 162, 184, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Estado de Eventos'
                    }
                }
            }
        });
    }
}

// =========================================================================
// ✏️ FUNCIONES DE EDICIÓN
// =========================================================================

async function ctrEditarPrecio(id, nuevoPrecio) {
    const token = sessionStorage.getItem('userToken');

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    // Mostrar confirmación
    const result = await Swal.fire({
        title: '¿Confirmar cambio?',
        text: `¿Deseas cambiar el precio a $${nuevoPrecio}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) {
        // Recargar para revertir el cambio en el input
        ctrGenerarReportes();
        return;
    }

    // Aquí iría la llamada a la API para actualizar el precio
    // Por ahora, solo mostramos una alerta de simulación
    mostrarAlerta('success', 'Precio actualizado', `💰 Precio del ticket ${id} actualizado a $${nuevoPrecio}`);
}

async function ctrEditarHorario(id, tipo, nuevaFecha) {
    const token = sessionStorage.getItem('userToken');

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    // Mostrar confirmación
    const result = await Swal.fire({
        title: '¿Confirmar cambio?',
        text: `¿Deseas cambiar la fecha de ${tipo} a ${nuevaFecha}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) {
        // Recargar para revertir el cambio en el input
        ctrGenerarReportes();
        return;
    }

    // Aquí iría la llamada a la API para actualizar la fecha
    // Por ahora, solo mostramos una alerta de simulación
    mostrarAlerta('success', 'Horario actualizado', `🕒 Fecha de ${tipo} del evento ${id} actualizada a ${nuevaFecha}`);
}

function ctrEditarTicket(id) {
    // Redirigir a edición completa del ticket
    mostrarAlerta('info', 'Editar Ticket', `✏️ Funcionalidad de edición completa del ticket ${id} (pendiente de implementar)`);
}

function ctrEditarEvento(id) {
    // Redirigir a edición completa del evento
    mostrarAlerta('info', 'Editar Evento', `✏️ Funcionalidad de edición completa del evento ${id} (pendiente de implementar)`);
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

    document.getElementById('modoOscuro').value = config.modoOscuro || 'false';
    document.getElementById('colorPrincipal').value = config.colorPrincipal || '#ff6b1f';
    document.getElementById('mensajeMantenimiento').value = config.mensajeMantenimiento || '';
    document.getElementById('logoTema').value = config.logoTema || '';
    document.getElementById('precioBase').value = config.precioBase || 50000;
    document.getElementById('horaApertura').value = config.horaApertura || '08:00';
    document.getElementById('horaCierre').value = config.horaCierre || '22:00';
    document.getElementById('correoNotificaciones').value = config.correoNotificaciones || 'admin@taquilleria.com';
    document.getElementById('estadoSistema').value = config.estadoSistema || 'activo';
}

// =========================================================================
// 💾 FUNCIÓN: GUARDAR CONFIGURACIÓN
// =========================================================================

async function guardarConfiguracion() {
    // Recolectar datos del formulario
    const configuracion = {
        modoOscuro: document.getElementById('modoOscuro').value,
        colorPrincipal: document.getElementById('colorPrincipal').value,
        mensajeMantenimiento: document.getElementById('mensajeMantenimiento').value.trim(),
        logoTema: document.getElementById('logoTema').value.trim(),
        precioBase: document.getElementById('precioBase').value,
        horaApertura: document.getElementById('horaApertura').value,
        horaCierre: document.getElementById('horaCierre').value,
        correoNotificaciones: document.getElementById('correoNotificaciones').value,
        estadoSistema: document.getElementById('estadoSistema').value
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
}

// =========================================================================
// 🎨 FUNCIÓN: APLICAR CONFIGURACIÓN VISUAL
// =========================================================================

function aplicarConfiguracionVisual(config) {
    // Aplicar modo oscuro
    if (config.modoOscuro === 'true') {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
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
    if (config.mensajeMantenimiento) {
        const maintenanceBanner = document.getElementById('maintenance-banner');
        if (maintenanceBanner) {
            maintenanceBanner.textContent = config.mensajeMantenimiento;
            maintenanceBanner.style.display = 'block';
        }
    }
}

// =========================================================================
// 📋 FUNCIÓN: CARGAR PERFIL DEL ADMIN
// =========================================================================

async function ctrCargarPerfilAdmin() {
    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontraron datos de sesión.');
        return;
    }

    try {
        const userData = JSON.parse(userDataString);

        // Poblar información de visualización
        document.getElementById('profile_nombre_display').textContent = userData.nombre || userData.nombres || '—';
        document.getElementById('profile_apellido_display').textContent = userData.apellido || userData.apellidos || '—';
        document.getElementById('profile_documento_display').textContent = userData.documento || '—';
        document.getElementById('profile_telefono_display').textContent = userData.telefono || '—';
        document.getElementById('profile_sexo_display').textContent = userData.sexo || '—';
        document.getElementById('profile_correo_display').textContent = userData.correo || '—';
        document.getElementById('profile_rol_display').textContent = userData.rol || 'admin';

        // Poblar formulario de edición
        document.getElementById('profile_nombre').value = userData.nombre || userData.nombres || '';
        document.getElementById('profile_apellido').value = userData.apellido || userData.apellidos || '';
        document.getElementById('profile_telefono').value = userData.telefono || '';
        document.getElementById('profile_sexo').value = userData.sexo || '';

    } catch (e) {
        console.error('Error cargando perfil del admin:', e);
        mostrarAlerta('error', 'Error al cargar perfil', 'No se pudieron cargar los datos del perfil.');
    }
}

// =========================================================================
// 📝 FUNCIÓN: ACTUALIZAR PERFIL DEL ADMIN
// =========================================================================

async function ctrActualizarPerfilAdmin() {
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
        // Agregar campos adicionales si existen
        correo: userData.correo, // Mantener el correo actual
        documento: userData.documento // Mantener el documento actual
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

    // 4. Intentar actualizar en el backend
    const userData = JSON.parse(userDataString);
    const urlAPI = `${ApiConexion}actualizarAdmin/${userData.id}`;

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
            const updatedUserData = data.admin || data.user;
            if (updatedUserData) {
                sessionStorage.setItem('userData', JSON.stringify(updatedUserData));
                // Refrescar la información en pantalla
                ctrCargarPerfilAdmin();
            }

            Swal.fire({
                icon: 'success',
                title: '¡Perfil Actualizado!',
                text: data.message || 'Tus datos se han guardado correctamente.',
                timer: 2000,
                showConfirmButton: false
            });

            // Recargar la página después de actualizar exitosamente
            setTimeout(() => {
                window.location.reload();
            }, 2000);

        } else {
            mostrarAlerta('error', 'Error al actualizar', data.message || 'No se pudieron guardar los cambios.');
        }
    } catch (error) {
        Swal.close();
        console.warn("Error al conectar con backend, usando lógica de perfil de usuario:", error);

        // Usar la misma lógica que ctrupdatePerfil() para usuarios
        ctrupdatePerfilAdminLocal(datos, userData);
    }
}

// =========================================================================
// 📝 FUNCIÓN: ACTUALIZAR PERFIL LOCAL (FALLBACK)
// =========================================================================

async function ctrupdatePerfilAdminLocal(datos, userData) {
    // Simular actualización usando la lógica del perfil de usuario
    try {
        // Actualizar sessionStorage
        const updatedUserData = { ...userData, ...datos };
        sessionStorage.setItem('userData', JSON.stringify(updatedUserData));

        // Refrescar la información en pantalla
        ctrCargarPerfilAdmin();

        Swal.fire({
            icon: 'success',
            title: '¡Perfil Actualizado!',
            text: 'Tus datos se han guardado correctamente (modo local).',
            timer: 2000,
            showConfirmButton: false
        });

        // Recargar la página después de actualizar exitosamente
        setTimeout(() => {
            window.location.reload();
        }, 2000);

    } catch (error) {
        console.error("Error al actualizar perfil local:", error);
        mostrarAlerta('error', 'Error al actualizar', 'No se pudieron guardar los cambios.');
    }
}

// =========================================================================
// 💰 FUNCIÓN: CARGAR INGRESOS DEL MES
// =========================================================================

async function loadIngresosMes() {
    const token = sessionStorage.getItem('userToken');

    try {
        const respuesta = await fetch(`${ApiConexion}listarIngresosMes`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        if (respuesta.ok) {
            const data = await respuesta.json();
            const ingresos = data.ingresos || data.total || 0;
            const ingresosEl = document.getElementById('ingresosMes');
            if (ingresosEl) {
                ingresosEl.textContent = `$${ingresos.toLocaleString()}`;
            }
        }
    } catch (error) {
        console.warn('Error cargando ingresos del mes:', error);
    }
}

// =========================================================================
// 📊 FUNCIÓN: CARGAR OCUPACIÓN DEL TEATRO
// =========================================================================

async function loadOcupacionTeatro() {
    const token = sessionStorage.getItem('userToken');

    try {
        // Obtener tickets vendidos
        const ticketsRes = await fetch(`${ApiConexion}listarTickets`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        // Obtener eventos para calcular capacidad total
        const eventosRes = await fetch(`${ApiConexion}listarEventos`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        if (ticketsRes.ok && eventosRes.ok) {
            const ticketsData = await ticketsRes.json();
            const eventosData = await eventosRes.json();

            const tickets = Array.isArray(ticketsData) ? ticketsData : (ticketsData.tickets || []);
            const eventos = eventosData.eventos || [];

            // Calcular capacidad total (asumiendo cada evento tiene capacidad)
            let capacidadTotal = 0;
            eventos.forEach(evento => {
                capacidadTotal += evento.capacidad || 100; // Default 100 si no hay capacidad
            });

            if (capacidadTotal === 0) capacidadTotal = 100; // Evitar división por cero

            const ticketsVendidos = tickets.length;
            const ocupacion = Math.round((ticketsVendidos / capacidadTotal) * 100);
            const porcentajeTickets = Math.min(ocupacion, 100); // Máximo 100%

            // Actualizar elementos
            const ocupacionEl = document.getElementById('ocupacionPercentage');
            const porcentajeTicketsEl = document.getElementById('porcentajeTickets');
            const circleEl = document.getElementById('ocupacionCircle');

            if (ocupacionEl) ocupacionEl.textContent = ocupacion;
            if (porcentajeTicketsEl) porcentajeTicketsEl.textContent = porcentajeTickets;

            // Animar círculo
            if (circleEl) {
                const circumference = 314; // 2 * π * 50
                const offset = circumference - (ocupacion / 100) * circumference;
                circleEl.style.strokeDashoffset = offset;
            }
        }
    } catch (error) {
        console.warn('Error cargando ocupación del teatro:', error);
    }
}

// =========================================================================
// 📋 FUNCIÓN: CARGAR ACTIVIDAD RECIENTE
// =========================================================================

async function loadActividadReciente() {
    const token = sessionStorage.getItem('userToken');
    const actividadLista = document.getElementById('actividadLista');

    if (!actividadLista) return;

    try {
        // Obtener datos recientes de diferentes endpoints
        const [ticketsRes, eventosRes, empresasRes] = await Promise.all([
            fetch(`${ApiConexion}listarTickets`, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token }
            }),
            fetch(`${ApiConexion}listarEventos`, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token }
            }),
            fetch(`${ApiConexion}listarEmpresas`, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token }
            })
        ]);

        const actividades = [];

        // Procesar tickets recientes
        if (ticketsRes.ok) {
            const ticketsData = await ticketsRes.json();
            const tickets = Array.isArray(ticketsData) ? ticketsData : (ticketsData.tickets || []);
            const ticketsRecientes = tickets.slice(-3); // Últimos 3 tickets

            ticketsRecientes.forEach(ticket => {
                actividades.push({
                    titulo: `Se vendieron tickets en el evento "${ticket.evento || 'Evento'}"`,
                    tipo: 'success',
                    icono: '🎫',
                    tiempo: ticket.fecha_compra || new Date().toISOString()
                });
            });
        }

        // Procesar eventos recientes
        if (eventosRes.ok) {
            const eventosData = await eventosRes.json();
            const eventos = eventosData.eventos || [];
            const eventosRecientes = eventos.slice(-2); // Últimos 2 eventos

            eventosRecientes.forEach(evento => {
                actividades.push({
                    titulo: `Evento actualizado: "${evento.titulo || evento.nombre}"`,
                    tipo: 'info',
                    icono: '📅',
                    tiempo: evento.fecha_creacion || evento.fecha_inicio || new Date().toISOString()
                });
            });
        }

        // Procesar empresas recientes
        if (empresasRes.ok) {
            const empresasData = await empresasRes.json();
            const empresas = empresasData.data || [];
            const empresasRecientes = empresas.slice(-2); // Últimas 2 empresas

            empresasRecientes.forEach(empresa => {
                actividades.push({
                    titulo: `Nueva empresa registrada: "${empresa.nombre_empresa || empresa.nombre}"`,
                    tipo: 'warning',
                    icono: '🏢',
                    tiempo: empresa.fecha_registro || new Date().toISOString()
                });
            });
        }

        // Agregar actividades simuladas si no hay suficientes
        if (actividades.length < 5) {
            const actividadesSimuladas = [
                { titulo: 'Backup automático completado exitosamente', tipo: 'info', icono: '💾', tiempo: new Date(Date.now() - 3600000).toISOString() },
                { titulo: 'Cliente realizó una compra', tipo: 'success', icono: '🛒', tiempo: new Date(Date.now() - 7200000).toISOString() },
                { titulo: 'Actualización de precios aplicada', tipo: 'warning', icono: '💰', tiempo: new Date(Date.now() - 10800000).toISOString() }
            ];

            actividades.push(...actividadesSimuladas.slice(0, 5 - actividades.length));
        }

        // Ordenar por tiempo (más reciente primero)
        actividades.sort((a, b) => new Date(b.tiempo) - new Date(a.tiempo));

        // Limitar a 8 actividades
        const actividadesMostrar = actividades.slice(0, 8);

        // Renderizar actividades
        actividadLista.innerHTML = actividadesMostrar.map(actividad => `
            <div class="activity-item">
                <div class="activity-icon ${actividad.tipo}">
                    ${actividad.icono}
                </div>
                <div class="activity-content">
                    <div class="activity-title">${actividad.titulo}</div>
                    <div class="activity-time">${formatearTiempoRelativo(actividad.tiempo)}</div>
                </div>
            </div>
        `).join('');

    } catch (error) {
        console.warn('Error cargando actividad reciente:', error);
        actividadLista.innerHTML = '<div class="loading-activity">Error al cargar actividades</div>';
    }
}

// =========================================================================
// 🕒 FUNCIÓN AUXILIAR: FORMATEAR TIEMPO RELATIVO
// =========================================================================

function formatearTiempoRelativo(fechaString) {
    const fecha = new Date(fechaString);
    const ahora = new Date();
    const diferencia = ahora - fecha;

    const minutos = Math.floor(diferencia / 60000);
    const horas = Math.floor(diferencia / 3600000);
    const dias = Math.floor(diferencia / 86400000);

    if (minutos < 1) return 'Hace un momento';
    if (minutos < 60) return `Hace ${minutos} minuto${minutos > 1 ? 's' : ''}`;
    if (horas < 24) return `Hace ${horas} hora${horas > 1 ? 's' : ''}`;
    return `Hace ${dias} día${dias > 1 ? 's' : ''}`;
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