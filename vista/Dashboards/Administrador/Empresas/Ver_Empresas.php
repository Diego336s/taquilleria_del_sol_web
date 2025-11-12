<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🏢 Empresas Registradas</title>
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <style>
    body {
      background-image: url('../../../css/img/fondo.png');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      color: #fff;
    }

    .dashboard-container {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.12);
      border-radius: 20px;
      padding: 30px;
      margin: 40px auto;
      width: 95%;
      max-width: 1200px;
      box-shadow: 0 10px 25px rgba(255, 107, 31, 0.6);
      position: relative;
    }

    /* === TITULO Y BUSCADOR === */
    .header-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 25px;
    }

    h1 {
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin: 0;
    }

    .search-box {
      position: relative;
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 30px;
      padding: 8px 14px;
      box-shadow: 0 0 8px rgba(255, 255, 255, 0.15);
      transition: 0.3s;
    }

    .search-box input {
      background: transparent;
      border: none;
      outline: none;
      color: #fff;
      padding: 6px 10px;
      font-size: 15px;
      width: 220px;
    }

    .search-box input::placeholder {
      color: #ddd;
    }

    .search-box i {
      color: #ffcc00;
      font-size: 18px;
      margin-right: 6px;
    }

    .search-box:hover {
      box-shadow: 0 0 12px rgba(255, 204, 0, 0.5);
      transform: scale(1.02);
    }

    .table-container {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
      border-radius: 15px;
      overflow: hidden;
      background-color: rgba(0, 0, 0, 0.4);
      color: #fff;
    }

    thead {
      background: linear-gradient(90deg, #ff6b1f, #ffcc00);
      color: #000;
      font-weight: bold;
      text-transform: uppercase;
    }

    th, td {
      padding: 12px 10px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    tbody tr:hover {
      background-color: rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
    }

    /* === BOTONES DE NAVEGACIÓN === */
    .btn-back, .btn-add {
      position: fixed;
      top: 25px;
      background-color: #9c4012e6;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 10px 16px;
      cursor: pointer;
      font-weight: bold;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
      transition: 0.3s;
      z-index: 999;
    }

    .btn-back { left: 25px; }
    .btn-add { right: 25px; background-color: #ff8c1a; }

    .btn-back:hover, .btn-add:hover {
      transform: scale(1.05);
    }

    /* === BOTONES DE ACCIÓN === */
    .btn-action {
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.95rem;
      font-weight: bold;
      margin: 2px;
      padding: 6px 12px;
      color: white;
      transition: all 0.3s ease;
    }

    .btn-editar {
      background-color: #ff8c1a;
      box-shadow: 0 4px 10px rgba(255, 140, 26, 0.4);
    }

    .btn-eliminar {
      background-color: #ff3b3b;
      box-shadow: 0 4px 10px rgba(255, 59, 59, 0.4);
    }

    .btn-editar:hover {
      background-color: #ffa447;
      transform: scale(1.05);
    }

    .btn-eliminar:hover {
      background-color: #ff5757;
      transform: scale(1.05);
    }

    @media (max-width: 768px) {
      .header-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
      }
      h1 { font-size: 1.4rem; }
      .search-box input { width: 100%; }
      th, td { padding: 8px; font-size: 0.9rem; }
      .btn-action { padding: 4px 8px; font-size: 0.8rem; }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <!-- 🔙 Botón volver -->
  <button class="btn-back" onclick="volverDashboard()">⬅️ Volver</button>

  <!-- ➕ Botón crear nueva empresa -->
  <button class="btn-add" onclick="window.location.href='Crear_Empresa.php'">➕ Nueva Empresa</button>

  <!-- 📦 Contenedor principal -->
  <div class="dashboard-container">
    <div class="header-section">
      <h1>🏢 Empresas Registradas</h1>

      <!-- 🔍 Buscador -->
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="buscador" placeholder="Buscar empresa...">
      </div>
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Nombre Empresa</th>
            <th>NIT</th>
            <th>Representante Legal</th>
            <th>Documento</th>
            <th>Nombre Contacto</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaEmpresas">
          <tr><td colspan="8">Cargando empresas...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="../../../js/ApiConexion.js"></script>
  <script src="../../../js/Admin/VerEmpresas.js"></script>
  <script>
    // 🔙 Volver al Dashboard
    function volverDashboard() {
      window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
    }

    // Inicializar la tabla con el ID correcto
    document.addEventListener("DOMContentLoaded", () => {
      const tbody = document.getElementById("tablaEmpresas");
      if (tbody) {
        tbody.id = "tbody-empresas"; // Coincidir con VerEmpresas.js
        ctrListarEmpresas();
      }

      // 🔍 Filtro en tiempo real
      const buscador = document.getElementById('buscador');
      buscador.addEventListener('keyup', () => {
        const filtro = buscador.value.toLowerCase();
        const filas = document.querySelectorAll('#tbody-empresas tr');

        filas.forEach(fila => {
          const textoFila = fila.textContent.toLowerCase();
          fila.style.display = textoFila.includes(filtro) ? '' : 'none';
        });
      });
    });
  </script>
</body>
</html>
