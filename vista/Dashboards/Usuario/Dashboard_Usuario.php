<!-- Enlazamos la hoja de estilos específica para este dashboard -->
<link rel="stylesheet" href="vista/css/usuario.css?v=1.0">

<div class="dashboard-container" id="dashboard-usuario-body">
  <header class="dashboard-header">
    <div class="user-greeting">
      <span class="icon-circle">👤</span>
      <span class="welcome-text">¡Bienvenido,<strong id="nombreUsuario"></strong></span>
    </div>
    <div class="header-actions">
      <button class="btn btn-explore">
        <span class="icon-inline">✨</span> Explorar Teatro
      </button>
      <button class="btn btn-profile">
        <span class="icon-inline">👤</span> Mi Perfil
      </button>
      <button class="btn btn-profile" id="btnLogout" onclick="confirmLogout()">
        <span class="icon-inline">🚪</span> Cerrar Sesión
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
          <div class="progress-bar-container">
            <div class="progress-bar-fill orange-bg" style="width: 75%;"></div>
          </div>
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
        <span class="featured-label">Su Próxima Función</span>
        <h2 class="featured-title">Don Juan Tenorio</h2>
        <div class="featured-details">
          <span class="detail-item">🗓️ 12 Enero</span>
          <span class="detail-item">🕒 8:30 PM</span>
          <span class="detail-item">📍 Palco A12, A13</span>
        </div>
        <button class="btn btn-white-border">Ver Detalles</button>
      </div>

      <div class="billboard-header">
        <h3 class="section-title">Cartelera Actual</h3>
        <a href="#" class="view-all-link">Ver Temporada Completa →</a>
      </div>

      <div class="billboard-list">

        <!-- === Tarjeta === -->
        <div class="billboard-card">
          <div class="card-image-container">
            <img src="https://wp.es.aleteia.org/wp-content/uploads/sites/7/2017/04/web-paint-romeo-juliet-dicksee-public-domain.jpg?resize=620,350&q=75" alt="Romeo y Julieta" class="card-image">
            <span class="genre-tag tag-drama">Drama Clásico</span>
            <span class="favorite-icon">🤍</span>
            <span class="popularity-badge">⭐ 95%</span>
          </div>
          <div class="card-content">
            <h4 class="card-title">Romeo y Julieta</h4>
            <p class="card-description">La historia de amor más famosa de todos los tiempos</p>
            <div class="card-meta">
              <span>🗓️ 15 Enero</span>
              <span>🕒 8:00 PM</span>
            </div>
            <div class="card-footer">
              <span class="popularity-text">Popularidad</span>
              <div class="progress-bar-container small"><div class="progress-bar-fill orange-bg" style="width: 95%;"></div></div>
            </div>
            <div class="card-booking">
              <span class="price">$45.000</span>
              <button class="btn btn-confirm">Reservar</button>
            </div>
          </div>
        </div>

        <div class="billboard-card">
          <div class="card-image-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSY7KuPBLCW9gVC1mVdfeAp9KjzMrBf-Tme1w&s" alt="Romeo y Julieta" class="card-image">
            <span class="genre-tag tag-drama">Drama Clásico</span>
            <span class="favorite-icon">🤍</span>
            <span class="popularity-badge">⭐ 95%</span>
          </div>
          <div class="card-content">
            <h4 class="card-title">El fantasma de la opera</h4>
            <p class="card-description"> La historia, un romance de terror ambientado en la Ópera de París, narra el amor obsesivo de un genio musical desfigurado enmascarado por una joven soprano llamada Christine Daaé. </p>
            <div class="card-meta">
              <span>🗓️ 5 Octubre</span>
              <span>🕒 8:00 PM</span>
            </div>
            <div class="card-footer">
              <span class="popularity-text">Popularidad</span>
              <div class="progress-bar-container small"><div class="progress-bar-fill orange-bg" style="width: 75%;"></div></div>
            </div>
            <div class="card-booking">
              <span class="price">$30.000</span>
              <button class="btn btn-confirm">Reservar</button>
            </div>
          </div>
        </div>

        <div class="billboard-card">
          <div class="card-image-container">
            <img src="https://kritilo.com/wp-content/uploads/2022/05/la-casa-de-bernarda-alba-foto-de-marcosgpunto.jpg" alt="Romeo y Julieta" class="card-image">
            <span class="genre-tag tag-drama">Drama Clásico</span>
            <span class="favorite-icon">🤍</span>
            <span class="popularity-badge">⭐ 95%</span>
          </div>
          <div class="card-content">
            <h4 class="card-title">La casa de Bernarda Alba</h4>
            <p class="card-description"> La casa de Bernarda Alba es una obra teatral en tres actos escrita en 1936 por Federico García Lorca.[1]​ No pudo estrenarse ni publicarse hasta 1945, en Buenos Aires. Gracias a la iniciativa de Margarita Xirgu. </p>
            <div class="card-meta">
              <span>🗓️ 5 Octubre</span>
              <span>🕒 8:00 PM</span>
            </div>
            <div class="card-footer">
              <span class="popularity-text">Popularidad</span>
              <div class="progress-bar-container small"><div class="progress-bar-fill orange-bg" style="width: 60%;"></div></div>
            </div>
            <div class="card-booking">
              <span class="price">$40.000</span>
              <button class="btn btn-confirm">Reservar</button>
            </div>
          </div>
        </div>

      </div>
    </section>
  </main>
</div>
