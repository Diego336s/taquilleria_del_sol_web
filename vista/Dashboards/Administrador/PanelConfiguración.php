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
      max-width: 850px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    h1 { text-align: center; font-weight: 600; font-size: 28px; color: #fff; margin-bottom: 35px; }
    .form-group { margin-bottom: 22px; }
    label { display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 6px; font-size: 15px; }
    .form-control {
      width: 100%; padding: 10px 12px; border-radius: 8px;
      border: 1px solid rgba(255,255,255,0.25); background: rgba(255,255,255,0.15);
      color: #fff; font-size: 15px;
    }
    .btn { padding: 10px 18px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px; transition: all 0.3s ease; }
    .btn-success { background-color: #3aa76d; color: #fff; }
    .btn-success:hover { background-color: #329764; }
    .btn-back { position: fixed; top: 25px; left: 25px; background-color: #cda664; color: #fff; padding: 10px 16px; z-index: 999; }
    .btn-back:hover { background-color: #b89358; }
    .precios-container { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 15px; }
    .precio-card { flex: 1 1 30%; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 15px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.25); transition: all 0.3s ease; }
    .precio-card h3 { margin: 0; font-size: 18px; font-weight: 600; color: #ffd580; }
    .precio-card input[type="number"] { margin-top: 10px; width: 100%; text-align: center; font-size: 16px; font-weight: 600; border-radius: 8px; border: none; padding: 10px; color: #fff; background: rgba(255,255,255,0.15); }
    .precio-card input[disabled] { opacity: 0.5; cursor: not-allowed; }
    .toggle { margin-top: 10px; display:flex; align-items:center; justify-content:center; gap:6px; font-size:14px; }
    @media (max-width: 768px) {
      .precios-container { flex-direction: column; } .precio-card { width: 100%; }
    }
  </style>
</head>
<body>

<button class="btn btn-back" onclick="volverDashboard()">⬅️ Volver</button>

<div class="dashboard-container">
  <h1>⚙️ Configuración del Sistema</h1>

  <form id="config-form">
    
    <div class="form-group">
      <label>🏢 Empresa</label>
      <select id="empresa" class="form-control" onchange="cargarEventosPorEmpresa()">
        <option value="">Seleccione una empresa</option>
      </select>
    </div>

    <div class="form-group">
      <label>🎭 Evento</label>
      <select id="evento" class="form-control" onchange="cargarDatosEvento()">
        <option value="">Seleccione un evento</option>
      </select>
    </div>

    <div class="form-group">
      <label>📝 Título</label>
      <input type="text" id="titulo" class="form-control">
    </div>

    <div class="form-group">
      <label>📖 Descripción</label>
      <textarea id="descripcion" class="form-control"></textarea>
    </div>

    <div class="form-group">
      <label>📅 Fecha</label>
      <input type="date" id="fecha" class="form-control">
    </div>

    <div class="form-group">
      <label>🕗 Hora Inicio</label>
      <input type="time" id="hora_inicio" class="form-control">
    </div>

    <div class="form-group">
      <label>🕙 Hora Final</label>
      <input type="time" id="hora_final" class="form-control">
    </div>

    <div class="form-group">
      <label>💰 Precios por Piso</label>
      <div class="precios-container">
        <div class="precio-card">
          <h3>1️⃣ Primer Piso</h3>
          <input type="number" id="precio_piso1">
        </div>
        <div class="precio-card">
          <h3>2️⃣ Segundo Piso</h3>
          <input type="number" id="precio_piso2">
        </div>
        <div class="precio-card">
          <h3>3️⃣ Tercer Piso</h3>
          <input type="number" id="precio_piso3">
        </div>
      </div>
    </div>

    <button type="button" class="btn btn-success" onclick="guardarCambiosEvento()">💾 Guardar Cambios</button>
  </form>
</div>

<script>
const API = ApiConexion;

function volverDashboard() {
  window.location.href = "/taquilleria_del_sol_web/index.php?ruta=dashboard-admin";
}


// ===============================
//  CARGAR EMPRESAS
// ===============================
async function cargarEmpresas() {
  try {
    const res = await fetch(`${API}/listarEmpresas`);
    const data = await res.json();

    const lista = data.data || [];
    const select = document.getElementById("empresa");

    select.innerHTML = `<option value="">Seleccione una empresa</option>`;

    lista.forEach(e => {
      select.innerHTML += `<option value="${e.id}">${e.nombre_empresa}</option>`;
    });

  } catch (e) {
    console.error("Error cargando empresas:", e);
  }
}


// ===============================
//  CARGAR EVENTOS POR EMPRESA
// ===============================
async function cargarEventosPorEmpresa() {
  const empresaId = document.getElementById("empresa").value;
  const eventoSelect = document.getElementById("evento");

  if (!empresaId) {
    eventoSelect.innerHTML = `<option value="">Seleccione una empresa primero</option>`;
    return;
  }

  try {
    const res = await fetch(`${API}/eventosPorEmpresa/${empresaId}`);
    const json = await res.json();

    const eventos = json.eventos || [];

    eventoSelect.innerHTML = `<option value="">Seleccione un evento</option>`;

    eventos.forEach(ev => {
      eventoSelect.innerHTML += `<option value="${ev.id}">${ev.titulo}</option>`;
    });

  } catch (e) {
    console.error("Error cargando eventos:", e);
  }
}


// ===============================
//  CARGAR DATOS DEL EVENTO
// ===============================
async function cargarDatosEvento() {
  const eventoId = document.getElementById("evento").value;
  if (!eventoId) return;

  try {
    const res = await fetch(`${API}/verEvento/${eventoId}`);
    const json = await res.json();

    const ev = json.evento;

    document.getElementById("titulo").value = ev.titulo;
    document.getElementById("descripcion").value = ev.descripcion;
    document.getElementById("fecha").value = ev.fecha;
    document.getElementById("hora_inicio").value = ev.hora_inicio;
    document.getElementById("hora_final").value = ev.hora_final;

    document.getElementById("precio_piso1").value = ev.precio_piso1;
    document.getElementById("precio_piso2").value = ev.precio_piso2;
    document.getElementById("precio_piso3").value = ev.precio_piso3;


  } catch (e) {
    console.error("Error cargando datos del evento:", e);
  }
}


// ===============================
//  GUARDAR CAMBIOS
// ===============================
async function guardarCambiosEvento() {
  const eventoId = document.getElementById("evento").value;

  const payload = {
    titulo: document.getElementById("titulo").value,
    descripcion: document.getElementById("descripcion").value,
    fecha: document.getElementById("fecha").value,
    hora_inicio: document.getElementById("hora_inicio").value,
    hora_final: document.getElementById("hora_final").value,
    precio_piso1: document.getElementById("precio_piso1").value,
    precio_piso2: document.getElementById("precio_piso2").value,
    precio_piso3: document.getElementById("precio_piso3").value,
  };

  try {
    const res = await fetch(`${API}/actualizarEvento/${eventoId}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    const json = await res.json();

    if (json.success) {
      alert("Evento actualizado correctamente");
    } else {
      alert("No se pudo actualizar el evento");
    }

  } catch (e) {
    console.error("Error actualizando evento:", e);
  }
}


// ===============================
//  INICIALIZAR
// ===============================
document.addEventListener("DOMContentLoaded", () => {
  cargarEmpresas();
});

</script>

</body>
</html>
