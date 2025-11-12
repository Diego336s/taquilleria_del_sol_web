<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📊 Estado del Sistema</title>
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* === AJUSTES BÁSICOS DE ORDEN (NO CAMBIAN TU DISEÑO ORIGINAL) === */
    .dashboard-container {
      max-width: 1200px;
      margin: 50px auto;
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
      backdrop-filter: blur(10px);
      color: white;
    }

    h1, h2, h3 {
      text-align: center;
    }

    .metrics, .charts, .services, .activity {
      margin-top: 40px;
    }

    /* === MÉTRICAS === */
    .metrics {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 10px;
      text-align: center;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      transition: transform 0.2s;
    }

    .card:hover {
      transform: scale(1.05);
    }

    .card i {
      font-size: 40px;
      color: #ff6b1f;
      margin-bottom: 10px;
    }

    .card h3 {
      margin: 10px 0;
    }

    /* === GRÁFICOS === */
    .charts {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
      gap: 30px;
    }

    .chart-container {
      background: rgba(255,255,255,0.15);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }

    /* === SERVICIOS === */
    .service-list {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }

    .service-item {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(255,255,255,0.15);
      padding: 10px 20px;
      border-radius: 8px;
    }

    .status-dot {
      width: 15px;
      height: 15px;
      border-radius: 50%;
    }

    .status-active {
      background-color: #28a745;
    }

    /* === ACTIVIDAD === */
    .activity-list {
      background: rgba(255,255,255,0.1);
      padding: 20px;
      border-radius: 10px;
      max-height: 300px;
      overflow-y: auto;
    }

    .activity-item {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .activity-icon {
      font-size: 14px;
      margin-right: 10px;
    }

    .activity-content {
      flex: 1;
    }

    .refresh-btn {
      display: block;
      margin: 20px auto;
      padding: 10px 20px;
      background: #ff6b1f;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .refresh-btn:hover {
      background: #ff8533;
    }

    /* === BOTÓN VOLVER === */
    .btn-back {
      background: #ff6b1f;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
      margin: 20px;
    }

    .btn-back:hover {
      background: #ff8533;
    }

    /* === NOTIFICACIONES === */
    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 25px;
      border-radius: 8px;
      color: white;
      font-weight: bold;
      opacity: 0;
      transform: translateY(-20px);
      transition: opacity 0.3s, transform 0.3s;
      z-index: 9999;
    }

    .notification.show {
      opacity: 1;
      transform: translateY(0);
    }

    .notification-success {
      background-color: #28a745;
    }

    .notification-warning {
      background-color: #ffc107;
      color: black;
    }

    .notification-info {
      background-color: #17a2b8;
    }
  </style>
</head>

<body>
  <button class="btn-back" onclick="volverDashboard()">⬅️ Volver</button>

  <div class="dashboard-container">
    <h1>📊 Estado del Sistema</h1>

    <!-- === TARJETAS DE MÉTRICAS === -->
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

    <!-- === GRÁFICOS === -->
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

    <!-- === ESTADO DE SERVICIOS === -->
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

    <!-- === ACTIVIDAD RECIENTE === -->
    <div class="activity">
      <h2>Actividad Reciente</h2>
      <div id="activityList" class="activity-list"></div>
      <button class="refresh-btn" onclick="refreshSystemStatus()">🔄 Actualizar Estado</button>
    </div>
  </div>

  <script>
    // === DATOS DE EJEMPLO ===
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

    // === GRÁFICOS ===
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

    // 🔙 Volver al Dashboard
    function volverDashboard() {
      window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
    }

    window.onload = () => {
      refreshSystemStatus();
    };
  </script>
</body>
</html>
