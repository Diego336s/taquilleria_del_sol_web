<!-- Enlazamos la hoja de estilos específica para este dashboard -->
<link rel="stylesheet" href="vista/css/empresa.css?v=1.0">

<!-- Usamos el mismo ID para heredar el fondo y estilos base -->
<div class="dashboard-container" id="dashboard-usuario-body">
  <header class="dashboard-header">
    <div class="user-greeting">
      <span class="icon-circle">⚙️</span>
      <span class="welcome-text">¡Bienvenido,<strong id="nombreAdmin"></strong></span>
    </div>
    <div class="header-actions">
            <a href="index.php?ruta=Configuracion_admin" class="btn btn-explore">
        <i class="fas fa-user-circle me-2"></i>Configuracion
      </a>
            <a href="index.php?ruta=mi_perfil_admin" class="btn btn-explore">
        <i class="fas fa-user-circle me-2"></i>Mi Perfil
      </a>
      <button class="btn btn-profile" onclick="window.location.href='/taquilleria_del_sol_web/vista/Dashboards/Administrador/Reportes.php'">
        <span class="icon-inline">📊</span> Reportes
      </button>

      <button class="btn btn-profile" id="btnLogoutEmpresa" onclick="confirmLogoutAdmin()">
        <span class="icon-inline">🚪</span> Cerrar Sesión
      </button>
    </div>
  </header>

  <main class="dashboard-main">

    <!-- ======= Columna Izquierda (Widgets de Empresa) ======= -->
    <aside class="summary-widgets">
      <div class="widget">
        <div class="widget-icon green-bg">👥</div>
        <div class="widget-content">
          <span class="widget-number">1,245</span>
          <span class="widget-title">Usuarios Activos</span>
          <div class="progress-bar-container">
            <div class="progress-bar-fill orange-bg" style="width: 70%;"></div>
          </div>
        </div>
      </div>

      <div class="widget">
        <div class="widget-icon">🏢</div>
        <div class="widget-content">
          <span class="widget-number">42</span>
          <span class="widget-title">Empresas Registradas</span>
          <button class="btn btn-confirm" onclick="window.location.href='/taquilleria_del_sol_web/vista/Dashboards/Administrador/Empresas/Ver_Empresas.php'">Gestionar</button>

        </div>
      </div>

      <div class="widget">
        <div class="widget-content">
          <span class="widget-title h3 centrar">Estado del sistema</span>
          <div class="header-actions">
            <li>🖥️ Servidores <span class="status-active">● </span></li>
            <li>💾 Base de Datos <span class="status-active">● </span></li>
            <li>💳 Pagos <span class="status-active">● </span></li>
          <button class="btn btn-confirm" onclick="window.location.href='index.php?ruta=estado_sistema'"> ⚙️ver Logs</button>

          </div>
        </div>
      </div>
    </aside>

    <!-- ======= Columna Derecha (Contenido de Empresa) ======= -->
    <section class="content-area">
      <div class="stats-grid">
        <div class="widget">
          <div class="widget-icon">💲</div>
          <div class="widget-content">
            <span class="widget-number">$2.4M</span>
            <span class="widget-title">Ingresos mes</span>
          </div>
        </div>
        <div class="widget">
          <div class="widget-icon">📈</div>
          <div class="widget-content">
            <span class="widget-number">156</span>
            <span class="widget-title">Funciones</span>
          </div>
        </div>
        <div class="widget">
          <div class="widget-icon">👁️</div>
          <div class="widget-content">
            <span class="widget-number">8,945</span>
            <span class="widget-title">Entradas vendidas </span>
          </div>
        </div>
        <div class="widget">
          <div class="widget-icon">📈</div>
          <div class="widget-content">
            <span class="widget-number">94%</span>
            <span class="widget-title">Ocupación</span>
          </div>
        </div>
      </div>

      <div class="billboard-header">
      </div>

      <div class="billboard-list br">

        <div class="widget recent-reservations-card">
          <div class="widget-header">
            <h3 class="section-title" style="margin: 0; color: #fff;">👥  Gestion de usuarios</h3>
          </div>

          <div class="reservation-list">
            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">Nuevo registro</span>
                <span class="item-title">Ultimas 24h</span> </div>
              <span class="status-badge status-confirmed">+23</span>
            </div>

            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">Usuarios Activos</span>
                <span class="item-title">Esta semana</span>

              </div>
              <span class="status-badge status-pending">892</span>
            </div>
          </div>

          <button class="btn btn-confirm btn-full-width orange-bg"
                  onclick="window.location.href='/taquilleria_del_sol_web/vista/Dashboards/Administrador/Usuarios/Ver_Usuarios.php'">
            👥 Ver Todos los Usuarios
          </button>
        </div>

         <div class="widget recent-reservations-card">
          <div class="widget-header">
            <h3 class="section-title" style="margin: 0; color: #fff;">⚙️Configuracion del sistema</h3>
          </div>

         <div class="reservation-list">
            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">Precios de Entradas</span>
                <span class="item-title">Última actualización</span> </div>
              <button class="btn btn-white btn-small">Editar</button>

            </div>

            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">Horarios de Función</span>
                <span class="item-title">Configurar</span>

              </div>
              <button class="btn btn-white btn-small">Editar</button>
            </div>
          </div>

          <button class="btn btn-confirm btn-full-width orange-bg" onclick="window.location.href='/taquilleria_del_sol_web/vista/Dashboards/Administrador/PanelConfiguración.php'">
            <span class="icon-inline"></span>⚙️ Panel de Configuración
          </button>
         </div>

         
      </div>

      <div class="widget recent-reservations-card">
          <div class="widget-header">
            
            <h3 class="section-title" style="margin: 0; color: #fff;">📌 Actividad Reciente del Sistema</h3>
            <button class="btn btn-confirm">Ver Todo</button>
          </div>

         <div class="reservation-list">
            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">Nueva empresa registrada: <b>“Corporación ABC”</b></span>
                <span class="item-title activity-time">Hace 15 minutos</span> </div>

            </div>

            <div class="reservation-item info">
              <div class="item-details">
                <span class="item-title">Backup automático completado exitosamente</span>
                <span class="item-title activity-time">Hace 1 hora</span>

              </div>
            </div>
            <div class="reservation-list">
            <div class="reservation-item">
              <div class="item-details">
                <span class="item-title">Actualización de precios aplicada </span>
                <span class="item-title activity-time">Hace 2 horas</span> </div>

            </div>
          </div>
          

         </div>
    </section>
  </main>
</div>