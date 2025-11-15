<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>👥 Usuarios Registrados</title>
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">

  <!-- ✅ FontAwesome sin errores CORS -->
  <link rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 50px;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 700;
    }

    .search-container {
      position: absolute;
      top: 30px;
      right: 40px;
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 174, 66, 0.5);
      border-radius: 30px;
      padding: 6px 14px;
      box-shadow: 0 0 10px rgba(255, 140, 0, 0.4);
      transition: all 0.3s ease;
    }

    .search-container:hover {
      box-shadow: 0 0 20px rgba(255, 174, 66, 0.8);
      background: rgba(255, 255, 255, 0.25);
      transform: scale(1.02);
    }

    .search-input {
      border: none;
      background: transparent;
      outline: none;
      color: #fff;
      font-size: 15px;
      padding: 8px;
      width: 200px;
      transition: width 0.4s ease;
    }

    .search-input::placeholder {
      color: #eee;
      font-style: italic;
    }

    .search-container i {
      color: #ffb347;
      font-size: 18px;
      margin-left: 8px;
      transition: transform 0.3s ease;
    }

    .table-container { overflow-x: auto; }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
      border-radius: 15px;
      overflow: hidden;
      background-color: rgba(0,0,0,0.4);
      color: #fff;
    }

    thead {
      background: linear-gradient(90deg, #ff6b1f, #ffcc00);
      color: #000;
      font-weight: bold;
      text-transform: uppercase;
    }

    th, td { padding: 12px 10px; border-bottom: 1px solid rgba(255,255,255,0.1); }

    tbody tr:hover {
      background-color: rgba(255,255,255,0.06);
      transition: all 0.2s ease;
    }

    .btn-back {
      position: fixed;
      top:25px;
      left:25px;
      background-color:#9c4012e6;
      color:#fff;
      border:none;
      border-radius:8px;
      padding:10px 16px;
      cursor:pointer;
      font-weight:bold;
      box-shadow:0 10px 20px rgba(255,107,31,0.5);
      z-index:999;
    }

    .btn {
      border:none;
      border-radius:6px;
      padding:6px 12px;
      cursor:pointer;
      font-weight:bold;
      transition:all .2s ease;
    }

    .btn-editar { background:#ffc107; color:#000; }
    .btn-eliminar { background:#dc3545; color:#fff; }
  </style>
</head>

<body>
  <button class="btn-back" onclick="volverDashboard()">⬅️ Volver al Dashboard</button>

  <div class="dashboard-container">
    <h1>👥 Usuarios Registrados</h1>

    <div class="search-container">
      <input type="text" id="buscador" class="search-input" placeholder="Buscar usuario...">
      <i class="fas fa-search"></i>
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Nombre</th><th>Apellido</th><th>Documento</th><th>Teléfono</th>
            <th>Fecha nacimiento</th><th>Correo</th><th>Sexo</th><th>Rol</th><th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaUsuarios">
          <tr><td colspan="11">Cargando usuarios...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

<script>
const API_BASE = "http://127.0.0.1:8000/api/";
const LIST_URL = `${API_BASE}listarClientes`;

function volverDashboard(){
  window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
}

function normalizeUsersResponse(json) {
  if (!json) return [];
  if (Array.isArray(json)) return json;
  if (json.clientes && Array.isArray(json.clientes)) return json.clientes;
  return [];
}

async function cargarUsuarios() {
  const tbody = document.getElementById('tablaUsuarios');
  tbody.innerHTML = `<tr><td colspan="11">Cargando usuarios...</td></tr>`;

  try {
    const res = await fetch(LIST_URL);
    const json = await res.json();

    const usuarios = normalizeUsersResponse(json);
    renderUsuarios(usuarios);

    document.getElementById("buscador")
      .addEventListener("input", e => filtrarUsuarios(usuarios, e.target.value));

  } catch (err) {
    console.error(err);
    tbody.innerHTML = `<tr><td colspan="11">Error al cargar usuarios.</td></tr>`;
  }
}

function renderUsuarios(usuarios) {
  const tbody = document.getElementById('tablaUsuarios');

  if (!usuarios.length) {
    tbody.innerHTML = `<tr><td colspan="11">No hay usuarios registrados.</td></tr>`;
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
        <button class="btn btn-editar" onclick="editarUsuario(${u.id})">✏️ Editar</button>
        <button class="btn btn-eliminar" onclick="eliminarUsuario(${u.id})">🗑️ Eliminar</button>
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

function editarUsuario(id) {
  window.location.href = `Editar_Usuario.php?id=${id}`;
}

async function eliminarUsuario(id) {
  if (!confirm("¿Eliminar usuario?")) return;
  await fetch(`${API_BASE}eliminarCliente/${id}`, { method: "DELETE" });
  cargarUsuarios();
}

document.addEventListener('DOMContentLoaded', cargarUsuarios);
</script>

</body>
</html>
