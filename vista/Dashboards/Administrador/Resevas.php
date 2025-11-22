<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Revisar Evento - Administrador</title>

  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Flatpickr (calendario y tiempo) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

  <style>


    .page-container { width:92%; max-width:1100px; margin:48px auto; }
    h1 { text-align:center; font-weight:700; margin-bottom:10px; color:#fff; text-transform:uppercase; letter-spacing:0.6px; }
    .card {
      background: var(--card-bg);
      border-radius:12px; padding:18px; box-shadow:0 8px 30px rgba(0,0,0,0.55);
      border:1px solid rgba(255,255,255,0.04);
    }

    /* Layout */
    .grid { display:grid; gap:18px; grid-template-columns: 1fr 380px; align-items:start; }
    @media (max-width:1000px){ .grid { grid-template-columns: 1fr; } }

    /* Tabla limpia para info */
    .info-table {
      width:100%;
      border-collapse:collapse;
      margin-top:8px;
      background: rgba(255,255,255,0.03);
      border-radius:10px;
      overflow:hidden;
    }
    .info-table th {
      text-align:left;
      padding:12px 14px;
      background: linear-gradient(90deg, rgba(255,107,31,0.95), rgba(255,204,0,0.95));
      color:#000;
      font-weight:700;
      font-size:13px;
    }
    .info-table td {
      padding:12px 14px;
      border-top:1px solid rgba(255,255,255,0.03);
      color:#fff;
      font-size:14px;
    }

    .form-control {
      width:100%; padding:10px; margin-top:6px; border-radius:8px;
      border:1px solid rgba(255,255,255,0.08);
      background: rgba(0,0,0,0.46); color:#fff; box-sizing:border-box;
    }

    .imagen-preview { width:100%; max-height:260px; object-fit:cover; border-radius:8px; margin-top:8px; border:1px solid rgba(255,255,255,0.04); }

    .controls { margin-top:16px; display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
    .btn { padding:10px 16px; border:none; border-radius:8px; cursor:pointer; font-weight:700; }
    .btn-approve { background:var(--success); color:#fff; }
    .btn-confirm { background:var(--blue); color:#fff; }
    .btn-reject { background:var(--danger); color:#fff; }
    .btn-back { position:fixed; top:20px; left:20px; z-index:60; background-color:var(--accent); color:white; padding:8px 12px; border-radius:8px; border:none; cursor:pointer; font-weight:700; }

    .notice { color:#e9e9e9; font-size:13px; margin-top:6px; }

    /* Calendario inline y estilo de días ocupados */
    .calendar-wrap { margin-top:8px; background: rgba(0,0,0,0.18); padding:10px; border-radius:8px; border:1px solid rgba(255,255,255,0.03); }
    .fp-ocupado {
      background: #6b0000 !important;
      color: #fff !important;
      border-radius: 6px !important;
    }
    .ocupado-badge {
      display:inline-block;
      background: #8b0000;
      color:#fff;
      padding:6px 8px;
      border-radius:6px;
      margin:6px 6px 6px 0;
      font-size:13px;
    }

    .small { font-size:13px; color:#eee; }

    .right .section { margin-bottom:12px; }

    /* make flatpickr look better in dark bg */
    .flatpickr-calendar {
      background: rgba(0,0,0,0.9);
      color: #fff;
      border: none;
      box-shadow: 0 8px 30px rgba(0,0,0,0.7);
    }
    .flatpickr-weekday { color:#ddd; }
    .flatpickr-day.today { box-shadow: 0 0 0 2px rgba(255,255,255,0.06); }
    .flatpickr-day.disabled { opacity:0.5; }
  </style>
</head>
<body>
  <button class="btn btn-back" onclick="window.location.href='Solicitudes_Empresas.php'">⬅️ Volver</button>

  <div class="page-container">
    <h1>Revisar Solicitud de Evento</h1>

    <div class="card">
      <div class="grid">
        <!-- LEFT: Tabla de información + imagen + descripción -->
        <div class="left">
          <div id="contenidoEvento">
            <table class="info-table" id="tablaInfo">
              <thead>
                <tr><th colspan="2">Información de la Solicitud</th></tr>
              </thead>
              <tbody>
                <tr><td>Título</td><td id="infoTitulo">Cargando...</td></tr>
                <tr><td>Fecha propuesta</td><td id="infoFecha">—</td></tr>
                <tr><td>Hora inicio</td><td id="infoHi">—</td></tr>
                <tr><td>Hora final</td><td id="infoHf">—</td></tr>
                <tr><td>Estado</td><td id="infoEstado">—</td></tr>
                <tr><td>Empresa</td><td id="infoEmpresa">—</td></tr>
                <tr><td>Categoría</td><td id="infoCategoria">—</td></tr>
              </tbody>
            </table>
          </div>

          <div style="margin-top:12px;">
            <label>Descripción</label>
            <textarea id="descripcion" class="form-control" rows="4" disabled></textarea>
          </div>

          <div style="margin-top:12px;">
            <label>Imagen (subida por la empresa)</label>
            <img id="imgPreview" class="imagen-preview" src="" alt="Sin imagen">
          </div>

          <div style="margin-top:12px;">
            <div class="controls">
              <button id="btnAprobarConFecha" class="btn btn-approve" style="display:none;">✔️ Aprobar y asignar fecha/hora</button>
              <button id="btnAprobarSinFecha" class="btn btn-confirm" style="display:none;">✔️ Aprobar sin fecha</button>
              <button id="btnRechazar" class="btn btn-reject" style="display:none;">✖️ Rechazar</button>
              <div style="flex:1"></div>
              <div class="small">ID: <strong id="idSolicitud">—</strong></div>
            </div>
          </div>
        </div>

        <!-- RIGHT: Calendario + horas -->
        <div class="right">
          <div class="section">
            <label>Calendario</label>
            <div class="calendar-wrap">
              <!-- flatpickr inline -->
              <input id="calendar" class="form-control" placeholder="Selecciona una fecha" readonly>
              <div class="notice">Las fechas con eventos se muestran en rojo (ocupadas).</div>
            </div>
          </div>

          <div class="section">
            <label>Hora inicio</label>
            <input id="horaInicio" class="form-control" placeholder="HH:MM">
            <label style="margin-top:8px">Hora final</label>
            <input id="horaFinal" class="form-control" placeholder="HH:MM">
            <div class="small" style="margin-top:8px">Formato 24h, ejemplo 18:30</div>
          </div>

          <div class="section">
            <label>Horarios ocupados para la fecha seleccionada</label>
            <div id="ocupadosContainer" class="ocupado-list">
              <div class="small">Selecciona una fecha para ver horarios ocupados.</div>
            </div>
          </div>
        </div>
      </div> <!-- grid -->
    </div> <!-- card -->
  </div> <!-- container -->

<script>
(function () {
  // ---------- CONFIG ----------
  // EXACTO como pediste:
  const API_URL = (typeof ApiConexion !== 'undefined' ? String(ApiConexion) : "http://127.0.0.1:8000").replace(/\/$/, "") + "/api";
  const TOKEN = sessionStorage.getItem("userToken") || localStorage.getItem("userToken") || null;

  // endpoints
  const ENDPOINT_EVENTO = id => `${API_URL}/evento/${id}`;
  const ENDPOINT_LISTAR = `${API_URL}/listarEventos`;
  const ENDPOINT_CAMBIAR_ESTADO = id => `${API_URL}/cambiar/estado/evento/${id}`;

  // DOM
  const params = new URLSearchParams(window.location.search);
  const id = params.get('id');

  const infoTitulo = document.getElementById('infoTitulo');
  const infoFecha = document.getElementById('infoFecha');
  const infoHi = document.getElementById('infoHi');
  const infoHf = document.getElementById('infoHf');
  const infoEstado = document.getElementById('infoEstado');
  const infoEmpresa = document.getElementById('infoEmpresa');
  const infoCategoria = document.getElementById('infoCategoria');
  const descripcionEl = document.getElementById('descripcion');
  const imgPreview = document.getElementById('imgPreview');
  const idSolicitudEl = document.getElementById('idSolicitud');

  const btnAprobarConFecha = document.getElementById('btnAprobarConFecha');
  const btnAprobarSinFecha = document.getElementById('btnAprobarSinFecha');
  const btnRechazar = document.getElementById('btnRechazar');

  const calendarInput = document.getElementById('calendar');
  const horaInicio = document.getElementById('horaInicio');
  const horaFinal = document.getElementById('horaFinal');
  const ocupadosContainer = document.getElementById('ocupadosContainer');

  let evento = null;
  let allEventos = [];
  let flatpickrInstance = null;
  let fechasBloqueadas = [];
  let ocupadosPorFecha = {};

  // headers
  function headers(json=false){
    const h = {"Accept":"application/json"};
    if (TOKEN) h["Authorization"] = "Bearer " + TOKEN;
    if (json) h["Content-Type"] = "application/json";
    return h;
  }

  // safe parse
  function safeJson(response){
    return response.text().then(t => {
      try { return JSON.parse(t || "{}"); } catch(e) { return t || null; }
    });
  }

  function escapeHtml(s){ return String(s||"").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;"); }

  // Normalize eventos
  function normalizeEventos(raw){
    if (!raw) return [];
    if (Array.isArray(raw)) return raw;
    if (raw.data && Array.isArray(raw.data)) return raw.data;
    if (raw.eventos && Array.isArray(raw.eventos)) return raw.eventos;
    const arr = Object.values(raw).filter(v => v && typeof v === 'object' && (v.fecha || v.date || v.hora_inicio));
    return arr.length ? arr : [];
  }

  // calcula ocupados por fecha y lista de fechas bloqueadas
  function calcularOcupados(list){
    const mapa = {};
    const bloqueadas = new Set();
    for (const e of list){
      const fecha = (e.fecha || e.date || e.fecha_evento || "").split("T")[0];
      const hi = e.hora_inicio || e.hora_inicio_evento || e.hora || "";
      const hf = e.hora_final || e.hora_fin || e.hora_final_evento || "";
      if (!fecha) continue;
      if (!mapa[fecha]) mapa[fecha] = [];
      mapa[fecha].push({ hora_inicio: hi || null, hora_final: hf || null, titulo: e.titulo || "" });
      bloqueadas.add(fecha);
    }
    return { mapa, bloqueadas: Array.from(bloqueadas) };
  }

  // inicializa flatpickr en modo inline para que se vea siempre
  function initCalendar(disabledDates = []){
    if (flatpickrInstance) flatpickrInstance.destroy();
    // construir set para acceso rápido
    const disabledSet = new Set(disabledDates);
    flatpickrInstance = flatpickr(calendarInput, {
      locale: "es",
      inline: true,
      dateFormat: "Y-m-d",
      minDate: "today",
      disable: disabledDates,
      onChange(selectedDates, dateStr){
        // muestra horarios ocupados para esa fecha
        renderOcupadosFecha(dateStr);
      },
      onDayCreate: function(dObj, dStr, fpDay){
        // fpDay.dateObj -> Date
        try {
          const y = fpDay.dateObj.getFullYear();
          const m = String(fpDay.dateObj.getMonth()+1).padStart(2,"0");
          const d = String(fpDay.dateObj.getDate()).padStart(2,"0");
          const ds = `${y}-${m}-${d}`;
          if (disabledSet.has(ds)){
            fpDay.classList.add("fp-ocupado");
            fpDay.setAttribute("title","Fecha ocupada");
          }
        } catch(e){}
      }
    });
  }

  // render tabla info
  function renderEvento(e){
    evento = e || {};
    infoTitulo.textContent = evento.titulo || "—";
    infoFecha.textContent = evento.fecha ? String(evento.fecha).split("T")[0] : "—";
    infoHi.textContent = evento.hora_inicio || "—";
    infoHf.textContent = evento.hora_final || "—";
    infoEstado.textContent = evento.estado || "Pendiente";
    infoEmpresa.textContent = evento.empresa_id ? String(evento.empresa_id) : "—";
    infoCategoria.textContent = evento.categoria_id ? String(evento.categoria_id) : "—";
    descripcionEl.value = evento.descripcion || "";
    imgPreview.src = evento.imagen || "";
    imgPreview.alt = evento.imagen ? "Imagen evento" : "Sin imagen";
    idSolicitudEl.textContent = evento.id || id || "—";

    btnAprobarConFecha.style.display = "inline-block";
    btnAprobarSinFecha.style.display = "inline-block";
    btnRechazar.style.display = "inline-block";
  }

  // muestra horarios ocupados para fecha
  function renderOcupadosFecha(fecha){
    ocupadosContainer.innerHTML = "";
    if (!fecha) {
      ocupadosContainer.innerHTML = `<div class="small">Selecciona una fecha para ver horarios ocupados.</div>`;
      return;
    }
    const list = ocupadosPorFecha[fecha] || [];
    if (!list.length){
      ocupadosContainer.innerHTML = `<div class="small">No hay horarios ocupados para ${fecha}.</div>`;
      return;
    }
    for (const s of list){
      const hi = s.hora_inicio || "—";
      const hf = s.hora_final || "—";
      const el = document.createElement('span');
      el.className = "ocupado-badge";
      el.textContent = `${hi} — ${hf}` + (s.titulo ? ` • ${s.titulo}` : "");
      ocupadosContainer.appendChild(el);
    }
  }

  // helpers para solapamiento
  function toMinutes(t){
    const p = String(t).split(':');
    if (p.length < 2) return NaN;
    return parseInt(p[0],10)*60 + parseInt(p[1],10);
  }
  function timeOverlap(a1,a2,b1,b2){
    if (!a1||!a2||!b1||!b2) return false;
    const A1 = toMinutes(a1), A2 = toMinutes(a2), B1 = toMinutes(b1), B2 = toMinutes(b2);
    if ([A1,A2,B1,B2].some(isNaN)) return false;
    return (A1 < B2) && (B1 < A2);
  }
  function validarSeleccion(fecha, hi, hf){
    if (!fecha) return { ok:false, msg:"Selecciona una fecha." };
    if (!hi || !hf) return { ok:false, msg:"Ingresa hora inicio y hora final." };
    const A1 = toMinutes(hi), A2 = toMinutes(hf);
    if (isNaN(A1)||isNaN(A2)) return { ok:false, msg:"Formato de hora inválido (usa HH:MM)." };
    if (A1 >= A2) return { ok:false, msg:"La hora inicio debe ser anterior a la hora final." };
    const ocupados = ocupadosPorFecha[fecha] || [];
    for (const s of ocupados){
      if (!s.hora_inicio || !s.hora_final) continue;
      if (timeOverlap(hi, hf, s.hora_inicio, s.hora_final)){
        return { ok:false, msg:`Choque con horario ocupado ${s.hora_inicio} - ${s.hora_final}` };
      }
    }
    return { ok:true };
  }

  // FETCHers
  async function fetchEvento(id){
    try {
      const r = await fetch(ENDPOINT_EVENTO(id), { headers: headers() });
      if (!r.ok) throw new Error("HTTP " + r.status);
      const j = await safeJson(r);
      return j.data || j.evento || j || null;
    } catch (e){
      console.error("fetchEvento", e);
      return null;
    }
  }

  async function fetchAllEventos(){
    try {
      const r = await fetch(ENDPOINT_LISTAR, { headers: headers() });
      const j = await safeJson(r);
      return normalizeEventos(j);
    } catch(e){ console.error("fetchAllEventos", e); return []; }
  }

  // update state
  async function postCambiarEstado(idEvento, payload){
    try {
      const r = await fetch(ENDPOINT_CAMBIAR_ESTADO(idEvento), {
        method: "POST",
        headers: headers(true),
        body: JSON.stringify(payload)
      });
      const j = await safeJson(r);
      return { ok: r.ok, json: j, status: r.status };
    } catch(e){ console.error("postCambiarEstado", e); return { ok:false, error:e }; }
  }

  // handlers
  btnAprobarConFecha.addEventListener('click', async () => {
    const fecha = calendarInput.value;
    const hi = horaInicio.value.trim();
    const hf = horaFinal.value.trim();
    const v = validarSeleccion(fecha, hi, hf);
    if (!v.ok) return Swal.fire({ icon:'error', title:'Horario inválido', text: v.msg });

    const confirm = await Swal.fire({
      title: "Confirmar aprobación",
      html: `Aprobar y asignar fecha <strong>${fecha}</strong> ${hi} - ${hf}?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, aprobar",
      cancelButtonText: "Cancelar"
    });
    if (!confirm.isConfirmed) return;

    Swal.fire({ title:"Procesando...", didOpen:()=> Swal.showLoading(), allowOutsideClick:false });
    const payload = { estado: "Confirmada", fecha: fecha, hora_inicio: hi, hora_final: hf };
    const res = await postCambiarEstado(id, payload);
    Swal.close();
    if (res.ok) {
      Swal.fire("✅ Hecho", "Evento aprobado y fecha asignada.", "success").then(()=> window.location.href = "Solicitudes_Empresas.php");
    } else {
      console.error(res);
      Swal.fire("❌ Error", "No se pudo actualizar el evento. Revisa el servidor.", "error");
    }
  });

  btnAprobarSinFecha.addEventListener('click', async () => {
    const confirm = await Swal.fire({
      title: "Aprobar sin fecha",
      html: `Aprobar el evento sin asignar fecha/hora (estado: <strong>Confirmada</strong>)?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, aprobar",
      cancelButtonText: "Cancelar"
    });
    if (!confirm.isConfirmed) return;

    Swal.fire({ title:"Procesando...", didOpen:()=> Swal.showLoading(), allowOutsideClick:false });
    const payload = { estado: "Confirmada" };
    const res = await postCambiarEstado(id, payload);
    Swal.close();
    if (res.ok) {
      Swal.fire("✅ Hecho", "Evento aprobado.", "success").then(()=> window.location.href = "Solicitudes_Empresas.php");
    } else {
      console.error(res);
      Swal.fire("❌ Error", "No se pudo actualizar el evento. Revisa el servidor.", "error");
    }
  });

  btnRechazar.addEventListener('click', async () => {
    const { value: motivo } = await Swal.fire({
      title: "Rechazar evento",
      input: "textarea",
      inputLabel: "Motivo (opcional)",
      inputPlaceholder: "Escribe por qué se rechaza el evento",
      showCancelButton: true,
      confirmButtonText: "Rechazar",
      cancelButtonText: "Cancelar"
    });
    if (motivo === undefined) return;
    const ok = await Swal.fire({
      title: "Confirmar rechazo",
      html: `Rechazar el evento?${motivo ? "<br><small>Motivo: "+escapeHtml(motivo)+"</small>" : ""}`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, rechazar",
      cancelButtonText: "Cancelar"
    });
    if (!ok.isConfirmed) return;

    Swal.fire({ title:"Procesando...", didOpen:()=> Swal.showLoading(), allowOutsideClick:false });
    const payload = { estado: "Rechazada", motivo_rechazo: motivo || null };
    const res = await postCambiarEstado(id, payload);
    Swal.close();
    if (res.ok) {
      Swal.fire("✖️ Rechazado", "Evento rechazado correctamente.", "success").then(()=> window.location.href = "Solicitudes_Empresas.php");
    } else {
      console.error(res);
      Swal.fire("❌ Error", "No se pudo actualizar el evento. Revisa el servidor.", "error");
    }
  });

  // init
  async function init(){
    if (!id){
      document.getElementById('contenidoEvento').innerHTML = "<div style='color:#f88'>ID de solicitud no proporcionado en la URL.</div>";
      return;
    }

    // cargar evento y lista de eventos (para ocupados)
    const [e, all] = await Promise.all([fetchEvento(id), fetchAllEventos()]);
    if (!e) {
      document.getElementById('contenidoEvento').innerHTML = "<div style='color:#f88'>No se encontró la solicitud (revisa el endpoint /evento/{id}).</div>";
    } else renderEvento(e);

    allEventos = all || [];

    // normaliza
    const normalized = allEventos.map(it => ({
      id: it.id || it.evento_id || it.eventoId || null,
      fecha: it.fecha || it.date || it.fecha_evento || null,
      hora_inicio: it.hora_inicio || it.hora_inicio_evento || it.hora || null,
      hora_final: it.hora_final || it.hora_fin || it.hora_final_evento || null,
      titulo: it.titulo || it.nombre || ""
    }));

    const calc = calcularOcupados(normalized);
    ocupadosPorFecha = calc.mapa;
    fechasBloqueadas = calc.bloqueadas;

    // inicia calendario inline marcando fechas ocupadas
    initCalendar(fechasBloqueadas);

    // si la solicitud trae fecha, seleccionar
    const fechaExistente = e && (e.fecha || e.date || e.fecha_evento);
    if (fechaExistente) {
      const f = String(fechaExistente).split('T')[0];
      // forzar selección en flatpickr
      if (flatpickrInstance) {
        flatpickrInstance.setDate(f, true, "Y-m-d");
      } else calendarInput.value = f;
      renderOcupadosFecha(f);
    } else {
      renderOcupadosFecha(null);
    }
  }

  // arrancar
  document.addEventListener('DOMContentLoaded', init);

})(); // end IIFE
</script>

</body>
</html>
