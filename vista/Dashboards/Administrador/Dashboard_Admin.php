<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>¡Bienvenido!</title>

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
              <span id="totalIngreso" class="widget-number">0</span>
              <span class="widget-title">Ingreso Anual</span>
            </div>
          </div>

          <!-- TICKETS -->
          <div class="widget">
            <div class="widget-icon"><i class="bi bi-clipboard-data"></i></div>
            <div class="widget-content">
              <span id="totalFuncionesPendientes" class="widget-number">0</span>
              <span class="widget-title">Funciones Pendientes</span>
            </div>
          </div>

          <!-- EVENTOS -->
          <div class="widget">
            <div class="widget-icon"><i class="bi bi-calendar-event"></i></div>
            <div class="widget-content">
              <span id="totalFuncionesActivas" class="widget-number">0</span>
              <span class="widget-title">Funciones Activas</span>
            </div>
          </div>

          <!-- OCUPACIÓN -->
          <div class="widget">
            <div class="widget-icon"><i class="bi bi-calendar-check-fill"></i></div>
            <div class="widget-content">
              <span id="totalFuncionesFinalizadas" class="widget-number">0</span>
              <span class="widget-title">Funciones Realizadas</span>
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
                <span id="totalCategorias" class="widget-number">0</span>
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

    <!-- ===================================== -->
    <!--                SCRIPTS                -->
    <!-- ===================================== -->
    <script src="../../../js/ApiConexion.js"></script>
    <script src="../../../js/Admin/Dashboard_Admin.js"></script>

    <script>
      async function cargarTotalesDashboard() {

        const totalIngreso = document.getElementById("totalIngreso");
        const totalClientes = document.getElementById("totalClientes");
        const totalEmpresas = document.getElementById("totalEmpresas");
        const totalCategorias = document.getElementById("totalCategorias");
        const totalFuncionesPendientes = document.getElementById("totalFuncionesPendientes");
        const totalFuncionesActivas = document.getElementById("totalFuncionesActivas");
        const totalFuncionesFinalizadas = document.getElementById("totalFuncionesFinalizadas");


        // Mostrar loader antes de cargar datos
        totalIngreso.innerHTML = `<i class="fas fa-spinner fa-spin" style="font-size: 22px; opacity: .7;"></i>`;
        totalClientes.innerHTML = `<i class="fas fa-spinner fa-spin" style="font-size: 22px; opacity: .7;"></i>`;
        totalEmpresas.innerHTML = `<i class="fas fa-spinner fa-spin" style="font-size: 22px; opacity: .7;"></i>`;
        totalCategorias.innerHTML = `<i class="fas fa-spinner fa-spin" style="font-size: 22px; opacity: .7;"></i>`;
        totalFuncionesPendientes.innerHTML = `<i class="fas fa-spinner fa-spin" style="font-size: 22px; opacity: .7;"></i>`;
        totalFuncionesFinalizadas.innerHTML = `<i class="fas fa-spinner fa-spin" style="font-size: 22px; opacity: .7;"></i>`;


        try {

          // === INGRESO ANUAL ===
          const resIngresos = await fetch(ApiConexion + "total-recaudado-teatro-año");
          const ingresos = await resIngresos.json();

          const totalAnual = ingresos.total_teatro ?? 0;
          totalIngreso.textContent = `$${Number(totalAnual).toLocaleString('es-CO', { 
          maximumFractionDigits: 0 
          })}`;

          // === EVENTOS ===
          const resEventos = await fetch(ApiConexion + "listarEventos");
          const eventos = await resEventos.json();

          const lista = eventos.eventos ?? eventos.data ?? [];

          let pendientes = 0;
          let activas = 0;
          let finalizadas = 0;

          const hoy = new Date();
          const inicioMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);

          lista.forEach(ev => {
            const fecha = new Date(ev.fecha);

            if (ev.estado === "pendiente") pendientes++;
            if (ev.estado === "activo") activas++;
            if (ev.estado === "finalizado") finalizadas++;
          });

          totalFuncionesPendientes.textContent = pendientes;
          totalFuncionesActivas.textContent = activas;
          totalFuncionesFinalizadas.textContent = finalizadas;


          // === CLIENTES ===
          const resClientes = await fetch(ApiConexion + "listarClientes");
          const clientes = await resClientes.json();
          totalClientes.textContent = (clientes.data?.length ?? clientes.clientes?.length ?? 0);

          // === EMPRESAS ===
          const resEmp = await fetch(ApiConexion + "listarEmpresas");
          const empresas = await resEmp.json();
          totalEmpresas.textContent = (empresas.data?.length ?? empresas.empresas?.length ?? 0);

          // === CATEGORÍAS ===
          const resCategorias = await fetch(ApiConexion + "listarCategorias");
          const categorias = await resCategorias.json();
          totalCategorias.textContent = (categorias.data?.length ?? categorias.categorias?.length ?? 0);

        } catch (err) {
          console.error("Error al cargar dashboard:", err);
        }
      }

      document.addEventListener("DOMContentLoaded", cargarTotalesDashboard);
    </script>

</body>

</html>