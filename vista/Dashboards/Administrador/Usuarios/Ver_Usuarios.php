<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios Registrados</title>
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
    }

    .dashboard-container {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 30px;
      margin: 40px auto;
      width: 90%;
      max-width: 1200px;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 25px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 10px;
      overflow: hidden;
    }

    th, td {
      padding: 12px;
      text-align: center;
      color: #fff;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    th {
      background-color: #9c4012e6;
      color: white;
      font-weight: bold;
    }

    tr:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    .btn {
      display: inline-block;
      padding: 8px 14px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-edit {
      background-color: #28a745;
      color: #fff;
      box-shadow: 0 10px 20px rgba(0, 255, 100, 0.5);
      margin-right: 5px;
    }

    .btn-edit:hover {
      background-color: #218838;
      transform: scale(1.05);
    }

    .btn-delete {
      background-color: #dc3545;
      color: #fff;
      box-shadow: 0 10px 20px rgba(255, 50, 50, 0.5);
    }

    .btn-delete:hover {
      background-color: #c82333;
      transform: scale(1.05);
    }

    /* ✅ Botón fijo en la esquina */
    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #9c4012e6;
      color: #fff;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
      z-index: 999;
    }

    .btn-back:hover {
      background-color: #9c4012e6;
      transform: scale(1.05);
    }

    .loading {
      text-align: center;
      color: #fff;
      font-style: italic;
    }
  </style>
</head>
<body>

<!-- Incluir el archivo de configuración de API -->
<script src="../../../vista/js/ApiConexion.js"></script>

  <!-- Botón fijo arriba -->
  <button class="btn btn-back" onclick="volverDashboard()">
    ⬅️ Volver a Inicio
  </button>

<div class="dashboard-container">
    <h1> Usuarios Registrados</h1>
    

          <table id="tabla-usuarios" class="table table-striped table-hover">
            <thead class="table-dark">
              <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Documento</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Fecha de Nacimiento</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="tbody-usuarios">
              <tr><td colspan="7" class="loading text-center">Cargando usuarios...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>
</div>

  <script>
  // 🔗 Conexión a tu API Laravel (ngrok)
  const ApiConexion = "https://uncoachable-rosaline-lasciviously.ngrok-free.dev/api/";

  // 🔙 Volver al dashboard administrador
  function volverDashboard() {
    window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
  }

  // 👥 Cargar lista de usuarios desde Laravel
  async function cargarUsuarios() {
    const tbody = document.getElementById('tbody-usuarios');
    tbody.innerHTML = '<tr><td colspan="7" class="loading text-center">Cargando usuarios...</td></tr>';

    try {
      // ✅ Usar la variable ApiConexion
      const response = await fetch(`${ApiConexion}listarClientes`);

      if (!response.ok) throw new Error("Error al obtener los usuarios");

      const result = await response.json();
      const data = result.data || result;
      tbody.innerHTML = "";

      // ⚠️ Si no hay usuarios
      if (!data || data.length === 0) {
        tbody.innerHTML = "<tr><td colspan='7' class='loading text-center'>No hay usuarios registrados</td></tr>";
        return;
      }

      // 🧩 Mostrar usuarios
      data.forEach(user => {
        const row = `
          <tr id="usuario-${user.id}">
            <td>${user.nombre ?? '—'}</td>
            <td>${user.apellido ?? '—'}</td>
            <td>${user.documento ?? '—'}</td>
            <td>${user.telefono ?? '—'}</td>
            <td>${user.correo ?? '—'}</td>
            <td>${user.fecha_nacimiento ?? '—'}</td>
            <td>
              <button class="btn btn-edit" onclick="editarUsuario(${user.id})">✏️ Editar</button>
              <button class="btn btn-delete" onclick="eliminarUsuario(${user.id})">🗑️ Eliminar</button>
            </td>
          </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
      });
    } catch (error) {
      console.error("❌ Error cargando usuarios:", error);
      tbody.innerHTML = "<tr><td colspan='7' class='loading text-center'>Error al cargar usuarios</td></tr>";
    }
  }

  // ✏️ Redirigir a la vista de edición
  function editarUsuario(id) {
    window.location.href = `Editar_Usuario.php?id=${id}`;
  }

  // 🗑️ Eliminar usuario
  async function eliminarUsuario(id) {
    if (confirm("¿Estás segura de que deseas eliminar este usuario?")) {
      try {
        const response = await fetch(`${ApiConexion}eliminarCliente/${id}`, {
          method: 'DELETE',
        });

        if (!response.ok) throw new Error("Error al eliminar el usuario");

        alert("✅ Usuario eliminado correctamente");
        cargarUsuarios(); // Recargar la lista
      } catch (error) {
        console.error("❌ Error al eliminar:", error);
        alert("❌ Error al eliminar el usuario");
      }
    }
  }

  // 🚀 Ejecutar al cargar
  document.addEventListener("DOMContentLoaded", cargarUsuarios);
</script>

</body>
</html>
