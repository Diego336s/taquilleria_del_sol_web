<!-- Enlazamos la hoja de estilos específica para este dashboard -->
<link rel="stylesheet" href="vista/css/usuario.css?v=1.0">

<div class="dashboard-container" id="dashboard-usuario-body">
  <header class="dashboard-header">
    <div class="user-greeting">
      <span class="icon-circle"><i class="fas fa-user"></i></span>
      <span class="welcome-text">¡Bienvenido,<strong id="nombreUsuario"></strong></span>
    </div>
    <div class="header-actions">
      <a href="index.php?ruta=mis_tickets" class="btn btn-explore">
        <i class="fas fa-ticket-alt me-2"></i>Mis Tickets
      </a>
      <a href="index.php?ruta=mi_perfil" class="btn btn-explore">
        <i class="fas fa-user-circle me-2"></i>Mi Perfil
      </a>
      <a href="index.php?ruta=configuracion_cliente" class="btn btn-explore">
        <i class="fas fa-cog me-2"></i>Configuración
      </a>
      <button class="btn btn-profile" id="btnLogout" onclick="confirmLogout()">
        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
      </button>

    </div>
  </header>

  <main class="dashboard-main">

    <!-- ======= Columna Izquierda ======= -->
    <aside class="summary-widgets">
      <div class="widget">
        <div class="widget-icon">🗓️</div>
        <div class="widget-content">
          <span id="obras_vistas" class="widget-number"></span>
          <span class="widget-title">Obras Vistas</span>
        </div>
      </div>

      <div class="widget">
        <div class="widget-icon">🕒</div>
        <div class="widget-content">
          <span id="proxima_funcion" class="widget-number"></span>
          <span class="widget-title">Próxima Función</span>
        </div>
      </div>

      <div class="widget seat-selector-widget">
        <span class="widget-title">Teatro Principal</span>

        <!-- ESCENARIO -->
        <div class="stage-label">ESCENARIO</div>



        <!-- ÁREA GENERAL -->
        <div class="area-label">ZONA GENERAL</div>

        <div class="seat-map-container">

          <!-- PALCO IZQUIERDO -->
          <div class="palco-side palco-left">
            <div class="palco-title">PALCOS IZQUIERDOS</div>
            <div class="palco-seats">
              <span class="seat-dot available"></span>
              <span class="seat-dot available"></span>
              <span class="seat-dot booked"></span>
            </div>
            <div class="palco-seats">
              <span class="seat-dot booked"></span>
              <span class="seat-dot available"></span>
              <span class="seat-dot available"></span>
            </div>
          </div>

          <!-- ZONA GENERAL (FILAS CENTRALES) -->
          <div class="seat-map">
            <div class="seat-row">
              <span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span>
            </div>
            <div class="seat-row">
              <span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span>
            </div>
            <div class="seat-row">
              <span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span>
            </div>
            <div class="seat-row">
              <span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span>
            </div>
            <div class="seat-row">
              <span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span>
            </div>
          </div>

          <!-- PALCO DERECHO -->
          <div class="palco-side palco-right">
            <div class="palco-title">PALCOS DERECHOS</div>
            <div class="palco-seats">
              <span class="seat-dot available"></span>
              <span class="seat-dot booked"></span>
              <span class="seat-dot available"></span>
            </div>
            <div class="palco-seats">
              <span class="seat-dot booked"></span>
              <span class="seat-dot available"></span>
              <span class="seat-dot booked"></span>
            </div>
          </div>

          <div class="area-label centrar" style="grid-column: 1 / -1;">PALCOS TRASEROS</div>

          <!-- Asientos Traseros -->
          <div style="grid-column: 1 / -1;">
            <div class="seat-row">
              <span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span>
            </div>
            <div class="seat-row">
              <span class="seat-dot booked"></span><span class="seat-dot booked"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot available"></span><span class="seat-dot booked"></span><span class="seat-dot booked"></span>
            </div>


          </div><!-- seat-map-container -->
        </div>


    </aside>

    <!-- ======= Columna Derecha ======= -->
    <section class="content-area">
      <div class="featured-function orange-bg">
        <div id="proxima-funcion-container" class="featured-content-wrapper">
          <span class="featured-label">Su Próxima Función</span>

          <!-- Este título será llenado por el JS -->
          <h2 class="featured-title"></h2>

          <!-- Aquí se insertarán fecha, hora y asientos -->
          <div class="featured-details"></div>

          <!-- Mensaje para cuando no hay función -->
          <p id="noEventsMessage" style="display:none; color:white; margin-top:8px;">
            No tienes funciones próximas.
          </p>
        </div>

      </div>


      <div class="billboard-header">
        <h3 class="section-title">Cartelera Actual</h3>
      </div>


      <div class="seccion-cartelera">
        <button class="nav-arrow nav-left" type="button" aria-label="Anterior">‹</button>
        <div class="billboard-list">
        </div>
        <button class="nav-arrow nav-right" type="button" aria-label="Siguiente">›</button>
      </div>
    </section>
  </main>
</div>


<!-- Modal detalles -->
<div id="modalDetalles" class="modal-detalles" style="display:none;">
  <div class="modal-contenido">
    <span id="cerrarModalDetalles" class="cerrar-modal">&times;</span>

    <h3 id="detalleTitulo"></h3>

    <p><strong>Fecha:</strong> <span id="detalleFecha"></span></p>
    <p><strong>Hora:</strong> <span id="detalleHora"></span></p>
    <p><strong>Asientos:</strong></p>
    <ul id="detalleAsientos"></ul>

  </div>
</div>

 <!-- Bot Asistente -->
<script src="https://cdn.botpress.cloud/webchat/v3.3/inject.js"></script>
<script src="https://files.bpcontent.cloud/2025/11/13/03/20251113033729-E31KFIHZ.js" defer></script>

<script src='./vista/js/Usuario/Dashboard_Usuario.js'></script> <!-- Lógica del dashboard de usuario -->