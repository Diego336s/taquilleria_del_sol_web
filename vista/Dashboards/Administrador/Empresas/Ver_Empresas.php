<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Empresas Registradas</title>
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
      font-size: 2rem;
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
      padding: 10px 16px;
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
      box-shadow: 0 5px 10px rgba(0, 255, 100, 0.5);
      margin-right: 5px;
    }

    .btn-edit:hover {
      background-color: #218838;
      transform: scale(1.05);
    }

    .btn-delete {
      background-color: #dc3545;
      color: #fff;
      box-shadow: 0 5px 10px rgba(255, 50, 50, 0.5);
    }

    .btn-delete:hover {
      background-color: #c82333;
      transform: scale(1.05);
    }

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
      transform: scale(1.05);
    }

    /* ✅ Botón Crear Empresa */
    .btn-create {
      display: inline-block;
      background-color: #ff6b1f;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      padding: 12px 22px;
      margin-bottom: 20px;
      float: right;
      cursor: pointer;
      transition: 0.3s ease;
      box-shadow: 0 5px 15px rgba(255, 107, 31, 0.5);
    }

    .btn-create:hover {
      background-color: #ff853d;
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

  <script src="../../../vista/js/ApiConexion.js"></script>

  <button class="btn btn-back" onclick="volverDashboard()">⬅️ Volver a Inicio</button>

  <div class="dashboard-container">
    <h1>Empresas Registradas</h1>

    <!-- ✅ Botón Crear Empresa -->
    <button class="btn-create" onclick="crearEmpresa()">➕ Crear Empresa</button>

    <table id="tabla-empresas">
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
      <tbody id="tbody-empresas">
        <tr><td colspan="8" class="loading">Cargando empresas...</td></tr>
      </tbody>
    </table>
  </div>

  <script>
    function volverDashboard() {
      window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
    }

    function crearEmpresa() {
      window.location.href = 'Crear_Empresa.php';
    }

    async function cargarEmpresas() {
      const tbody = document.getElementById('tbody-empresas');
      tbody.innerHTML = '<tr><td colspan="8" class="loading">Cargando empresas...</td></tr>';

      try {
        // 🔸 Datos simulados (hasta tener la API)
        const data = [
          {
            id: 1,
            nombre_empresa: "Teatro del Sol",
            nit: "900123456-7",
            representante_legal: "Carlos Pérez",
            documento_representante: "12345678",
            nombre_contacto: "Ana López",
            telefono: "3201234567",
            correo: "contacto@teatrodelsol.com"
          },
          {
            id: 2,
            nombre_empresa: "Eventos Boyacá",
            nit: "800987654-3",
            representante_legal: "Laura Gómez",
            documento_representante: "98765432",
            nombre_contacto: "Andrés Rojas",
            telefono: "3119876543",
            correo: "info@eventosboyaca.com"
          }
        ];

        tbody.innerHTML = "";

        if (data.length === 0) {
          tbody.innerHTML = "<tr><td colspan='8' class='loading'>No hay empresas registradas</td></tr>";
          return;
        }

        data.forEach(empresa => {
          const row = `
            <tr id="empresa-${empresa.id}">
              <td>${empresa.nombre_empresa}</td>
              <td>${empresa.nit}</td>
              <td>${empresa.representante_legal}</td>
              <td>${empresa.documento_representante}</td>
              <td>${empresa.nombre_contacto}</td>
              <td>${empresa.telefono}</td>
              <td>${empresa.correo}</td>
              <td>
                <button class="btn btn-edit" onclick="editarEmpresa(${empresa.id})">✏️ Editar</button>
                <button class="btn btn-delete" onclick="eliminarEmpresa(${empresa.id})">🗑️ Eliminar</button>
              </td>
            </tr>
          `;
          tbody.insertAdjacentHTML("beforeend", row);
        });
      } catch (error) {
        console.error(error);
        tbody.innerHTML = "<tr><td colspan='8' class='loading'>Error al cargar empresas</td></tr>";
      }
    }

    function editarEmpresa(id) {
      window.location.href = `Editar_Empresa.php?id=${id}`;
    }

    function eliminarEmpresa(id) {
      if (confirm("¿Estás segura de que deseas eliminar esta empresa?")) {
        alert("✅ Empresa eliminada correctamente (modo simulación)");
        cargarEmpresas();
      }
    }

    cargarEmpresas();
  </script>
</body>
</html>
