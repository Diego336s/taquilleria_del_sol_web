<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>👥 Usuarios Registrados</title>
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

    /* 📦 Contenedor principal */
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

    /* 📋 Tabla */
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

    /* 🟢 Estado visual */
    .estado-activo {
      background-color: #28a745;
      color: #fff;
      border-radius: 20px;
      padding: 4px 12px;
      font-size: 0.85rem;
      font-weight: bold;
    }

    .estado-inactivo {
      background-color: #dc3545;
      color: #fff;
      border-radius: 20px;
      padding: 4px 12px;
      font-size: 0.85rem;
      font-weight: bold;
    }

    /* 🔙 Botón volver */
    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
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

    .btn-back:hover {
      transform: scale(1.05);
    }

    /* 📱 Responsivo */
    @media (max-width: 768px) {
      h1 {
        font-size: 1.4rem;
      }
      th, td {
        padding: 8px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>

<body>
  <!-- 🔙 Botón de volver -->
  <button class="btn-back" onclick="volverDashboard()">⬅️ Volver al Dashboard</button>

  <!-- 📦 Contenedor principal -->
  <div class="dashboard-container">
    <h1>👥 Usuarios Registrados</h1>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Documento</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody id="tablaUsuarios">
          <tr><td colspan="8">Cargando usuarios...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // URL de la API para listar los usuarios
    const API_URL = "http://127.0.0.1:8000/api/listarClientes";

    // Función para volver al Dashboard
    function volverDashboard() {
      window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
    }

    // Cargar los usuarios al iniciar la página
    document.addEventListener("DOMContentLoaded", async () => {
      const tbody = document.getElementById("tablaUsuarios");

      try {
        const response = await fetch(API_URL);
        if (!response.ok) throw new Error("Error al obtener usuarios");

        const data = await response.json();
        const usuarios = Array.isArray(data) ? data : data.data || [];

        if (usuarios.length === 0) {
          tbody.innerHTML = `<tr><td colspan="8">No hay usuarios registrados.</td></tr>`;
          return;
        }

        tbody.innerHTML = usuarios.map(u => `
          <tr>
            <td>${u.id || ""}</td>
            <td>${u.nombre || ""}</td>
            <td>${u.apellido || ""}</td>
            <td>${u.documento || ""}</td>
            <td>${u.telefono || ""}</td>
            <td>${u.correo || ""}</td>
            <td>${u.rol || "Cliente"}</td>
            <td>
              <span class="${u.estado === "Activo" ? "estado-activo" : "estado-inactivo"}">
                ${u.estado || "Activo"}
              </span>
            </td>
          </tr>
        `).join("");

      } catch (error) {
        console.error(error);
        tbody.innerHTML = `<tr><td colspan="8">Error al cargar los usuarios.</td></tr>`;
      }
    });
  </script>
</body>
</html>
