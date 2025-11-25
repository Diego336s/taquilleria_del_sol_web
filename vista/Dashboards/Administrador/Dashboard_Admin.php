<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>

  <!-- Estilos principales -->
  <link rel="stylesheet" href="vista/css/empresa.css?v=1.0">
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">

  <!-- Librerías -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

  <!-- CONTENEDOR PRINCIPAL -->
  <div class="dashboard-container" id="dashboard-usuario-body">

    <!-- ===================================== -->
    <!--              ENCABEZADO               -->
    <!-- ===================================== -->
    <header class="dashboard-header">
      <div class="user-greeting">
        <span class="icon-circle">⚙️</span>
        <span class="welcome-text">¡Bienvenido, <strong id="nombreAdmin"></strong></span>
      </div>

      <div class="header-actions">
        <a href="index.php?ruta=Configuracion_admin" class="btn btn-explore">
          ⚙️ Configuración
        </a>
        <a href="index.php?ruta=mi_perfil_admin" class="btn btn-explore">
          😊 Mi Perfil
        </a>
        <a href="index.php?ruta=Reservas" class="btn btn-explore">
          📅 Reservas
        </a>
        <a href="index.php?ruta=Reportes" class="btn btn-explore">
          📊 Reportes
        </a>

        <button class="btn btn-profile" onclick="confirmLogoutAdmin()">
          🚪 Cerrar Sesión
        </button>
      </div>
    </header>

    <!-- ===================================== -->
    <!--               CONTENIDO               -->
    <!-- ===================================== -->
    <main class="dashboard-main">

      <!-- ********** COLUMNA IZQUIERDA ********** -->
      <aside class="summary-widgets">

        <!-- CLIENTES -->
        <div class="widget">
          <div class="widget-icon green-bg">👥</div>
          <div class="widget-content">
            <span id="totalClientes" class="widget-number">0</span>
            <span class="widget-title">Clientes registrados</span>

            <a href="index.php?ruta=Ver_Usuarios_Admin" class="btn btn-explore btn btn-confirm btn-full-width">
              👥 Ver todos los clientes
            </a>
          </div>
        </div>

        <!-- EMPRESAS -->
        <div class="widget">
          <div class="widget-icon">🏢</div>
          <div class="widget-content">
            <span id="totalEmpresas" class="widget-number">0</span>
            <span class="widget-title">Empresas registradas</span>
            <a href="index.php?ruta=Ver_Empresas_Admin" class="btn btn-explore btn btn-confirm btn-full-width">
              🏢 Ver todas las empresas
            </a>
          </div>
        </div>

        <!-- CONFIGURACIÓN -->
        <div class="widget">
          <div class="widget-content">
            <span class="widget-title h3 centrar">⚙️ Configuración del sistema</span>

            <button class="btn btn-confirm btn-full-width orange-bg"
              onclick="window.location.href='/taquilleria_del_sol_web/vista/Dashboards/Administrador/PanelConfiguración.php'">
              ⚙️ Panel de Configuración
            </button>
          </div>
        </div>

      </aside>

      <!-- ********** COLUMNA DERECHA ********** -->
      <section class="content-area">

        <div class="stats-grid">

          <!-- INGRESOS -->
          <div class="widget">
            <div class="widget-icon">💲</div>
            <div class="widget-content">
              <span id="totalEntradas" class="widget-number">0</span>
              <span class="widget-title">Ingresos mes</span>
            </div>
          </div>

          <!-- TICKETS -->
          <div class="widget">
            <div class="widget-icon">👁️</div>
            <div class="widget-content">
              <span id="totalTickets" class="widget-number">0</span>
              <span class="widget-title">Entradas vendidas</span>
            </div>
          </div>

          <!-- EVENTOS -->
          <div class="widget">
            <div class="widget-icon">📈</div>
            <div class="widget-content">
              <span id="totalEventos" class="widget-number">0</span>
              <span class="widget-title">Funciones</span>
            </div>
          </div>

          <!-- OCUPACIÓN -->
          <div class="widget">
            <div class="widget-icon">📊</div>
            <div class="widget-content">
              <span id="ocupacionPromedio" class="widget-number">0</span>
              <span class="widget-title">Ocupación</span>
            </div>
          </div>

        </div>

        <!-- ACTIVIDAD RECIENTE -->
        <div class="widget recent-reservations-card">
          <div class="widget-header">
            <h3 class="section-title" style="color:#fff;">Actividad Reciente del Sistema</h3>
            <button class="btn btn-confirm">Ver Todo</button>
          </div>

          <div class="reservation-list">
            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">Nueva empresa registrada: <b>“Corporación ABC”</b></span>
                <span class="item-title activity-time">Hace 15 minutos</span>
              </div>
            </div>

            <div class="reservation-item info">
              <div class="item-details">
                <span class="item-title">Backup automático completado exitosamente</span>
                <span class="item-title activity-time">Hace 1 hora</span>
              </div>
            </div>

            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">Actualización de precios aplicada</span>
                <span class="item-title activity-time">Hace 2 horas</span>
              </div>
            </div>
          </div>
        </div>

      </section>
    </main>
  </div>

  <!-- ===================================== -->
  <!--                SCRIPTS                -->
  <!-- ===================================== -->
  <script src="../../../js/ApiConexion.js"></script>
  <script src="../../../js/Admin/Dashboard_Admin.js"></script>

  <script>
    async function cargarTotalesDashboard() {
      const totalClientes = document.getElementById("totalClientes");
      const totalEmpresas = document.getElementById("totalEmpresas");
      const totalTickets = document.getElementById("totalTickets");
      const totalEventos = document.getElementById("totalEventos");

      try {
        const resClientes = await fetch(ApiConexion + "listarClientes");
        const clientes = await resClientes.json();
        totalClientes.textContent = (clientes.clientes?.length ?? 0).toLocaleString();

        const resEmp = await fetch(ApiConexion + "listarEmpresas");
        const empresas = await resEmp.json();
        totalEmpresas.textContent = (empresas.data?.length ?? 0).toLocaleString();

        const resTickets = await fetch(ApiConexion + "listarTickets");
        const tickets = await resTickets.json();
        totalTickets.textContent = (Array.isArray(tickets) ? tickets.length : 0).toLocaleString();

        const resEventos = await fetch(ApiConexion + "listarEventos");
        const eventos = await resEventos.json();
        totalEventos.textContent = (eventos.eventos?.length ?? 0).toLocaleString();

      } catch (err) {
        totalClientes.textContent =
          totalEmpresas.textContent =
          totalTickets.textContent =
          totalEventos.textContent = "—";
      }
    }

    document.addEventListener("DOMContentLoaded", cargarTotalesDashboard);
  </script>

</body>

</html>