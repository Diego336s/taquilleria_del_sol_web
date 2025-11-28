<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <meta charset="UTF-8">
  <title>Reportes de Ventas y Eventos</title>
  <link rel="stylesheet" href="../../../css/main.css?v=1.0">
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    /* === CORTINA OSCURA GLOBAL === */
    body {
      position: relative;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
    }

    /* Asegurar que el contenedor quede encima */
    .dashboard-container {
      position: relative;
      z-index: 999;
    }

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
    }

    th,
    td {
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
      margin-right: 5px;
    }

    .btn-edit:hover {
      background-color: #218838;
      transform: scale(1.05);
    }

    .btn-back {
      top: 25px;
      left: 25px;
      background-color: #9c4012e6;
      color: #fff;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
      z-index: 999;
      
    }

    .controles-tabla {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
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

    /* Buscador elegante */
    .search-box {
      position: relative;
      width: 220px;
      margin-bottom: 10px;
      float: right;
    }

    .search-box input {
      width: 100%;
      padding: 8px 35px 8px 12px;
      border-radius: 20px;
      border: none;
      outline: none;
      font-size: 14px;
      background-color: rgba(255, 255, 255, 0.2);
      color: #fff;
      transition: all 0.3s ease;
    }

    .search-box input::placeholder {
      color: #ffd27f;
    }

    .search-box i {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #ffd27f;
    }

    /* Pagination */
    .pagination {
      display: flex;
      gap: 8px;
      justify-content: flex-end;
      align-items: center;
      margin: 10px 0 25px;
      flex-wrap: wrap;
    }

    .pagination button {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 6px 10px;
      border-radius: 8px;
      cursor: pointer;
      transition: all .2s ease;
      min-width: 34px;
    }

    .pagination button:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-1px);
    }

    .pagination button.active {
      background: #9c4012e6;
      border-color: #ff9f66;
      box-shadow: 0 6px 14px rgba(255, 107, 31, 0.35);
    }

    .pagination .info {
      color: #ffd27f;
      margin-right: auto;
      font-size: 0.9em;
    }

    /* Contenedor para tablas responsivas */
    .table-responsive {
      overflow-x: auto;
      margin-bottom: 40px;
      border-radius: 10px; /* Para que el scroll no oculte los bordes redondeados de la tabla */
    }

    /* Ocultar scrollbar en webkit pero mantener funcionalidad */
    .table-responsive::-webkit-scrollbar {
      height: 8px;
    }
  </style>
</head>

<body>


  <div class="dashboard-container">
    <h1>💰 Reportes de Ventas (Tickets)</h1>
    <div class="controles-tabla">
      <button class="btn btn-back" onclick="volverDashboard()">⬅️ Volver a Inicio</button>
      <!-- Buscador tickets -->
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="buscador-tickets" placeholder="Buscar tickets...">
      </div>
    </div>
    
    <div class="table-responsive">
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
          <tr>
            <td colspan="6" class="loading">Cargando reportes...</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="paginacion-tickets" class="pagination"></div>

    <h1>🎭 Reporte de Eventos</h1>

    <!-- Controles de la tabla de eventos -->
    <div class="controles-tabla">
      <button class="btn btn-back" onclick="volverDashboard()">⬅️ Volver a Inicio</button>
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="buscador-eventos" placeholder="Buscar eventos...">
      </div>
    </div>

    <div class="table-responsive">
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
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tbody-eventos">
          <tr>
            <td colspan="9" class="loading">Cargando eventos...</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    const ApiConexion = "http://127.0.0.1:8000/api/";

    let empresasMap = {};
    let categoriasMap = {};

    // Pagination state for tickets
    let ticketsData = [];
    let filteredTickets = [];
    let currentPageTickets = 1;
    const pageSizeTickets = 10;

    // Maps to resolve names once
    let eventosMapTickets = {};
    let clientesMapTickets = {};

    document.addEventListener('DOMContentLoaded', async () => {
      await cargarEmpresas();
      await cargarCategorias();
      await buildTicketsMaps();
      await cargarReportesTickets();
      await cargarReportesEventos();

      // Buscador tickets (filtra y pagina)
      const buscadorTickets = document.getElementById('buscador-tickets');
      buscadorTickets.addEventListener('keyup', () => {
        applyTicketsFilterAndRender();
      });

      // Buscador eventos (fila a fila)
      const buscadorEventos = document.getElementById('buscador-eventos');
      buscadorEventos.addEventListener('keyup', () => {
        const filtro = buscadorEventos.value.toLowerCase();
        const filas = document.querySelectorAll('#tbody-eventos tr');
        filas.forEach(fila => {
          fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? '' : 'none';
        });
      });
    });

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

    async function cargarReportesTickets() {
      const tbody = document.getElementById('tbody-reportes');
      tbody.innerHTML = '<tr><td colspan="6" class="loading">Cargando tickets...</td></tr>';
      try {
        const res = await fetch(ApiConexion + 'listarTickets');
        const data = await res.json();
        const tickets = Array.isArray(data) ? data : (Array.isArray(data.tickets) ? data.tickets : []);
        // Normalizar y enriquecer con mapas ya construidos
        ticketsData = tickets.map(t => {
          const eventoNombre = eventosMapTickets[t.evento_id] ?? t.evento_titulo ?? 'Sin evento';
          const clienteNombre = clientesMapTickets[t.cliente_id] ?? t.cliente_nombre ?? 'Sin cliente';
          const fechaCompra = t.fecha_compra ? (t.fecha_compra.includes('T') ? t.fecha_compra.split('T')[0] : t.fecha_compra) : '—';
          return {
            id: t.id,
            eventoNombre,
            clienteNombre,
            precio: t.precio ?? 0,
            estado: t.estado ?? '—',
            fechaCompra
          };
        });
        applyTicketsFilterAndRender();
      } catch (error) {
        console.error("Error cargando tickets:", error);
        tbody.innerHTML = '<tr><td colspan="6" class="loading">Error cargando tickets</td></tr>';
      }
    }

    // Construir mapas una sola vez para los tickets (eventos y clientes)
    async function buildTicketsMaps() {
      try {
        const [resEventos, resClientes] = await Promise.all([
          fetch(ApiConexion + 'listarEventos'),
          fetch(ApiConexion + 'listarClientes')
        ]);
        const dataEventos = await resEventos.json();
        const dataClientes = await resClientes.json();

        eventosMapTickets = {};
        (Array.isArray(dataEventos?.eventos) ? dataEventos.eventos : (Array.isArray(dataEventos) ? dataEventos : [])).forEach(e => {
          eventosMapTickets[e.id] = e.titulo ?? 'Sin evento';
        });

        clientesMapTickets = {};
        (Array.isArray(dataClientes?.clientes) ? dataClientes.clientes : (Array.isArray(dataClientes) ? dataClientes : [])).forEach(c => {
          clientesMapTickets[c.id] = `${c.nombre ?? ''} ${c.apellido ?? ''}`.trim() || 'Sin cliente';
        });
      } catch (e) {
        console.warn('No se pudieron construir los mapas de tickets', e);
        eventosMapTickets = {};
        clientesMapTickets = {};
      }
    }

    function applyTicketsFilterAndRender() {
      const buscadorTickets = document.getElementById('buscador-tickets');
      const filtro = (buscadorTickets?.value || '').toLowerCase();
      filteredTickets = ticketsData.filter(t => {
        const text = `${t.eventoNombre} ${t.clienteNombre} ${t.precio} ${t.estado} ${t.fechaCompra}`.toLowerCase();
        return text.includes(filtro);
      });
      currentPageTickets = 1;
      renderTicketsPage(currentPageTickets);
    }

    function renderTicketsPage(page) {
      const tbody = document.getElementById('tbody-reportes');
      if (!Array.isArray(filteredTickets)) filteredTickets = [];
      const total = filteredTickets.length;
      const totalPages = Math.max(1, Math.ceil(total / pageSizeTickets));
      currentPageTickets = Math.min(Math.max(1, page), totalPages);

      const start = (currentPageTickets - 1) * pageSizeTickets;
      const end = start + pageSizeTickets;
      const items = filteredTickets.slice(start, end);

      if (items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="loading">No hay tickets registrados</td></tr>';
      } else {
        tbody.innerHTML = items.map(t => `
      <tr>
        <td>${t.eventoNombre}</td>
        <td>${t.clienteNombre}</td>
        <td><input type="number" class="input-precio" value="${t.precio}" readonly></td>
        <td>${t.estado}</td>
        <td>${t.fechaCompra}</td>
      </tr>
    `).join('');
      }

      renderTicketsPagination(total, totalPages);
    }

    function renderTicketsPagination(total, totalPages) {
      const container = document.getElementById('paginacion-tickets');
      if (!container) return;
      if (total === 0) {
        container.innerHTML = '';
        return;
      }

      const buttons = [];

      // Info
      buttons.push(`<span class="info">Total: ${total}</span>`);

      // Prev
      buttons.push(`<button ${currentPageTickets === 1 ? 'disabled' : ''} data-page="${currentPageTickets - 1}">&laquo;</button>`);

      // Page numbers window
      const maxToShow = 5;
      let start = Math.max(1, currentPageTickets - Math.floor(maxToShow / 2));
      let end = Math.min(totalPages, start + maxToShow - 1);
      start = Math.max(1, end - maxToShow + 1);

      for (let p = start; p <= end; p++) {
        buttons.push(`<button class="${p === currentPageTickets ? 'active' : ''}" data-page="${p}">${p}</button>`);
      }

      // Next
      buttons.push(`<button ${currentPageTickets === totalPages ? 'disabled' : ''} data-page="${currentPageTickets + 1}">&raquo;</button>`);

      container.innerHTML = buttons.join('');

      // Bind events
      Array.from(container.querySelectorAll('button[data-page]')).forEach(btn => {
        btn.addEventListener('click', () => {
          const page = parseInt(btn.getAttribute('data-page'), 10);
          if (!isNaN(page)) renderTicketsPage(page);
        });
      });
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
            let accionesHtml = '';

            // Mostrar el botón solo si el evento está finalizado
            if (evento.estado && evento.estado.toLowerCase() === 'finalizado') {
              accionesHtml = `<button class="btn btn-edit" style="background-color: #1d6f42;" onclick="exportarEvento(${evento.id})">
                                <i class="fas fa-file-excel"></i> Exportar Reporte
                              </button>`;
            } else {
              accionesHtml = `<button class="btn btn-edit" style="background-color: #1d6f42;" disabled>
                                <i class="fas fa-file-excel"></i> Sin Finalizar
                              </button>`;
            }

            tbody.innerHTML += `
          <tr>
            <td>${evento.titulo ?? '—'}</td>
            <td>${evento.fecha ?? '—'}</td>
            <td>${evento.hora_inicio ?? '—'}</td>
            <td>${evento.hora_final ?? '—'}</td>
            <td>${evento.estado ?? '—'}</td>
            <td>${empresaNombre}</td>
            <td>${categoriaNombre}</td>
            <td>${accionesHtml}</td>
          </tr>`;
          });
        } else {
          tbody.innerHTML = '<tr><td colspan="9" class="loading">No hay eventos registrados</td></tr>';
        }
      } catch (error) {
        console.error("Error cargando eventos:", error);
        tbody.innerHTML = '<tr><td colspan="9" class="loading">Error cargando eventos</td></tr>';
      }
    }

    async function exportarEvento(eventoId) {

      Swal.fire({
        title: "Generando reporte...",
        html: "Por favor espera unos segundos",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });

      try {
        const response = await fetch(`${ApiConexion}exportar/evento/${eventoId}`, {
          method: "GET",
          headers: {
            "Accept": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
          }
        });

        if (!response.ok) {
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudo generar el reporte. Verifica que existan tickets para este evento."
          });
          return;
        }

        // Obtener el Blob del Excel
        const blob = await response.blob();

        // Crear enlace de descarga
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = `Reporte_Evento_${eventoId}.xlsx`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);

        Swal.close();
        Swal.fire({
          icon: "success",
          title: "Reporte generado",
          text: "El archivo Excel ha sido descargado correctamente."
        });

      } catch (error) {
        Swal.close();
        console.error("Error exportando evento:", error);
        Swal.fire({
          icon: "error",
          title: "Error inesperado",
          text: "Hubo un problema al exportar el reporte."
        });
      }
    }


    function volverDashboard() {
      window.location.href = 'index.php?ruta=dashboard-admin';
    }
  </script>

</body>

</html>