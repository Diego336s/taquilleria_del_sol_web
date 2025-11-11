<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>⚙️ Configuración del Sistema</title>
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
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
      max-width: 800px;
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

    .form-group {
      margin-bottom: 22px;
    }

    label {
      display: block;
      color: #e0e0e0;
      font-weight: 500;
      margin-bottom: 6px;
      font-size: 15px;
    }

    .form-control {
      width: 100%;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.25);
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      font-size: 15px;
    }

    .btn {
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      font-size: 15px;
      transition: all 0.3s ease;
    }

    .btn-success {
      background-color: #3aa76d;
      color: #fff;
    }

    .btn-success:hover {
      background-color: #329764;
    }

    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #cda664;
      color: #fff;
      padding: 10px 16px;
      z-index: 999;
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
  <button class="btn btn-back" onclick="volverDashboard()">⬅️ Volver</button>

  <div class="dashboard-container">
    <h1>⚙️ Configuración del Sistema</h1>

    <form id="config-form">
      <div class="form-group">
        <label for="empresa">🏢 Empresa</label>
        <select id="empresa" class="form-control" onchange="cargarFuncionesPorEmpresa()">
          <option value="">Seleccione una empresa</option>
        </select>
      </div>

      <div class="form-group">
        <label for="funcion">🎭 Función</label>
        <select id="funcion" class="form-control" onchange="cargarDatosFuncion()">
          <option value="">Seleccione una función</option>
        </select>
      </div>

      <div class="form-group">
        <label for="titulo">📝 Título</label>
        <input type="text" id="titulo" class="form-control">
      </div>

      <div class="form-group">
        <label for="descripcion">📖 Descripción</label>
        <textarea id="descripcion" class="form-control"></textarea>
      </div>

      <div class="form-group">
        <label for="fecha">📅 Fecha</label>
        <input type="date" id="fecha" class="form-control">
      </div>

      <div class="form-group">
        <label for="hora_inicio">🕗 Hora Inicio</label>
        <input type="time" id="hora_inicio" class="form-control">
      </div>

      <div class="form-group">
        <label for="hora_final">🕙 Hora Final</label>
        <input type="time" id="hora_final" class="form-control">
      </div>

      <div class="form-group">
        <label for="precio">💰 Precio</label>
        <input type="number" id="precio" class="form-control">
      </div>

      <div class="form-group">
        <label for="estado">🔒 Estado</label>
        <select id="estado" class="form-control">
          <option value="activo">🟢 Activo</option>
          <option value="inactivo">🔴 Inactivo</option>
        </select>
      </div>

      <button type="button" class="btn btn-success" onclick="guardarCambiosFuncion()">💾 Guardar Cambios</button>
    </form>
  </div>

  <script>
    const API_URL = "http://127.0.0.1:8000/api"; // Cambia si tu Laravel corre en otro puerto

    // 🔙 Volver
    function volverDashboard() {
      window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
    }

    // ✅ Cargar empresas desde la API
    async function cargarEmpresas() {
      const response = await fetch(`${API_URL}/listarEmpresas`);
      const data = await response.json();

      const select = document.getElementById('empresa');
      select.innerHTML = '<option value="">Seleccione una empresa</option>';

      if (data.success && data.data.length > 0) {
        data.data.forEach(emp => {
          const option = document.createElement('option');
          option.value = emp.id;
          option.textContent = emp.nombre_empresa;
          select.appendChild(option);
        });
      } else {
        alert("⚠️ No hay empresas registradas en el sistema.");
      }
    }

    // 🎭 Cargar funciones según la empresa seleccionada
    async function cargarFuncionesPorEmpresa() {
      const empresaId = document.getElementById('empresa').value;
      const funcionSelect = document.getElementById('funcion');
      funcionSelect.innerHTML = '<option value="">Cargando funciones...</option>';

      const response = await fetch(`${API_URL}/listarFuncionesPorEmpresa/${empresaId}`);
      const data = await response.json();

      funcionSelect.innerHTML = '<option value="">Seleccione una función</option>';
      if (data.success && data.data.length > 0) {
        data.data.forEach(funcion => {
          const option = document.createElement('option');
          option.value = funcion.id;
          option.textContent = funcion.titulo;
          funcionSelect.appendChild(option);
        });
      } else {
        funcionSelect.innerHTML = '<option value="">No hay funciones registradas</option>';
      }
    }

    // 🧾 Cargar los datos de una función
    async function cargarDatosFuncion() {
      const idFuncion = document.getElementById('funcion').value;
      if (!idFuncion) return;

      const response = await fetch(`${API_URL}/verFuncion/${idFuncion}`);
      const data = await response.json();

      if (data.success && data.data) {
        const f = data.data;
        document.getElementById('titulo').value = f.titulo;
        document.getElementById('descripcion').value = f.descripcion;
        document.getElementById('fecha').value = f.fecha;
        document.getElementById('hora_inicio').value = f.hora_inicio;
        document.getElementById('hora_final').value = f.hora_final;
        document.getElementById('precio').value = f.precio;
        document.getElementById('estado').value = f.estado;
      } else {
        alert("⚠️ Error al cargar los datos de la función.");
      }
    }

    // 💾 Guardar cambios de la función
    async function guardarCambiosFuncion() {
      const idFuncion = document.getElementById('funcion').value;
      const body = {
        titulo: document.getElementById('titulo').value,
        descripcion: document.getElementById('descripcion').value,
        fecha: document.getElementById('fecha').value,
        hora_inicio: document.getElementById('hora_inicio').value,
        hora_final: document.getElementById('hora_final').value,
        precio: document.getElementById('precio').value,
        estado: document.getElementById('estado').value
      };

      const response = await fetch(`${API_URL}/actualizarFuncion/${idFuncion}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body)
      });

      const data = await response.json();
      if (data.success) {
        alert("✅ Función actualizada correctamente.");
      } else {
        alert("⚠️ No se pudo actualizar la función.");
      }
    }

    // 🚀 Iniciar
    document.addEventListener("DOMContentLoaded", cargarEmpresas);
  </script>
</body>
</html>
