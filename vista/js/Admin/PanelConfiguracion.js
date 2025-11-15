/* ==========================================================
   PanelConfiguracion.js – COMPLETO Y FUNCIONAL
   Unificado con tu configuración y tus reportes del sistema
=========================================================== */

const ApiConexion = "http://127.0.0.1:8000/api/";

let empresasMap = {};
let categoriasMap = {};
let clientesMap = {};
let eventosMap = {};

// ==========================================================
// 🚀 EJECUCIÓN INICIAL
// ==========================================================
document.addEventListener('DOMContentLoaded', async () => {

    // 👉 Verifica login
    checkAuthAndRedirect();

    // 👉 Llena los datos del admin en pantalla
    populateAdminData();

    // 👉 Cargar configuración del panel (colores, idioma, etc.)
    ctrCargarConfiguracion();

    // 👉 Aplicar configuración de apariencia
    const config = JSON.parse(localStorage.getItem('adminConfig') || '{}');
    aplicarConfiguracionVisual(config);

    // 👉 Cargar datos del sistema
    await cargarEmpresas();
    await cargarCategorias();
    await cargarClientes();
    await cargarEventos();
    await cargarReportesTickets();
    await cargarReportesEventos();
});

// ==========================================================
// 📌 CARGAR EMPRESAS
// ==========================================================
async function cargarEmpresas() {
    try {
        const res = await fetch(ApiConexion + 'listarEmpresas');
        const data = await res.json();

        empresasMap = {};
        (data.data || []).forEach(e => {
            empresasMap[e.id] = e.nombre_empresa;
        });

    } catch (e) {
        console.error("Error cargando empresas", e);
    }
}

// ==========================================================
// 📌 CARGAR CATEGORÍAS
// ==========================================================
async function cargarCategorias() {
    try {
        const res = await fetch(ApiConexion + 'listarCategorias');
        const data = await res.json();

        categoriasMap = {};
        (data.data || []).forEach(c => {
            categoriasMap[c.id] = c.nombre;
        });

    } catch (e) {
        console.error("Error cargando categorías", e);
    }
}

// ==========================================================
// 📌 CARGAR CLIENTES
// ==========================================================
async function cargarClientes() {
    try {
        const res = await fetch(ApiConexion + 'listarClientes');
        const data = await res.json();

        clientesMap = {};
        (data || []).forEach(c => {
            clientesMap[c.id] = `${c.nombre} ${c.apellido}`;
        });

    } catch (e) {
        console.error("Error cargando clientes", e);
    }
}

// ==========================================================
// 📌 CARGAR EVENTOS
// ==========================================================
async function cargarEventos() {
    try {
        const res = await fetch(ApiConexion + 'listarEventos');
        const data = await res.json();

        eventosMap = {};
        (data.eventos || []).forEach(ev => {
            eventosMap[ev.id] = ev.titulo;
        });

    } catch (e) {
        console.error("Error cargando eventos", e);
    }
}

// ==========================================================
// 🎫 CARGAR TICKETS
// ==========================================================
async function cargarReportesTickets() {
    const tbody = document.getElementById('tbody-reportes');
    tbody.innerHTML = '<tr><td colspan="6" class="loading">Cargando tickets...</td></tr>';

    try {
        const res = await fetch(ApiConexion + 'listarTickets');
        const tickets = await res.json();
        tbody.innerHTML = '';

        if (Array.isArray(tickets) && tickets.length > 0) {

            tickets.forEach(ticket => {

                const nombreEvento = eventosMap[ticket.evento_id] ?? "—";
                const nombreCliente = clientesMap[ticket.cliente_id] ?? "—";

                tbody.innerHTML += `
                  <tr>
                    <td>${nombreEvento}</td>
                    <td>${nombreCliente}</td>
                    <td><input type="number" class="input-precio" value="${ticket.precio ?? 0}" readonly></td>
                    <td>${ticket.estado ?? '—'}</td>
                    <td>${ticket.fecha_compra ?? '—'}</td>
                  </tr>`;
            });

        } else {
            tbody.innerHTML = '<tr><td colspan="6" class="loading">No hay tickets registrados</td></tr>';
        }

    } catch (error) {
        console.error("❌ Error cargando tickets:", error);
        tbody.innerHTML = '<tr><td colspan="6" class="loading">Error cargando tickets</td></tr>';
    }
}

// ==========================================================
// 🎭 CARGAR EVENTOS PARA TABLA
// ==========================================================
async function cargarReportesEventos() {
    const tbody = document.getElementById('tbody-eventos');
    tbody.innerHTML = '<tr><td colspan="9" class="loading">Cargando eventos...</td></tr>';

    try {
        const res = await fetch(ApiConexion + 'listarEventos');
        const data = await res.json();
        const eventos = Array.isArray(data.eventos) ? data.eventos : [];

        tbody.innerHTML = '';

        if (eventos.length > 0) {

            eventos.forEach(evento => {
                const empresaNombre   = empresasMap[evento.empresa_id]   || "—";
                const categoriaNombre = categoriasMap[evento.categoria_id] || "—";

                tbody.innerHTML += `
                  <tr>
                    <td>${evento.titulo ?? '—'}</td>
                    <td>${evento.descripcion ?? '—'}</td>
                    <td>${evento.fecha ?? '—'}</td>
                    <td>${evento.hora_inicio ?? '—'}</td>
                    <td>${evento.hora_final ?? '—'}</td>
                    <td>${evento.estado ?? '—'}</td>
                    <td>${empresaNombre}</td>
                    <td>${categoriaNombre}</td>
                  </tr>`;
            });

        } else {
            tbody.innerHTML = '<tr><td colspan="9" class="loading">No hay eventos registrados</td></tr>';
        }

    } catch (error) {
        console.error("❌ Error cargando eventos:", error);
        tbody.innerHTML = '<tr><td colspan="9" class="loading">Error cargando eventos</td></tr>';
    }
}

// ==========================================================
// 🔙 VOLVER AL DASHBOARD
// ==========================================================
function volverDashboard() {
    window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
}
