<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportes de Ventas y Eventos</title>
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">

  <style>
    body {
      background-image: url('../../css/img/fondo.png');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
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

    .btn-delete {
      background-color: #dc3545;
      color: #fff;
      box-shadow: 0 10px 20px rgba(255, 50, 50, 0.5);
    }

    .btn-delete:hover {
      background-color: #c82333;
      transform: scale(1.05);
    }

    /* ✅ Botón fijo en la esquina */
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

    .input-precio, .input-fecha {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      border-radius: 6px;
      color: #fff;
      padding: 5px;
      text-align: center;
      width: 90%;
    }

    .input-precio:focus, .input-fecha:focus {
      outline: none;
      background-color: rgba(255, 255, 255, 0.3);
    }

  </style>
</head>
<body>

  <!-- Botón fijo arriba -->
  <button class="btn btn-back" onclick="volverDashboard()">
    ⬅️ Volver a Inicio
  </button>

  <div class="dashboard-container">
    <h1>📊 Reportes de Ventas de Tickets</h1>

    <!-- Tabla de Tickets -->
    <table id="tabla-reportes">
      <thead>
        <tr>
          <th>Evento</th>
          <th>Cliente</th>
          <th>Tipo</th>
          <th>Precio</th>
          <th>Estado</th>
          <th>Fecha de Compra</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tbody-reportes">
        <tr><td colspan="7" class="loading">Cargando reportes...</td></tr>
      </tbody>
    </table>

    <h1>🕒 Reporte de Eventos</h1>

    <!-- Tabla de Eventos -->
    <table id="tabla-eventos">
      <thead>
        <tr>
          <th>Título</th>
          <th>Descripción</th>
          <th>Fecha Inicio</th>
          <th>Fecha Fin</th>
          <th>Lugar</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tbody-eventos">
        <tr><td colspan="7" class="loading">Cargando eventos...</td></tr>
      </tbody>
    </table>
  </div>

  <script>
    const ApiConexion = "https://uncoachable-rosaline-lasciviously.ngrok-free.dev/api/";

    function volverDashboard() {
      window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
    }

    // 📊 Datos simulados
    const ticketsSimulados = [
      { id: 1, evento: "Concierto Rock", cliente: "Juan Pérez", tipo: "VIP", precio: 85000, estado: "Pagado", fecha_compra: "2025-10-15" },
      { id: 2, evento: "Obra de Teatro", cliente: "María López", tipo: "General", precio: 50000, estado: "Pendiente", fecha_compra: "2025-10-20" },
      { id: 3, evento: "Feria Cultural", cliente: "Carlos Gómez", tipo: "VIP", precio: 90000, estado: "Pagado", fecha_compra: "2025-11-01" }
    ];

    const eventosSimulados = [
      { id: 1, titulo: "Concierto Rock", descripcion: "Banda en vivo toda la noche", fecha_inicio: "2025-11-20", fecha_fin: "2025-11-21", lugar: "Auditorio Central", estado: "Activo" },
      { id: 2, titulo: "Obra de Teatro", descripcion: "Clásico colombiano", fecha_inicio: "2025-11-25", fecha_fin: "2025-11-25", lugar: "Teatro del Sol", estado: "Activo" },
      { id: 3, titulo: "Feria Cultural", descripcion: "Evento de arte y música", fecha_inicio: "2025-12-01", fecha_fin: "2025-12-03", lugar: "Plaza Mayor", estado: "Pendiente" }
    ];

    // 🧾 Cargar reportes
    function cargarReportes() {
      const tbody = document.getElementById('tbody-reportes');
      tbody.innerHTML = "";

      ticketsSimulados.forEach(ticket => {
        const row = `
          <tr>
            <td>${ticket.evento}</td>
            <td>${ticket.cliente}</td>
            <td>${ticket.tipo}</td>
            <td><input type="number" class="input-precio" value="${ticket.precio}" onchange="editarPrecio(${ticket.id}, this.value)"></td>
            <td>${ticket.estado}</td>
            <td>${ticket.fecha_compra}</td>
            <td><button class="btn btn-edit" onclick="editarTicket(${ticket.id})">✏️ Editar</button></td>
          </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
      });
    }

    // 🕒 Cargar eventos
    function cargarEventos() {
      const tbody = document.getElementById('tbody-eventos');
      tbody.innerHTML = "";

      eventosSimulados.forEach(evento => {
        const row = `
          <tr>
            <td>${evento.titulo}</td>
            <td>${evento.descripcion}</td>
            <td><input type="date" class="input-fecha" value="${evento.fecha_inicio}" onchange="editarHorario(${evento.id}, 'inicio', this.value)"></td>
            <td><input type="date" class="input-fecha" value="${evento.fecha_fin}" onchange="editarHorario(${evento.id}, 'fin', this.value)"></td>
            <td>${evento.lugar}</td>
            <td>${evento.estado}</td>
            <td><button class="btn btn-edit" onclick="editarEvento(${evento.id})">✏️ Editar</button></td>
          </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
      });
    }

    // ✏️ Funciones de edición simuladas
    function editarPrecio(id, nuevoPrecio) {
      alert(`💰 Precio del ticket ${id} actualizado a ${nuevoPrecio}`);
    }

    function editarHorario(id, tipo, nuevaFecha) {
      alert(`🕒 Fecha de ${tipo} del evento ${id} actualizada a ${nuevaFecha}`);
    }

    function editarTicket(id) {
      alert(`✏️ Editar información completa del ticket ${id}`);
    }

    function editarEvento(id) {
      alert(`✏️ Editar información completa del evento ${id}`);
    }

    // 🚀 Ejecutar al cargar
    cargarReportes();
    cargarEventos();
  </script>

</body>
</html>
