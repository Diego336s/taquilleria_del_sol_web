<link rel="stylesheet" href="vista/css/Empresa/reportes.css?v=1.1">
<div class="dashboard">
    <!-- Encabezado -->
    <header class="top-bar">
      <div class="left">
        <i class="bi bi-bar-chart-line-fill logo"></i>
        <div class="title-group">
          <h1>Reportes</h1>
          <p>Corporación ABC</p>
        </div>
      </div>
      <div class="right">
        <select>
          <option>Este Mes</option>
          <option>Últimos 3 Meses</option>
          <option>Este Año</option>
        </select>
        <button class="exportar"><i class="bi bi-download"></i> Exportar</button>
      </div>
    </header>

    <!-- Tarjetas principales -->
    <section class="stats">
      <div class="card total-invertido">
        <i class="bi bi-cash-coin"></i>
        <h2>$156,800</h2>
        <p>Total Invertido</p>
      </div>
      <div class="card">
        <i class="bi bi-calendar-event"></i>
        <h2>23</h2>
        <p>Eventos Asistidos</p>
      </div>
      <div class="card">
        <i class="bi bi-people"></i>
        <h2>456</h2>
        <p>Asistentes Totales</p>
      </div>
    </section>

    <main class="grid">
      <!-- Inversión reciente -->
      <div class="card largo">
        <h3>Inversión Reciente</h3>
        <div class="progress-group">
          <p>Abril <span>$28.600</span></p>
          <div class="bar"><div class="fill" style="width:90%"></div></div>
          <p>Mayo <span>$26.400</span></p>
          <div class="bar"><div class="fill" style="width:80%"></div></div>
          <p>Junio <span>$27.300</span></p>
          <div class="bar"><div class="fill" style="width:85%"></div></div>
        </div>
      </div>

      <!-- Próximos eventos -->
      <div class="card corto">
        <h3>Próximos Eventos</h3>
        <div class="evento">
          <div>
            <h4>Gala Anual</h4>
            <p><i class="bi bi-calendar"></i> 25 Feb</p>
          </div>
          <p><i class="bi bi-person"></i> 150</p>
        </div>
        <div class="evento">
          <div>
            <h4>Don Quijote</h4>
            <p><i class="bi bi-calendar"></i> 5 Mar</p>
          </div>
          <p><i class="bi bi-person"></i> 45</p>
        </div>
        <button class="btn-calendario">Ver Calendario</button>
      </div>

      <!-- Últimos eventos -->
      <div class="card largo">
        <h3>Últimos Eventos</h3>
        <div class="evento-row">
          <div>
            <h4>Romeo y Julieta</h4>
            <p>15 Jun 2025</p>
          </div>
          <p><i class="bi bi-person"></i> 45</p>
          <span>$6,750</span>
        </div>
        <div class="evento-row">
          <div>
            <h4>La Casa de Bernarda Alba</h4>
            <p>8 Jun 2025</p>
          </div>
          <p><i class="bi bi-person"></i> 38</p>
          <span>$5,700</span>
        </div>
        <div class="evento-row">
          <div>
            <h4>Hamlet</h4>
            <p>1 Jun 2025</p>
          </div>
          <p><i class="bi bi-person"></i> 52</p>
          <span>$7,800</span>
        </div>
      </div>

      <!-- Resumen de costos -->
      <div class="card corto">
        <h3>Resumen de Costos</h3>
        <div class="progress-group">
          <p>Entradas <span>$98,200</span></p>
          <div class="bar"><div class="fill" style="width:70%"></div></div>
          <p>Otros <span>$58,600</span></p>
          <div class="bar"><div class="fill" style="width:40%"></div></div>
        </div>
        <h4>Total <span class="total">$156,800</span></h4>
      </div>
    </main>
  </div>