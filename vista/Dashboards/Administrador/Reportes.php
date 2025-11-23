<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportes de Ventas y Eventos</title>
  <link rel="stylesheet" href="../../../css/main.css?v=1.0">
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>

    .dashboard-container {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 30px;
      margin: 40px auto;
      width: 90%;
      max-width: 1200px;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 25px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 10px;
      overflow: hidden;
      margin-bottom: 40px;
    }

    th, td {
      padding: 12px;
      text-align: center;
      color: #fff;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    th {
      background-color: #9c4012e6;
      color: white;
      font-weight: bold;
    }

    tr:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    .btn {
      display: inline-block;
      padding: 8px 14px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-edit {
      background-color: #28a745;
      color: #fff;
      box-shadow: 0 10px 20px rgba(0, 255, 100, 0.5);
      margin-right: 5px;
    }

    .btn-edit:hover {
      background-color: #218838;
      transform: scale(1.05);
    }

    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #9c4012e6;
      color: #fff;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
      z-index: 999;
    }

    .btn-back:hover {
      transform: scale(1.05);
    }

    .loading {
      text-align: center;
      color: #fff;
      font-style: italic;
    }

    .input-precio {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      border-radius: 6px;
      color: #fff;
      padding: 5px;
      text-align: center;
      width: 90%;
    }
  </style>
</head>
<body>

  <button class="btn btn-back" onclick="volverDashboard()">⬅️ Volver a Inicio</button>

  <div class="dashboard-container">
    <h1>💰 Reportes de Ventas (Tickets)</h1>

    <table id="tabla-reportes">
      <thead>
        <tr>
          <th>Evento</th>
          <th>Cliente</th>
          <th>Precio</th>
          <th>Estado</th>
          <th>Fecha de Compra</th>
        </tr>
      </thead>
      <tbody id="tbody-reportes">
        <tr><td colspan="6" class="loading">Cargando reportes...</td></tr>
      </tbody>
    </table>

    <h1>🎭 Reporte de Eventos</h1>

    <table id="tabla-eventos">
      <thead>
        <tr>
          <th>Título</th>
          <th>Fecha</th>
          <th>Hora Inicio</th>
          <th>Hora Fin</th>
          <th>Estado</th>
          <th>Empresa</th>
          <th>Categoría</th>
        </tr>
      </thead>
      <tbody id="tbody-eventos">
        <tr><td colspan="9" class="loading">Cargando eventos...</td></tr>
      </tbody>
    </table>
  </div>

 <script>
const ApiConexion = "http://127.0.0.1:8000/api/";

let empresasMap = {};
let categoriasMap = {};

document.addEventListener('DOMContentLoaded', async () => {
  await cargarEmpresas();
  await cargarCategorias();
  await cargarReportesTickets();
  await cargarReportesEventos();
});

/* =============================
   📌 CARGAR EMPRESAS
   ============================= */
async function cargarEmpresas() {
  try {
    const res = await fetch(ApiConexion + 'listarEmpresas');
    const data = await res.json();

    empresasMap = {};

    // Backend devuelve "data": []
    (data.data || []).forEach(e => {
      empresasMap[e.id] = e.nombre_empresa;
    });

  } catch (e) {
    console.error("Error cargando empresas", e);
  }
}

/* =============================
   📌 CARGAR CATEGORÍAS
   ============================= */
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

    // =============================
    // 🎫 CARGAR TICKETS
    // =============================
    async function cargarReportesTickets() {
      const tbody = document.getElementById('tbody-reportes');
      tbody.innerHTML = '<tr><td colspan="6" class="loading">Cargando tickets...</td></tr>';

      try {
        const res = await fetch(ApiConexion + 'listarTickets');
        const tickets = await res.json();
        tbody.innerHTML = '';

        if (Array.isArray(tickets) && tickets.length > 0) {
          for (const ticket of tickets) {
            const eventoNombre = await obtenerEventoNombre(ticket.evento_id);
            const clienteNombre = await obtenerClienteNombre(ticket.cliente_id);

            tbody.innerHTML += `
              <tr>
                <td>${eventoNombre}</td>
                <td>${clienteNombre}</td>
                <td><input type="number" class="input-precio" value="${ticket.precio ?? 0}" readonly></td>
                <td>${ticket.estado ?? '—'}</td>
                <td>${ticket.fecha_compra ?? '—'}</td>
              </tr>`;
          }
        } else {
          tbody.innerHTML = '<tr><td colspan="6" class="loading">No hay tickets registrados</td></tr>';
        }
      } catch (error) {
        console.error("❌ Error cargando tickets:", error);
        tbody.innerHTML = '<tr><td colspan="6" class="loading">Error cargando tickets</td></tr>';
      }
    }

    async function obtenerEventoNombre(id) {
      try {
        const res = await fetch(`${ApiConexion}listarEventos`);
        const data = await res.json();
        const evento = data.eventos?.find(e => e.id === id);
        return evento ? evento.titulo : 'Sin evento';
      } catch {
        return 'Sin evento';
      }
    }

    async function obtenerClienteNombre(id) {
      try {
        const res = await fetch(`${ApiConexion}listarClientes`);
        const data = await res.json();
        const cliente = data.clientes?.find(c => c.id === id);
        return cliente ? `${cliente.nombre} ${cliente.apellido}` : 'Sin cliente';
      } catch {
        return 'Sin cliente';
      }
    }

/* =============================
   🎭 CARGAR EVENTOS
   ============================= */
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

        const empresaNombre = empresasMap[evento.empresa_id] || "—";
        const categoriaNombre = categoriasMap[evento.categoria_id] || "—";

        tbody.innerHTML += `
          <tr>
            <td>${evento.titulo ?? '—'}</td>
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

function volverDashboard() {
  window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
}
</script>

</body>
</html>
