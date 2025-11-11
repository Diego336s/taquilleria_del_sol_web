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
    h1 { text-align: center; color: #fff; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 1px; }
    .table-container { overflow-x: auto; }
    table {
      width: 100%; border-collapse: collapse; text-align: center; border-radius: 15px; overflow: hidden;
      background-color: rgba(0,0,0,0.4); color: #fff;
    }
    thead { background: linear-gradient(90deg, #ff6b1f, #ffcc00); color: #000; font-weight: bold; text-transform: uppercase; }
    th, td { padding: 12px 10px; border-bottom: 1px solid rgba(255,255,255,0.1); }
    tbody tr:hover { background-color: rgba(255,255,255,0.06); transition: all 0.2s ease; }
    .estado-activo { background-color: #28a745; color: #fff; border-radius: 20px; padding: 4px 12px; font-size: .85rem; font-weight: bold; }
    .estado-inactivo { background-color: #dc3545; color: #fff; border-radius: 20px; padding: 4px 12px; font-size: .85rem; font-weight: bold; }
    .btn-back { position: fixed; top:25px; left:25px; background-color:#9c4012e6; color:#fff; border:none; border-radius:8px; padding:10px 16px; cursor:pointer; font-weight:bold; box-shadow:0 10px 20px rgba(255,107,31,0.5); z-index:999; }
    .btn { border:none; border-radius:6px; padding:6px 12px; cursor:pointer; font-weight:bold; transition:all .2s ease; }
    .btn-editar { background:#ffc107; color:#000; }
    .btn-eliminar { background:#dc3545; color:#fff; }
    @media (max-width:768px){ th,td{padding:8px;font-size:.9rem;} h1{font-size:1.4rem;} }
  </style>
</head>
<body>
  <button class="btn-back" onclick="volverDashboard()">⬅️ Volver al Dashboard</button>

  <div class="dashboard-container">
    <h1>👥 Usuarios Registrados</h1>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th><th>Nombre</th><th>Apellido</th><th>Documento</th><th>Teléfono</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaUsuarios">
          <tr><td colspan="9">Cargando usuarios...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

<script>
  const API_BASE = "http://127.0.0.1:8000/api/";
  const LIST_URL = `${API_BASE}listarClientes`;

  function volverDashboard(){ window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin'; }

  // Normaliza respuesta: puede ser array, { data: [...] }, o { success:true, data: [...] }
  function normalizeUsersResponse(json) {
    if (!json) return [];
    if (Array.isArray(json)) return json;
    if (json.data && Array.isArray(json.data)) return json.data;
    // try possible alternative fields
    if (json.usuarios && Array.isArray(json.usuarios)) return json.usuarios;
    return [];
  }

  async function cargarUsuarios() {
    const tbody = document.getElementById('tablaUsuarios');
    tbody.innerHTML = `<tr><td colspan="9">Cargando usuarios...</td></tr>`;
    const token = sessionStorage.getItem('userToken');

    try {
      const headers = { "Content-Type": "application/json" };
      if (token) headers['Authorization'] = 'Bearer ' + token;

      const res = await fetch(LIST_URL, { method: 'GET', headers });
      if (!res.ok) {
        // try to parse error body for debugging
        let errText = await res.text().catch(()=>res.statusText);
        console.error('HTTP Error:', res.status, errText);
        tbody.innerHTML = `<tr><td colspan="9">Error al cargar los usuarios (HTTP ${res.status}).</td></tr>`;
        return;
      }

      const json = await res.json().catch(()=>null);
      const usuarios = normalizeUsersResponse(json);

      if (!usuarios.length) {
        tbody.innerHTML = `<tr><td colspan="9">No hay usuarios registrados.</td></tr>`;
        return;
      }

      const rows = usuarios.map(u => {
        const estado = (u.estado || '').toString();
        return `
          <tr id="usuario-${u.id}">
            <td>${u.id ?? ''}</td>
            <td>${u.nombre ?? ''}</td>
            <td>${u.apellido ?? ''}</td>
            <td>${u.documento ?? ''}</td>
            <td>${u.telefono ?? ''}</td>
            <td>${u.correo ?? ''}</td>
            <td>${u.rol ?? 'Cliente'}</td>
            <td><span class="${estado.toLowerCase()==='activo' ? 'estado-activo' : 'estado-inactivo'}">${u.estado ?? 'Activo'}</span></td>
            <td>
              <button class="btn btn-editar" onclick="editarUsuario(${u.id})">✏️ Editar</button>
              <button class="btn btn-eliminar" onclick="eliminarUsuario(${u.id})">🗑️ Eliminar</button>
            </td>
          </tr>`;
      }).join('');

      tbody.innerHTML = rows;

    } catch (err) {
      console.error('Fetch error:', err);
      document.getElementById('tablaUsuarios').innerHTML = `<tr><td colspan="9">Error al cargar los usuarios.</td></tr>`;
    }
  }

  function editarUsuario(id) {
    if (!id) return alert('ID inválido');
    window.location.href = `Editar_Usuario.php?id=${id}`;
  }

  async function eliminarUsuario(id) {
    if (!confirm('¿Seguro que deseas eliminar este usuario?')) return;
    const token = sessionStorage.getItem('userToken');

    try {
      const headers = {};
      if (token) headers['Authorization'] = 'Bearer ' + token;

      const res = await fetch(`${API_BASE}eliminarUsuario/${id}`, { method: 'DELETE', headers });
      const json = await res.json().catch(()=>null);

      if (res.ok && (json?.success || json?.message)) {
        alert('✅ Usuario eliminado correctamente.');
        cargarUsuarios();
      } else {
        console.error('Delete response', res.status, json);
        alert('⚠️ No se pudo eliminar el usuario.');
      }
    } catch (err) {
      console.error(err);
      alert('❌ Error al conectar con el servidor.');
    }
  }

  // Init
  document.addEventListener('DOMContentLoaded', cargarUsuarios);
</script>
</body>
</html>
