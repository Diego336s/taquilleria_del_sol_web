<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión de Reservas</title>

  <!-- ICONOS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- FLATPICKR -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

  <style>
    /* === CORTINA OSCURA GLOBAL === */
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

    body {
      position: relative;

      font-family: Arial, sans-serif;
      background-color: #121212;
      color: #fff;
      margin: 0;
      padding: 0;
    }

    .btn-back {
      background: #ff6b1f;
      color: #fff;
      font-weight: bold;
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin: 20px;
      box-shadow: 0 4px 12px rgba(255, 107, 31, 0.4);
      transition: all 0.3s ease;
    }

    .centrar {
      display: flex;
      text-align: center;
      justify-content: center;
    }

    h1 {

      text-align: center;

      color: #fff;
      margin-top: 10px;
      text-transform: uppercase;
      font-weight: 700;
    }

    .dashboard-container {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.12);
      border-radius: 20px;
      padding: 30px;
      margin: 20px auto;
      width: 95%;
      max-width: 1500px;
      box-shadow: 0 10px 25px rgba(255, 107, 31, 0.6);
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    /* Buscador elegante */
    .search-box {
      position: relative;
      width: 220px;
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

    /* Contenedor de tabla */
    .tabla-container {
      flex: 1;
      overflow-x: auto;
    }

    table {
      width: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      border-radius: 15px;
      overflow: hidden;
      color: #fff;
      text-align: center;
      border-collapse: collapse;
    }

    .controles-tabla {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    thead {
      background: linear-gradient(90deg, #ff6b1f, #ffcc00);
      color: #000;
      font-weight: bold;
    }

    th,
    td {
      padding: 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn {
      border: none;
      border-radius: 6px;
      padding: 7px 12px;
      cursor: pointer;
      font-weight: bold;

    }

    .btn-aprobar {
      background: #008000;
      color: #fff;
    }

    .btn-rechazar {
      background: #ff0000;
      color: #fff;
    }

    .event-thumb {
      width: 110px;
      height: 70px;
      border-radius: 6px;
      object-fit: cover;
      border: 2px solid #ff6b1f;
      cursor: pointer;
    }

    td.descripcion-corta {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      cursor: pointer;
      color: #ffd27f;
      font-weight: bold;
    }

    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.65);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .modal-box {
      width: 400px;
      background: #ffffff;
      padding: 20px 25px;
      border-radius: 12px;
      position: relative;
      color: #000;
    }

    .modal-close-x {
      position: absolute;
      top: 8px;
      right: 10px;
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
    }

    .calendar-wrapper {
      width: 300px;
      flex-shrink: 0;
    }

    .flatpickr-day.ocupada {
      background: #ff0000 !important;
      color: white !important;
      border-radius: 8px;
    }

    #sinPendientes {
      color: #fff;
      text-align: center;
      margin-top: 10px;
    }

    @media (max-width: 1200px) {
      .calendar-wrapper {
        width: 100%;
        margin-top: 20px;
        order: 2;
        /* Asegura que el calendario vaya después */
      }
    }

    /* ===== MODAL DE EVENTO POR FECHA ===== */

    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(4px);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 99999;
      animation: fadeIn 0.3s ease;
    }

    .modal-box-evento {
      width: 450px;
      max-height: 85vh;
      overflow-y: auto;
      background: rgba(20, 20, 20, 0.95);
      border-radius: 14px;
      padding: 25px 28px;
      position: relative;
      box-shadow: 0 0 20px rgba(255, 107, 31, 0.35);
      border: 1px solid rgba(255, 107, 31, 0.4);
      animation: popIn 0.28s ease;
    }

    /* Barra de desplazamiento elegante */
    .modal-box-evento::-webkit-scrollbar {
      width: 6px;
    }

    .modal-box-evento::-webkit-scrollbar-thumb {
      background: #ff6b1f;
      border-radius: 10px;
    }

    .modal-evento-titulo {
      text-align: center;
      font-size: 22px;
      color: #ff6b1f;
      font-weight: 700;
      margin-bottom: 15px;
    }

    .modal-close-x {
      position: absolute;
      top: 10px;
      right: 14px;
      background: #ff6b1f;
      color: #fff;
      border: none;
      font-size: 18px;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      cursor: pointer;
      transition: 0.2s;
      font-weight: bold;
    }

    .modal-close-x:hover {
      background: #ff8c42;
      transform: scale(1.1);
    }

    /* Tarjeta de evento */
    .evento-card {
      padding: 15px;
      margin-bottom: 18px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.08);
      border-left: 4px solid #ff6b1f;
      box-shadow: 0 0 10px rgba(255, 107, 31, 0.2);
    }

    .evento-card h4 {
      margin: 0;
      color: #ff6b1f;
      font-size: 20px;
    }

    .evento-card p {
      margin: 5px 0;
      font-size: 14px;
      color: #ddd;
    }

    .evento-card img {
      width: 100%;
      border-radius: 8px;
      margin-top: 10px;
      border: 2px solid rgba(255, 107, 31, 0.5);
    }

    /* Animaciones */
    @keyframes popIn {
      from {
        transform: scale(0.85);
        opacity: 0;
      }

      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }
  </style>
</head>

<body>


  <div class="dashboard-container">
    <!-- Tabla + buscador -->
    <div class="tabla-container">
      <div class="centrar" style="width: 100%; margin-bottom: 15px;">
        <h1 style="margin: 0;">Gestión de Reservas</h1>
      </div>

      <div class="controles-tabla">

        <button class="btn btn-back" onclick="volverDashboard()">⬅️ Volver a Inicio</button>

        <!-- Buscador arriba a la derecha -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="buscador" placeholder="Buscar reserva...">
          </div>
        </div>
      </div>
      <!-- Tabla de reservas -->
      <table>
        <thead>
          <tr>
            <th>Título</th>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Empresa</th>
            <th>Categoría</th>
            <th>Imagen</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaReservas"></tbody>
      </table>

      <div id="sinPendientes" style="display:none;">No hay eventos pendientes.</div>
    </div>

    <!-- Calendario a la derecha -->
    <div class="calendar-wrapper">
      <div id="calendarioStatic"></div>
    </div>
  </div>

  <div class="modal-overlay" id="modalDescripcion">
    <div class="modal-box">
      <button class="modal-close-x" onclick="cerrarModal()">×</button>
      <h3 style="text-align:center; color:#ff6b1f;">Descripción completa</h3>
      <p id="modalTexto" style="white-space:pre-wrap;"></p>
    </div>
  </div>

  <!-- MODAL EVENTO POR FECHA -->
  <div class="modal-overlay" id="modalEventoFecha">
    <div class="modal-box-evento">
      <button class="modal-close-x" onclick="cerrarModalEventoFecha()">×</button>
      <h3 class="modal-evento-titulo">Evento en esta fecha</h3>
      <div id="contenidoEventoFecha"></div>
    </div>
  </div>



  <script src="vista/js/ApiConexion.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function abrirModal(texto) {
      document.getElementById("modalTexto").innerText = texto;
      document.getElementById("modalDescripcion").style.display = "flex";
    }

    function cerrarModal() {
      document.getElementById("modalDescripcion").style.display = "none";
    }

    function cerrarModalEventoFecha() {
      document.getElementById("modalEventoFecha").style.display = "none";
    }

    let eventosPorFecha = {};

    let empresasMap = {};
    let categoriasMap = {};
    let fpInstance = null;


    async function cargarEmpresas() {
      const res = await fetch(ApiConexion + "listarEmpresas");
      const data = await res.json();
      (data.data || data.empresas || []).forEach(e => {
        empresasMap[e.id ?? e.id_empresa] = e.nombre_empresa ?? e.nombre;
      });
    }

    async function cargarCategorias() {
      const res = await fetch(ApiConexion + "listarCategorias");
      const data = await res.json();
      (data.data || data.categorias || []).forEach(c => {
        categoriasMap[c.id ?? c.id_categoria] = c.nombre ?? c.nombre_categoria;
      });
    }

    async function cambiarEstado(id, nuevoEstado) {
      Swal.fire({
        title: `¿Cambiar estado a ${nuevoEstado}?`,
        icon: "question",
        confirmButtonText: "Sí",
        showCancelButton: true
      }).then(async (res) => {
        if (!res.isConfirmed) return;
        try {
          const respuesta = await fetch(ApiConexion + `cambiar/estado/evento/${id}`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({
              estado: nuevoEstado
            })
          });
          const data = await respuesta.json();
          if (!respuesta.ok) {
            Swal.fire("Error", data.message ?? "No se pudo cambiar el estado", "error");
            return;
          }
          Swal.fire("Éxito", "Estado actualizado", "success");
          cargarReservas();
        } catch (e) {
          Swal.fire("Error", "No se pudo conectar con el servidor", "error");
        }
      });
    }

    async function cargarReservas() {
      const res = await fetch(ApiConexion + "listarEventos");
      const data = await res.json();
      const eventos = data.eventos || data.data || [];
      const tbody = document.getElementById("tablaReservas");
      tbody.innerHTML = "";

      let fechasActivas = [];
      let pendientes = 0;

      eventos.forEach(ev => {
        const fecha = ev.fecha.split("T")[0];

        // Guardar evento por fecha
        eventosPorFecha[fecha] ??= [];
        eventosPorFecha[fecha].push(ev);

        if (ev.estado === "activo") fechasActivas.push(fecha);

        const desc = ev.descripcion ?? "Sin descripción";
        if (ev.estado === "activo") fechasActivas.push(ev.fecha.split("T")[0]);
        if (ev.estado === "pendiente") {
          pendientes++;
          tbody.innerHTML += `
        <tr>
          <td>${ev.titulo}</td>
          <td class="descripcion-corta" onclick='abrirModal(${JSON.stringify(desc)})'>${desc}</td>
          <td>${ev.fecha.split("T")[0]}</td>
          <td>${ev.hora_inicio}</td>
          <td>${ev.hora_final}</td>
          <td>${empresasMap[ev.empresa_id] ?? "Empresa"}</td>
          <td>${categoriasMap[ev.categoria_id] ?? "Categoria"}</td>
          <td><img src="${ev.imagen}" class="event-thumb"></td>
          <td>${ev.estado}</td>
          <td>
            <button class="btn btn-aprobar" onclick="cambiarEstado(${ev.id}, 'activo')">Aprobar</button>
            <button class="btn btn-rechazar mt-2" onclick="cambiarEstado(${ev.id}, 'cancelado')">Rechazar</button>
          </td>
        </tr>
      `;
        }
      });

      document.getElementById("sinPendientes").style.display = pendientes === 0 ? "block" : "none";
      iniciarCalendario([...new Set(fechasActivas)]);
    }

    function iniciarCalendario(fechas) {
      if (fpInstance) fpInstance.destroy();
      fpInstance = flatpickr("#calendarioStatic", {
        inline: true,
        locale: "es",
        dateFormat: "Y-m-d",
        onDayCreate: (dObj, dStr, fp, dayElem) => {
          const fecha = dayElem.dateObj.toISOString().split("T")[0];

          if (fechas.includes(fecha)) {
            dayElem.classList.add("ocupada");

            // Hacer clic en los días ocupados
            dayElem.addEventListener("click", () => {
              mostrarEventoPorFecha(fecha);
            });
          }
        }

      });
    }

    const buscador = document.getElementById('buscador');
    buscador.addEventListener('keyup', () => {
      const filtro = buscador.value.toLowerCase();
      const filas = document.querySelectorAll('#tablaReservas tr');
      filas.forEach(fila => {
        fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? '' : 'none';
      });
    });

    async function init() {
      await cargarEmpresas();
      await cargarCategorias();
      await cargarReservas();
    }
    init();

    function volverDashboard() {
      window.location.href = 'index.php?ruta=dashboard-admin';
    }

    function mostrarEventoPorFecha(fecha) {

      const eventoLista = eventosPorFecha[fecha] || [];

      if (eventoLista.length === 0) return;

      let html = "";

      eventoLista.forEach(ev => {
        html += `
            <div style="margin-bottom:15px; padding:10px; border-left:4px solid #ff6b1f;">
                <h4 style="margin:0; color:#ff6b1f;">${ev.titulo}</h4>
                <p><strong>Descripción:</strong> ${ev.descripcion ?? "Sin descripción"}</p>
                <p><strong>Empresa:</strong> ${empresasMap[ev.empresa_id] ?? "Empresa"}</p>
                <p><strong>Categoría:</strong> ${categoriasMap[ev.categoria_id] ?? "Categoría"}</p>
                <p><strong>Hora:</strong> ${ev.hora_inicio} - ${ev.hora_final}</p>
                <img src="${ev.imagen}" style="width:100%; border-radius:8px; margin-top:10px;">
            </div>
        `;
      });

      document.getElementById("contenidoEventoFecha").innerHTML = html;
      document.getElementById("modalEventoFecha").style.display = "flex";
    }
  </script>
</body>

</html>