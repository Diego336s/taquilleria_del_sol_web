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
          <button class="btn btn-confirm">Confirmar</button>
        </div>
      </div>

      <div class="widget seat-selector-widget">
        <span class="widget-title">Teatro Principal</span>
        <div class="stage-label">ESCENARIO</div>
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
        <button class="btn btn-confirm">Seleccionar Sillas</button>
      </div>
    </aside>

    <!-- ======= Columna Derecha ======= -->
    <section class="content-area">
      <div class="featured-function orange-bg">
        <div class="featured-content-wrapper">
          <span class="featured-label">Su Próxima Función</span>
          <h2 class="featured-title">Don Juan Tenorio</h2>
          <div class="featured-details">
            <span class="detail-item">🗓️ 12 Enero</span>
            <span class="detail-item">🕒 8:30 PM</span>
            <span class="detail-item">📍 Palco A12, A13</span>
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

<script src='./vista/js/Usuario/Dashboard_Usuario.js'></script> <!-- Lógica del dashboard de usuario -->