<!-- Enlazamos la hoja de estilos específica para este dashboard -->
<link rel="stylesheet" href="vista/css/empresa.css?v=1.0">

<!-- Usamos el mismo ID para heredar el fondo y estilos base -->
<div class="dashboard-container" id="dashboard-usuario-body">
  <header class="dashboard-header">
    <div class="user-greeting">
      <span class="icon-circle">🏢</span>
      <span class="welcome-text">¡Bienvenido,<strong id="nombreEmpresa"></strong></span>
    </div>
    <div class="header-actions">
      <button class="btn btn-explore">
        <span class="icon-inline">📝</span> Nueva Reservasss
      </button>
      <button class="btn btn-profile">
        <span class="icon-inline">📈</span> Reportes
      </button>
      <button class="btn btn-profile">
        <span class="icon-inline">⚙️</span> Mi Perfil Empresa
      </button>
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
          <span class="widget-number">85</span>
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
            <button class="btn btn-confirm">Reservar función</button>
            <button class="btn btn-profile"><span class="icon-inline">🧾</span> Ver Calendario</button>
            <button class="btn btn-profile"><span class="icon-inline">⚙️</span> Facturación</button>
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
            <span class="widget-number">$156k</span>
            <span class="widget-title">Gastado este año</span>
          </div>
        </div>
        <div class="widget">
          <div class="widget-icon">🗓️</div>
          <div class="widget-content">
            <span class="widget-number">23</span>
            <span class="widget-title">Eventos Asistidos</span>
          </div>
        </div>
        <div class="widget">
          <div class="widget-icon">🪑</div>
          <div class="widget-content">
            <span class="widget-number">456</span>
            <span class="widget-title">Asientos Totales</span>
          </div>
        </div>
        <div class="widget">
          <div class="widget-icon">📈</div>
          <div class="widget-content">
            <span class="widget-number">+28%</span>
            <span class="widget-title">Crecimiento</span>
          </div>
        </div>
      </div>

      <div class="featured-function orange-bg">
        <span class="featured-label">Proximo Evento</span>
        <h2 class="featured-title">El Fantasma de la Ópera</h2>
        <div class="featured-details">
          <span class="detail-item">📈 95% Ocupación</span>
          <span class="detail-item">💰 $45M Ingresos</span>
          <span class="detail-item">👥 1.2k Asistentes</span>
        </div>
        <button class="btn btn-white-border">Ver Reporte Detallado</button>
      </div>

      <div class="billboard-header">
      </div>

      <div class="billboard-list br">

        <div class="widget recent-reservations-card">
          <div class="widget-header">
            <h3 class="section-title" style="margin: 0; color: #fff;">Reservas Recientes</h3>
          </div>

          <div class="reservation-list">
            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">Romeo y Julieta</span>
                <span class="item-date-time">15 Enero - 8:00 PM</span>
              </div>
              <span class="status-badge status-confirmed">Confirmada</span>
            </div>

            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">El Fantasma de la Ópera</span>
                <span class="item-date-time">20 Enero - 7:30 PM</span>
              </div>
              <span class="status-badge status-pending">Pendiente</span>
            </div>
          </div>

          <button class="btn btn-confirm btn-full-width orange-bg">
            <span class="icon-inline"></span> Ver Todas las Reservas
          </button>


        </div>

         <div class="widget recent-reservations-card">
          <div class="widget-header">
            <h3 class="section-title" style="margin: 0; color: #fff;">Análisis Corporativo</h3>
          </div>

          <div class="reservation-list">
            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">Costo promedio por evento</span>
              </div>
              <span class="status-badge status-count">$35.000</span>
            </div>

            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">Tasa de Asistencia</span>
              </div>
              <span class="status-badge status-count">92%</span>
            </div>
          </div>

          <button class="btn btn-confirm btn-full-width orange-bg">
            <span class="icon-inline"></span> Ver Reporte Completo
          </button>
        </div>

      </div>
    </section>
  </main>
</div>