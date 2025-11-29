<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión Usuarios</title>
  <link rel="stylesheet" href="vista/css/admin.css?v=1.0">

  <!-- FontAwesome -->
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* === CORTINA OSCURA DE FONDO === */
    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: -1;
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
      margin-bottom: 50px;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 700;
    }

    .search-container {
      position: relative;
      top: 30px;
      right: 40px;
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 174, 66, 0.5);
      border-radius: 30px;
      padding: 6px 14px;
      box-shadow: 0 0 10px rgba(255, 140, 0, 0.4);
      margin-bottom: 50px;
    }

    .search-input {
      border: none;
      background: transparent;
      outline: none;
      color: #fff;
      font-size: 15px;
      padding: 8px;
      width: 200px;
    }

    .search-input::placeholder {
      color: #eee;
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

    th,
    td {
      padding: 12px 10px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    tbody tr:hover {
      background-color: rgba(255, 255, 255, 0.06);
    }

    .btn-back {
      background: #ff6b1f;
      color: #fff;
      font-weight: bold;
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin: 20px;
      box-shadow: 0 4px 12px rgba(255, 107, 31, 0.4);
      transition: all 0.3s ease;
      margin-bottom: 10px;
    }

    .btn-back:hover {
      background: #ff853f;
      box-shadow: 0 6px 16px rgba(255, 107, 31, 0.6);
      transform: translateY(-2px);
    }
    


    .btn {
      border: none;
      border-radius: 6px;
      padding: 6px 12px;
      cursor: pointer;
      font-weight: bold;
      transition: all .2s ease;
      color: #fff;
      min-width: 100px;
    }

    .btn-editar {
      background-color: #b25d2e;
      /* café claro / naranja quemado */
      box-shadow: 0 4px 10px rgba(178, 93, 46, 0.5);
    }

    .btn-editar:hover {
      background-color: #d87a45;
      transform: scale(1.05);
    }

    .btn-eliminar {
      background-color: #8b3a22;
      /* café rojizo */
      box-shadow: 0 4px 10px rgba(139, 58, 34, 0.4);
    }

    .btn-eliminar:hover {
      background-color: #a1482b;
      transform: scale(1.05);
    }

    .controles-tabla {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: -15px;
    }
  </style>
</head>

<body>


  <div class="dashboard-container">
    <div class="table-container">
      <div class="centrar" style="width: 100%; margin-bottom: 15px;">
        <h1 style="margin: 0;">Gestión de Usuarios</h1>
      </div>

      <div class="controles-tabla">
        <button class="btn-back" onclick="volverDashboard()">⬅ Volver al Dashboard</button>

        <!-- Buscador arriba a la derecha -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
          <div class="search-container">
            <input type="text" id="buscador" class="search-input" placeholder="Buscar usuario...">
            <i class="fas fa-search"></i>
          </div>
        </div>

      </div>



      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Documento</th>
            <th>Teléfono</th>
            <th>Fecha nacimiento</th>
            <th>Correo</th>
            <th>Sexo</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>

        <tbody id="tablaUsuarios">
          <tr>
            <td colspan="11">Cargando usuarios...</td>
          </tr>
        </tbody>

      </table>
    </div>
  </div>

  <script src="vista/js/ApiConexion.js"></script>

  <script>
    const API_BASE = ApiConexion;
    const LIST_URL = API_BASE + "listarClientes";

    function volverDashboard() {
      window.location.href = "index.php?ruta=dashboard-admin";
    }

    function normalizeUsersResponse(json) {
      if (!json) return [];
      if (Array.isArray(json)) return json;
      if (json.clientes && Array.isArray(json.clientes)) return json.clientes;
      return [];
    }

    async function cargarUsuarios() {
      const tbody = document.getElementById('tablaUsuarios');
      tbody.innerHTML = '<tr><td colspan="11">Cargando usuarios...</td></tr>';

      try {
        const res = await fetch(LIST_URL);
        const json = await res.json();

        const usuarios = normalizeUsersResponse(json);
        renderUsuarios(usuarios);

        document.getElementById("buscador")
          .addEventListener("input", e => filtrarUsuarios(usuarios, e.target.value));

      } catch (err) {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="11">Error al cargar usuarios.</td></tr>';
      }
    }

    function renderUsuarios(usuarios) {
      const tbody = document.getElementById('tablaUsuarios');

      if (!usuarios.length) {
        tbody.innerHTML = '<tr><td colspan="11">No hay usuarios registrados.</td></tr>';
        return;
      }

      tbody.innerHTML = usuarios.map(u => `
        <tr>
          <td>${u.nombre ?? ''}</td>
          <td>${u.apellido ?? ''}</td>
          <td>${u.documento ?? ''}</td>
          <td>${u.telefono ?? ''}</td>
          <td>${u.fecha_nacimiento ?? ''}</td>
          <td>${u.correo ?? ''}</td>
          <td>${u.sexo ?? ''}</td>
          <td>Cliente</td>
          <td>
            <button class="btn btn-editar" onclick="editarUsuario(${u.id})">
              <i class="fas fa-pencil-alt"></i> Editar
            </button>

          </td>
        </tr>
      `).join('');
    }

    function filtrarUsuarios(usuarios, texto) {
      texto = texto.toLowerCase();
      const filtrados = usuarios.filter(u =>
        (u.nombre ?? '').toLowerCase().includes(texto) ||
        (u.apellido ?? '').toLowerCase().includes(texto) ||
        (u.documento ?? '').toLowerCase().includes(texto) ||
        (u.telefono ?? '').toLowerCase().includes(texto) ||
        (u.correo ?? '').toLowerCase().includes(texto)
      );
      renderUsuarios(filtrados);
    }

    /* ✅ ESTA ES LA FUNCIÓN CORRECTA */
    function editarUsuario(id) {
      window.location.href = `index.php?ruta=Editar_Usuario&id=${id}`;
    }

    async function eliminarUsuario(id) {
      Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción eliminará al usuario permanentemente.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
      }).then(async (result) => {
        if (!result.isConfirmed) return;

        // Mostrar cargando
        Swal.fire({
          title: "Eliminando...",
          text: "Por favor espera",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });

        try {
          const res = await fetch(`${API_BASE}eliminarCliente/${id}`, {
            method: "DELETE"
          });

          const json = await res.json().catch(() => null);

          if (res.ok) {
            Swal.fire({
              icon: "success",
              title: "Usuario eliminado",
              showConfirmButton: false,
              timer: 1400
            }).then(() => cargarUsuarios());

          } else {
            Swal.fire("Error", json?.message || "No se pudo eliminar el usuario.", "error");
          }

        } catch (error) {
          Swal.fire("Error", "Error al conectar con el servidor", "error");
        }
      });
    }


    document.addEventListener('DOMContentLoaded', cargarUsuarios);
  </script>

</body>

</html>