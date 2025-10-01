<!-- Enlazamos la hoja de estilos específica para este dashboard -->
<link rel="stylesheet" href="vista/css/empresa.css?v=1.0">

<!-- Usamos el mismo ID para heredar el fondo y estilos base -->
<div class="dashboard-container" id="dashboard-usuario-body">
  <header class="dashboard-header">
    <div class="user-greeting">
      <span class="icon-circle">🏢</span>
      <span class="welcome-text">¡Bienvenido, <strong>Gerente de Teatro XYZ</strong>! 👋</span>
    </div>
    <div class="header-actions">
      <button class="btn btn-explore">
        <span class="icon-inline">📈</span> Analíticas
      </button>
      <button class="btn btn-profile">
        <span class="icon-inline">⚙️</span> Configuración
      </button>
      <span class="icon-circle">📤</span>
    </div>
  </header>

  <main class="dashboard-main">

    <!-- ======= Columna Izquierda (Widgets de Empresa) ======= -->
    <aside class="summary-widgets">
      <div class="widget">
        <div class="widget-icon">💰</div>
        <div class="widget-content">
          <span class="widget-number">$85M</span>
          <span class="widget-title">Ingresos del Mes</span>
          <div class="progress-bar-container">
            <div class="progress-bar-fill orange-bg" style="width: 80%;"></div>
          </div>
        </div>
      </div>

      <div class="widget">
        <div class="widget-icon">🎭</div>
        <div class="widget-content">
          <span class="widget-number">4</span>
          <span class="widget-title">Funciones Activas</span>
          <button class="btn btn-confirm">Gestionar Funciones</button>
        </div>
      </div>

      <div class="widget">
        <div class="widget-icon">📊</div>
        <div class="widget-content">
          <span class="widget-number">72%</span>
          <span class="widget-title">Ocupación Promedio</span>
           <div class="progress-bar-container">
            <div class="progress-bar-fill orange-bg" style="width: 72%;"></div>
          </div>
        </div>
      </div>
    </aside>

    <!-- ======= Columna Derecha (Contenido de Empresa) ======= -->
    <section class="content-area">
      <div class="featured-function orange-bg">
        <span class="featured-label">Función más Vendida</span>
        <h2 class="featured-title">El Fantasma de la Ópera</h2>
        <div class="featured-details">
          <span class="detail-item">📈 95% Ocupación</span>
          <span class="detail-item">💰 $45M Ingresos</span>
          <span class="detail-item">👥 1.2k Asistentes</span>
        </div>
        <button class="btn btn-white-border">Ver Reporte Detallado</button>
      </div>

      <div class="billboard-header">
        <h3 class="section-title">Gestión de Cartelera</h3>
        <a href="#" class="view-all-link">Añadir Nueva Función +</a>
      </div>

      <div class="billboard-list">

        <!-- === Tarjeta de Función === -->
        <div class="billboard-card">
          <div class="card-image-container">
            <img src="https://wp.es.aleteia.org/wp-content/uploads/sites/7/2017/04/web-paint-romeo-juliet-dicksee-public-domain.jpg?resize=620,350&q=75" alt="Romeo y Julieta" class="card-image">
            <span class="genre-tag tag-drama">Drama Clásico</span>
          </div>
          <div class="card-content">
            <h4 class="card-title">Romeo y Julieta</h4>
            <p class="card-description">La historia de amor más famosa de todos los tiempos</p>
            <div class="card-meta">
              <span>🗓️ 15 Enero</span>
              <span>🕒 8:00 PM</span>
            </div>
            <div class="card-booking">
              <span class="price">Estado: Activa</span>
              <button class="btn btn-confirm">Editar</button>
            </div>
          </div>
        </div>

        <div class="billboard-card">
          <div class="card-image-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSY7KuPBLCW9gVC1mVdfeAp9KjzMrBf-Tme1w&s" alt="El Fantasma de la Ópera" class="card-image">
            <span class="genre-tag tag-drama">Musical</span>
          </div>
          <div class="card-content">
            <h4 class="card-title">El fantasma de la opera</h4>
            <p class="card-description">Un romance de terror ambientado en la Ópera de París.</p>
            <div class="card-meta">
              <span>🗓️ 5 Octubre</span>
              <span>🕒 8:00 PM</span>
            </div>
            <div class="card-booking">
              <span class="price">Estado: Activa</span>
              <button class="btn btn-confirm">Editar</button>
            </div>
          </div>
        </div>

        <div class="billboard-card">
          <div class="card-image-container">
            <img src="https://kritilo.com/wp-content/uploads/2022/05/la-casa-de-bernarda-alba-foto-de-marcosgpunto.jpg" alt="La Casa de Bernarda Alba" class="card-image">
            <span class="genre-tag tag-drama">Drama</span>
          </div>
          <div class="card-content">
            <h4 class="card-title">La casa de Bernarda Alba</h4>
            <p class="card-description">Obra teatral en tres actos escrita en 1936 por Federico García Lorca.</p>
            <div class="card-meta">
              <span>🗓️ 5 Octubre</span>
              <span>🕒 8:00 PM</span>
            </div>
            <div class="card-booking">
              <span class="price">Estado: Finalizada</span>
              <button class="btn btn-confirm">Ver Reporte</button>
            </div>
          </div>
        </div>

      </div>
    </section>
  </main>
</div>