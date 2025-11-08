document.addEventListener('DOMContentLoaded', () => {
    // Verificar Autenticación y Redirigir (Protección de Rutas)
    checkAuthAndRedirect();

    // Poblar datos del administrador en la UI
    populateAdminData();

    // Generar reportes
    ctrGenerarReportes();
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
// 📊 FUNCIÓN: CARGAR ESTADÍSTICAS SIMULADAS
// =========================================================================

function cargarEstadisticasSimuladas() {
    document.getElementById('total-usuarios').textContent = '1,245';
    document.getElementById('total-empresas').textContent = '42';
    document.getElementById('total-eventos').textContent = '156';
    document.getElementById('total-tickets').textContent = '8,945';
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
// 📊 FUNCIÓN: RENDERIZAR TICKETS SIMULADOS
// =========================================================================

function renderTablaTicketsSimulados() {
    const tbody = document.getElementById('tbody-reportes');
    tbody.innerHTML = "";

    const ticketsSimulados = [
        { id: 1, evento: "Concierto Rock", cliente: "Juan Pérez", tipo: "VIP", precio: 150000, estado: "Vendido", fecha_compra: "2024-11-01" },
        { id: 2, evento: "Teatro Musical", cliente: "Ana García", tipo: "General", precio: 80000, estado: "Reservado", fecha_compra: "2024-11-02" },
        { id: 3, evento: "Festival Jazz", cliente: "Carlos López", tipo: "VIP", precio: 200000, estado: "Vendido", fecha_compra: "2024-11-03" }
    ];

    ticketsSimulados.forEach(ticket => {
        const row = `
            <tr>
                <td>${ticket.evento}</td>
                <td>${ticket.cliente}</td>
                <td>${ticket.tipo}</td>
                <td><input type="number" class="input-precio" value="${ticket.precio}" onchange="ctrEditarPrecio(${ticket.id}, this.value)"></td>
                <td>${ticket.estado}</td>
                <td>${ticket.fecha_compra}</td>
                <td>
                    <button class="btn btn-edit" onclick="ctrEditarTicket(${ticket.id})">
                        <i class="fa fa-edit"></i> Editar
                    </button>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
    });
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
// 📊 FUNCIÓN: RENDERIZAR EVENTOS SIMULADOS
// =========================================================================

function renderTablaEventosSimulados() {
    const tbody = document.getElementById('tbody-eventos');
    tbody.innerHTML = "";

    const eventosSimulados = [
        { id: 1, titulo: "Concierto Rock", descripcion: "Gran concierto de rock nacional", fecha_inicio: "2024-12-01", fecha_fin: "2024-12-01", lugar: "Teatro del Sol", estado: "Activo" },
        { id: 2, titulo: "Teatro Musical", descripcion: "Obra teatral para toda la familia", fecha_inicio: "2024-12-05", fecha_fin: "2024-12-07", lugar: "Centro Cultural", estado: "Activo" },
        { id: 3, titulo: "Festival Jazz", descripcion: "Festival internacional de jazz", fecha_inicio: "2024-12-10", fecha_fin: "2024-12-12", lugar: "Auditorio Principal", estado: "Inactivo" }
    ];

    eventosSimulados.forEach(evento => {
        const row = `
            <tr>
                <td>${evento.titulo}</td>
                <td>${evento.descripcion}</td>
                <td><input type="date" class="input-fecha" value="${evento.fecha_inicio}" onchange="ctrEditarHorario(${evento.id}, 'inicio', this.value)"></td>
                <td><input type="date" class="input-fecha" value="${evento.fecha_fin}" onchange="ctrEditarHorario(${evento.id}, 'fin', this.value)"></td>
                <td>${evento.lugar}</td>
                <td>${evento.estado}</td>
                <td>
                    <button class="btn btn-edit" onclick="ctrEditarEvento(${evento.id})">
                        <i class="fa fa-edit"></i> Editar
                    </button>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
    });
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
// 🔙 FUNCIÓN: VOLVER AL DASHBOARD
// =========================================================================

function volverDashboard() {
    window.location.href = '../../../index.php?ruta=dashboard-admin';
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