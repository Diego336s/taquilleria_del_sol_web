
<title>¡Bienvenido!</title>


<!-- Enlazamos la hoja de estilos específica para este dashboard -->
<link rel="stylesheet" href="vista/css/empresa.css?v=1.0">
<link rel="stylesheet" href="vista/css/main.css?v=1.0">
<script src="https://cdn.tailwindcss.com"></script>


<!-- Usamos el mismo ID para heredar el fondo y estilos base -->
<div class="dashboard-container" id="dashboard-usuario-body">
  <header class="dashboard-header">
    <div class="user-greeting">
      <span class="icon-circle">🏢</span>
      <span class="welcome-text">¡Bienvenido,<strong id="nombreEmpresa"></strong></span>
    </div>
    <div class="header-actions">

      <a href="index.php?ruta=analisis_corporativo" class="btn btn-explore">
        <span class="icon-inline">📈</span> Analisis corporativo
      </a>



      <a href="index.php?ruta=Configuracion_empresa" class="btn btn-explore">
        <i class="fas fa-cog me-2"></i> Configuraciones
      </a>




      <a href="index.php?ruta=mi_perfil_empresa" class="btn btn-explore">
        <i class="fas fa-user-circle me-2"></i> Mi Perfil
      </a>


      <button class="btn btn-profile" id="btnLogoutEmpresa" onclick="confirmLogoutEmpresas()">
        <span class="icon-inline">🚪</span> Cerrar Sesión
      </button>
    </div>
  </header>

  <main class="dashboard-main">

    <!-- ======= Columna Izquierda (Widgets de Empresa) ======= -->
    <aside class="summary-widgets">
      <div class="widget">
        <div class="widget-icon">💰</div>
        <div class="widget-content">
          <div id="loaderEventosActivos" class="loader-wrapper">
            <div class="custom-loader"></div>
          </div>
          <div id="contendorCantidadCantidadEventosActivos"></div>


          <span class="widget-title">Reservas Activas</span>
          <div class="progress-bar-container">
            <div class="progress-bar-fill orange-bg" style="width: 80%;"></div>
          </div>
        </div>
      </div>


      <div class="widget">
        <div class="widget-content">
          <span class="widget-title h3 centrar">Acciones Rápidas</span>
          <div class="header-actions">
            <a href="index.php?ruta=Reservar_funciones" class="btn btn-Reservar"><span class="icon-inline">📋</span> Reservar función</a>
            <a href="index.php?ruta=Eventos_realizados" class="btn btn-eventos_realizados"><span class="icon-inline">📆</span>Eventos realizados</a>
             <a href="index.php?ruta=HistorialDeEventos" class="btn btn-profile"><span class="icon-inline">🗂️</span>Historial de eventos</a>
         
          </div>
        </div>
      </div>
    </aside>

    <!-- ======= Columna Derecha (Contenido de Empresa) ======= -->
    <section class="content-area">
      <div class="stats-grid">
        <div class="widget">
          <div class="widget-icon">💸</div>
          <div class="widget-content">
            <div id="loaderTotalVendido">
              <!-- From Uiverse.io by carlosepcc -->
              <div class="loader border-t-2 rounded-full border-yellow-500 bg-yellow-300 animate-spin
aspect-square w-8 flex justify-center items-center text-yellow-700">$</div>
            </div>
            <div id="contendorTotalVendido"></div>
            <span class="widget-title">Total vendido este año</span>
          </div>
        </div>
        <div class="widget">
          <div class="widget-icon">🗓️</div>
          <div class="widget-content">
            <div id="loaderEventosRealizados" >
              <div class="custom-loader-estadistica"></div>
            </div>
            <div id="contendorEventosRealizados"></div>
            <span class="widget-title">Eventos Realizados</span>
          </div>
        </div>
        <div class="widget">
          <div class="widget-icon">🪑</div>
          <div class="widget-content">
            <div id="loaderAsientosVendidos">
              <div class="custom-loader-estadistica"></div>
            </div>
            <div id="contendorAsientosVendidos"></div>
            <span class="widget-title">Asientos vendidos</span>
          </div>
        </div>

      </div>

      <div class="featured-function orange-bg">
        <span class="featured-label">Próximo Evento</span>
        <h2 id="eventoTitulo" class="featured-title">---</h2>
        <div class="featured-details">
          <span id="ocupacion" class="detail-item">📈 -- % Ocupación</span>
          <span id="ingresos" class="detail-item">💰 $ -- Ingresos</span>
          <span id="asistentes" class="detail-item">👥 -- Asistentes</span>
        </div>

       
      </div>


      <div class="billboard-header">
      </div>



      </div>
    </section>
  </main>
</div>
<script src="vista\js\Empresa\EstadisticasEmpresa.js"></script>