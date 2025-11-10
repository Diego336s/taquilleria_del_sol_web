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
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 30px;
      text-transform: uppercase;
      letter-spacing: 1px;
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

    /* Botones */
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

    .btn-action {
      border: none;
      background: none;
      cursor: pointer;
      font-size: 1rem;
      margin: 0 4px;
    }

    @media (max-width: 768px) {
      h1 { font-size: 1.4rem; }
      th, td { padding: 8px; font-size: 0.9rem; }
    }
  </style>
</head>

<body>
  <!-- 🔙 Botón volver -->
  <button class="btn-back" onclick="volverDashboard()">⬅️ Volver</button>
  <!-- ➕ Botón crear nueva empresa -->
 <button class="btn-add" onclick="window.location.href='Crear_Empresa.php'">➕ Nueva Empresa</button>

  <!-- 📦 Contenedor principal -->
  <div class="dashboard-container">
    <h1>🏢 Empresas Registradas</h1>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th>
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
          <tr><td colspan="9">Cargando empresas...</td></tr>
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
        tbody.id = "tbody-empresas"; // Cambiar ID para coincidir con VerEmpresas.js
        ctrListarEmpresas();
      }
    });
  </script>
</body>
</html>
