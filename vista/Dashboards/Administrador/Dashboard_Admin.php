<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>

  <!-- Estilos principales -->
  <link rel="stylesheet" href="vista/css/admin.css?v=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
        <span class="icon-circle"><i class="bi bi-gear-fill icon-inline"></i></span>
        <span class="welcome-text">¡Bienvenido, <strong id="nombreAdmin"></strong></span>
      </div>

      <div class="header-actions">
        <a href="index.php?ruta=Configuracion_admin" class="btn btn-explore">
          <i class="bi bi-gear-fill icon-inline"></i> Configuración
        </a>
        <a href="index.php?ruta=mi_perfil_admin" class="btn btn-explore">
          <i class="bi bi-person-circle icon-inline"></i> Mi Perfil
        </a>
        <a href="index.php?ruta=Reservas" class="btn btn-explore">
          <i class="bi bi-calendar-event icon-inline"></i> Reservas
        </a>
        <a href="index.php?ruta=Reportes" class="btn btn-explore">
          <i class="bi bi-bar-chart-line icon-inline"></i> Reportes
        </a>

        <button class="btn btn-profile" onclick="confirmLogoutAdmin()">
          <i class="bi bi-box-arrow-right icon-inline"></i> Cerrar Sesión
        </button>
      </div>
    </header>

    <!-- ===================================== -->
    <!--               CONTENIDO               -->
    <!-- ===================================== -->

    <!-- ********** COLUMNA DERECHA (AHORA SUPERIOR) ********** -->

    <main class="dashboard-main-der">
      <section class="content-area">

        <div class="stats-grid">

          <!-- INGRESOS -->
          <div class="widget">
            <div class="widget-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="widget-content">
              <span id="totalEntradas" class="widget-number">0</span>
              <span class="widget-title">Ingresos mes</span>
            </div>
          </div>

          <!-- TICKETS -->
          <div class="widget">
            <div class="widget-icon"><i class="bi bi-ticket-perforated"></i></div>
            <div class="widget-content">
              <span id="totalTickets" class="widget-number">0</span>
              <span class="widget-title">Entradas vendidas</span>
            </div>
          </div>

          <!-- EVENTOS -->
          <div class="widget">
            <div class="widget-icon"><i class="bi bi-calendar-event"></i></div>
            <div class="widget-content">
              <span id="totalEventos" class="widget-number">0</span>
              <span class="widget-title">Funciones</span>
            </div>
          </div>

          <!-- OCUPACIÓN -->
          <div class="widget">
            <div class="widget-icon"><i class="bi bi-pie-chart"></i></div>
            <div class="widget-content">
              <span id="ocupacionPromedio" class="widget-number">0</span>
              <span class="widget-title">Ocupación</span>
            </div>
          </div>

        </div>

      </section>
    </main>


    <!-- ********** COLUMNA IZQUIERDA ********** -->
    <main class="dashboard-main-izq">
      <section class="content-area">

        <aside>
          <div class="summary-widgets horizontal-widgets">
            <!-- CLIENTES -->
            <div class="widget">
              <div class="widget-icon green-bg"><i class="bi bi-people"></i></div>
              <div class="widget-content">
                <span id="totalClientes" class="widget-number">0</span>
                <span class="widget-title">Clientes registrados</span>

                <a href="index.php?ruta=Ver_Usuarios_Admin" class="btn btn-explore btn btn-confirm btn-full-width">
                  <i class="bi bi-people icon-inline"></i> Gestionar Clientes
                </a>
              </div>
            </div>

            <!-- EMPRESAS -->
            <div class="widget">
              <div class="widget-icon"><i class="bi bi-buildings"></i></div>
              <div class="widget-content">
                <span id="totalEmpresas" class="widget-number">0</span>
                <span class="widget-title">Empresas registradas</span>
                <a href="index.php?ruta=Ver_Empresas_Admin" class="btn btn-explore btn btn-confirm btn-full-width">
                  <i class="bi bi-buildings icon-inline"></i> Gestionar Empresas
                </a>
              </div>
            </div>

            <!-- CONFIGURACIÓN -->
            <div class="widget">
              <div class="widget-icon"><i class="bi bi-tag"></i></div>
              <div class="widget-content">
                <span id="totalCategorías" class="widget-number">0</span>
                <span class="widget-title">Categorías registradas</span>
                <a href="index.php?ruta=Ver_Categorias_Admin" class="btn btn-explore btn btn-confirm btn-full-width">
                  <i class="bi bi-tag icon-inline"></i> Gestionar Categorías
                </a>
              </div>
            </div>
          </div>
        </aside>
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