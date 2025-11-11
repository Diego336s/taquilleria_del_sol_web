<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📊 Estado del Sistema</title>
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-image: url('../../css/img/fondo.png');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      color: #fff;
    }

    .dashboard-container {
      backdrop-filter: blur(12px);
      background: rgba(255, 255, 255, 0.08);
      border-radius: 16px;
      padding: 40px;
      margin: 60px auto;
      width: 90%;
      max-width: 1200px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    h1 {
      text-align: center;
      font-weight: 600;
      font-size: 28px;
      color: #fff;
      margin-bottom: 35px;
      letter-spacing: 0.5px;
    }

    /* === Tarjetas métricas === */
    .metrics {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }

    .card {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: 1px solid rgba(255,255,255,0.2);
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 35px rgba(0,0,0,0.4);
    }

    .card i {
      font-size: 2rem;
      color: #ff6b1f;
      margin-bottom: 10px;
    }

    .card h3 {
      font-size: 1rem;
      margin: 5px 0;
      color: #e0e0e0;
    }

    .card p {
      font-size: 1.6rem;
      font-weight: bold;
      color: #fff;
    }

    /* === Gráficos === */
    .charts {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 25px;
      margin-bottom: 40px;
    }

    .chart-container {
      position: relative;
      height: 300px;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      padding: 20px;
      border: 1px solid rgba(255,255,255,0.2);
    }

    .chart-container h2 {
      font-size: 1.1rem;
      margin-bottom: 10px;
      text-align: center;
      color: #ffb47f;
    }

    /* === Servicios === */
    .services h2, .activity h2 {
      text-align: center;
      color: #ffb47f;
      margin-bottom: 20px;
    }

    .service-list {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 40px;
    }

    .service-item {
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      width: 180px;
      border: 1px solid rgba(255,255,255,0.2);
    }

    .status-dot {
      display: inline-block;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      margin-right: 8px;
    }

    .status-active { background-color: #28a745; }
    .status-warning { background-color: #ffc107; }
    .status-error { background-color: #dc3545; }

    /* === Actividad reciente === */
    .activity-list {
      max-height: 250px;
      overflow-y: auto;
      padding-right: 10px;
      margin-bottom: 20px;
    }

    .activity-item {
      display: flex;
      align-items: center;
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      padding: 10px;
      margin-bottom: 10px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: 1px solid rgba(255,255,255,0.2);
    }

    .activity-item:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    }

    .activity-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 12px;
    }

    .activity-icon.success { background-color: rgba(40, 167, 69, 0.8); }
    .activity-icon.warning { background-color: rgba(255, 193, 7, 0.8); }
    .activity-icon.info { background-color: rgba(0, 123, 255, 0.8); }

    .activity-title { font-weight: 600; color: #fff; }
    .activity-time { font-size: 0.85rem; color: #ccc; }

    .refresh-btn {
      display: block;
      margin: 0 auto;
      padding: 10px 20px;
      border-radius: 8px;
      background-color: #3aa76d;
      border: none;
      color: #fff;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .refresh-btn:hover {
      background-color: #329764;
    }

    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: rgba(40, 167, 69, 0.9);
      color: #fff;
      padding: 10px 20px;
      border-radius: 10px;
      opacity: 0;
      transform: translateY(-10px);
      transition: 0.3s;
      z-index: 999;
    }

    .notification.show {
      opacity: 1;
      transform: translateY(0);
    }

    .notification-success { background-color: rgba(40, 167, 69, 0.9); }
    .notification-error { background-color: rgba(220, 53, 69, 0.9); }

    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #cda664;
      color: #fff;
      padding: 10px 16px;
      border-radius: 8px;
      z-index: 999;
      font-weight: 600;
    }

    .btn-back:hover {
      background-color: #b89358;
    }

    @media (max-width: 600px) {
      .dashboard-container { padding: 25px; }
      h1 { font-size: 22px; }
    }
  </style>
</head>
<body>

  <button class="btn-back" onclick="volverDashboard()">⬅️ Volver</button>

  <div class="dashboard-container">
    <h1>📊 Estado del Sistema</h1>

    <!-- Tarjetas de Métricas -->
    <div class="metrics">
      <div class="card">
        <i class="fas fa-users"></i>
        <h3>Usuarios Activos</h3>
        <p id="activeUsers">0</p>
      </div>
      <div class="card">
        <i class="fas fa-building"></i>
        <h3>Empresas Registradas</h3>
        <p id="companies">0</p>
      </div>
      <div class="card">
        <i class="fas fa-ticket-alt"></i>
        <h3>Tickets Vendidos</h3>
        <p id="ticketsSold">0</p>
      </div>
      <div class="card">
        <i class="fas fa-microchip"></i>
        <h3>Carga del Sistema</h3>
        <p id="systemLoad">0%</p>
      </div>
    </div>

    <!-- Gráficos -->
    <div class="charts">
      <div class="chart-container">
        <h2>Actividad de Usuarios</h2>
        <canvas id="userActivityChart"></canvas>
      </div>
      <div class="chart-container">
        <h2>Recursos del Servidor</h2>
        <canvas id="serverResourcesChart"></canvas>
      </div>
      <div class="chart-container">
        <h2>Ventas Mensuales</h2>
        <canvas id="salesChart"></canvas>
      </div>
      <div class="chart-container">
        <h2>Distribución de Eventos</h2>
        <canvas id="eventsChart"></canvas>
      </div>
    </div>

    <!-- Servicios -->
    <div class="services">
      <h2>Estado de Servicios</h2>
      <div class="service-list">
        <div class="service-item">
          <div class="status-dot status-active" id="serverStatus"></div>
          <span>Servidor</span>
        </div>
        <div class="service-item">
          <div class="status-dot status-active" id="dbStatus"></div>
          <span>Base de Datos</span>
        </div>
        <div class="service-item">
          <div class="status-dot status-active" id="paymentStatus"></div>
          <span>Pagos</span>
        </div>
        <div class="service-item">
          <div class="status-dot status-active" id="securityStatus"></div>
          <span>Seguridad</span>
        </div>
      </div>
    </div>

    <!-- Actividad -->
    <div class="activity">
      <h2>Actividad Reciente</h2>
      <div id="activityList" class="activity-list"></div>
      <button class="refresh-btn" onclick="refreshSystemStatus()">🔄 Actualizar Estado</button>
    </div>
  </div>

  <script>
    // === Datos de ejemplo ===
    const exampleMetrics = {
      activeUsers: 125,
      companies: 23,
      ticketsSold: 342,
      systemLoad: 67
    };

    const exampleActivities = [
      { type: 'success', title: 'Usuario inició sesión', time: '10:15 AM' },
      { type: 'warning', title: 'Carga del servidor elevada', time: '10:20 AM' },
      { type: 'info', title: 'Nueva empresa registrada', time: '10:25 AM' }
    ];

    function updateMetrics() {
      document.getElementById('activeUsers').innerText = exampleMetrics.activeUsers;
      document.getElementById('companies').innerText = exampleMetrics.companies;
      document.getElementById('ticketsSold').innerText = exampleMetrics.ticketsSold;
      document.getElementById('systemLoad').innerText = exampleMetrics.systemLoad + '%';
    }

    function updateActivities() {
      const activityList = document.getElementById('activityList');
      activityList.innerHTML = '';
      exampleActivities.forEach(act => {
        const div = document.createElement('div');
        div.className = 'activity-item';
        div.innerHTML = `
          <div class="activity-icon ${act.type}"><i class="fas fa-circle"></i></div>
          <div class="activity-content">
            <div class="activity-title">${act.title}</div>
            <div class="activity-time">${act.time}</div>
          </div>
        `;
        activityList.appendChild(div);
      });
    }

    // === Gráficos ===
    const userActivityChart = new Chart(document.getElementById('userActivityChart'), {
      type: 'line',
      data: {
        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
        datasets: [{
          label: 'Usuarios Activos',
          data: [50, 75, 125, 100, 150, 120, 130],
          backgroundColor: 'rgba(255, 107, 31, 0.3)',
          borderColor: '#ff6b1f',
          borderWidth: 2,
          fill: true
        }]
      },
      options: { responsive: true }
    });

    const serverResourcesChart = new Chart(document.getElementById('serverResourcesChart'), {
      type: 'bar',
      data: {
        labels: ['CPU', 'RAM', 'Disco'],
        datasets: [{
          label: 'Uso %',
          data: [67, 45, 80],
          backgroundColor: ['#ff6b1f','#ffb47f','#ffc107']
        }]
      },
      options: { responsive: true, scales: { y: { beginAtZero: true, max: 100 } } }
    });

    const salesChart = new Chart(document.getElementById('salesChart'), {
      type: 'line',
      data: {
        labels: ['Ene','Feb','Mar','Abr','May','Jun'],
        datasets: [{
          label: 'Ventas',
          data: [50,75,100,80,120,90],
          backgroundColor: 'rgba(40, 167, 69, 0.3)',
          borderColor: '#28a745',
          borderWidth: 2,
          fill: true
        }]
      },
      options: { responsive: true }
    });

    const eventsChart = new Chart(document.getElementById('eventsChart'), {
      type: 'doughnut',
      data: {
        labels: ['Conciertos','Deportes','Teatro'],
        datasets: [{
          label: 'Eventos',
          data: [12, 19, 7],
          backgroundColor: ['#ff6b1f','#ffc107','#28a745']
        }]
      },
      options: { responsive: true }
    });

    function refreshSystemStatus() {
      updateMetrics();
      updateActivities();
      showNotification('Estado del sistema actualizado', 'success');
    }

    function showNotification(message, type) {
      const notif = document.createElement('div');
      notif.className = `notification notification-${type} show`;
      notif.innerText = message;
      document.body.appendChild(notif);
      setTimeout(() => notif.remove(), 3000);
    }

    // 🔙 Volver a Dashboard
    function volverDashboard() {
      window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
    }

    window.onload = () => {
      refreshSystemStatus();
    };
  </script>
</body>
</html>
