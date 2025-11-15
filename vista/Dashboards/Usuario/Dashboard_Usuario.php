<!-- Enlazamos la hoja de estilos específica para este dashboard -->
<link rel="stylesheet" href="vista/css/usuario.css?v=1.0">

<div class="dashboard-container" id="dashboard-usuario-body">
  <header class="dashboard-header">
    <div class="user-greeting">
      <span class="icon-circle">👤</span>
      <span class="welcome-text">¡Bienvenido,<strong id="nombreUsuario"></strong></span>
    </div>
    <div class="header-actions">
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
          <span class="widget-number">5</span>
          <span class="widget-title">Obras Vistas</span>
        </div>
      </div>

      <div class="widget">
        <div class="widget-icon">🕒</div>
        <div class="widget-content">
          <span class="widget-number">1</span>
          <span class="widget-title">Próxima Función</span>
          <button class="btn btn-confirm">Cancelar</button>
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
        <div class="featured-content-wrapper">
          <span class="featured-label">Su Próxima Función</span>
          <h2 class="featured-title">Don Juan Tenorio</h2>
          <div class="featured-details">
            <span class="detail-item"><i class="fas fa-calendar-alt me-2"></i>12 Enero</span>
            <span class="detail-item"><i class="fas fa-clock me-2"></i>8:30 PM</span>
            <span class="detail-item"><i class="fas fa-chair me-2"></i>Palco A12, A13</span>
          </div>
        </div>
        <a href="#" class="btn btn-white-border" id="btnVerDetalles">Ver Detalles</a>
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


<script src="https://cdn.botpress.cloud/webchat/v3.3/inject.js"></script>
<script src="https://files.bpcontent.cloud/2025/11/13/03/20251113033729-E31KFIHZ.js" defer></script>

<script src='./vista/js/Usuario/Dashboard_Usuario.js'></script> <!-- Lógica del dashboard de usuario -->