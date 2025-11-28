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