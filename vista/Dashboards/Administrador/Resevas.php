<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>✔️ Revisar Solicitud de Empresa — Aprobar / Rechazar Reserva</title>

  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Flatpickr (calendario) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

  <style>

    .page-container {
      width: 92%;
      max-width: 980px;
      margin: 48px auto;
    }

    h1 {
      text-align:center;
      font-weight:600;
      margin-bottom:6px;
      color:#fff;
      text-shadow: 0 0 6px rgba(0,0,0,0.7);
    }
    .subtitulo { text-align:center; color:#f1f1f1; margin-bottom:18px; opacity:.95; }

    .card {
      background: rgba(0,0,0,0.30); /* ligero, no gris pesado */
      border-radius: 12px;
      padding: 18px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.45);
      border: 1px solid rgba(255,255,255,0.04);
    }

    .grid {
      display: grid;
      gap: 12px;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      align-items: start;
    }

    label { color:#fff; margin-top:8px; display:block; font-size:13px; }
    .form-control {
      width:100%; padding:10px; margin-top:6px;
      border-radius:8px; border:1px solid rgba(255,255,255,0.12);
      background: rgba(0,0,0,0.46); color:#fff; box-sizing:border-box;
    }
    .form-control:disabled { opacity:1; color:#fff; }

    .controls { margin-top:16px; display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
    .btn {
      padding:10px 16px; border:none; border-radius:8px; cursor:pointer;
      font-weight:600; font-size:14px; transition: .14s;
    }

    .btn-approve { background:#28a745; color:#fff; }
    .btn-approve:hover { transform:scale(1.03); }

    .btn-confirm { background:#1976d2; color:#fff; }
    .btn-reject { background:#e53935; color:#fff; }

    .btn-back {
      position: fixed; top: 20px; left: 20px; z-index:60;
      background-color: #ff6b1f; color: white; padding:8px 12px; border-radius:8px; border:none; cursor:pointer;
    }

    .notice { color:#f2f2f2; font-size:13px; opacity:.95; margin-top:6px; }

    .fecha-selected { margin-top:8px; color:#fff; font-weight:500; }
    .small { font-size:13px; opacity:0.95; }

    @media (max-width:520px) {
      .page-container { padding: 0 12px; }
    }
  </style>
</head>
<body>

  <button class="btn btn-back" onclick="window.location.href='Solicitudes_Empresas.php'">⬅️ Volver</button>

  <div class="page-container">
    <h1>Revisar Solicitud de Empresa</h1>
    <p class="subtitulo">Visualiza la solicitud, consulta el calendario con fechas ocupadas y decide aprobar o rechazar la reserva.</p>

    <div class="card">
      <form id="formReserva">
        <div id="contenidoEmpresa" class="loading">Cargando solicitud...</div>

        <div style="margin-top:12px;">
          <label for="calendar">Calendario — fechas ocupadas bloqueadas</label>
          <input id="calendar" class="form-control" placeholder="Elige una fecha..." readonly>
          <div class="fecha-selected" id="fechaSeleccionada">Ninguna fecha seleccionada</div>
          <div class="notice">Las fechas ocupadas se muestran bloqueadas y no se pueden seleccionar.</div>
        </div>

        <div class="controls">
          <button type="button" id="btnAprobarConFecha" class="btn btn-approve" style="display:none;">✔️ Aprobar y Confirmar Fecha</button>

          <button type="button" id="btnAprobarSinFecha" class="btn btn-confirm" style="display:none;">✔️ Aprobar sin fecha (Confirmada)</button>

          <button type="button" id="btnRechazar" class="btn btn-reject" style="display:none;">✖️ Rechazar Reserva</button>
        </div>

        <div style="margin-top:12px; display:flex; gap:8px; flex-wrap:wrap;">
          <div class="small">Estado actual: <strong id="estadoActual">—</strong></div>
          <div class="small">ID Solicitud: <strong id="idSolicitud">—</strong></div>
        </div>
      </form>
    </div>
  </div>

  <script>
  (function(){

    const API_BASE = "http://127.0.0.1:8000/api/";
    const token = sessionStorage.getItem("userToken") || localStorage.getItem("userToken");

    // --- CONFIGURAR ENDPOINTS (ajusta si tu backend usa otro nombre) ---
    // Endpoint que devuelve fechas ocupadas: debe devolver {"data":["YYYY-MM-DD", ...]} o similar.
    const ENDPOINT_FECHAS_RESERVADAS = "fechasReservadas"; // <-- ajusta si hace falta
    // Endpoint para ver solicitud de empresa (ya usado anteriormente)
    const ENDPOINT_VER_SOLICITUD = "verSolicitudEmpresa/"; // + id
    // Endpoint para actualizar estado de la empresa/solicitud: aquí usamos el mismo con PUT
    const ENDPOINT_ACTUALIZAR_ESTADO = "aprobarEmpresa/"; // + id
    // Endpoint/acción para liberar recursos en caso de rechazo (se intentará)
    const ENDPOINTS_LIBERAR_RECURSO = [
      "liberarRecurso",          // POST { reserva_id | empresa_id | fecha }
      "liberarRecurso/"          // POST /liberarRecurso/{id}  (fallback)
      // si tienes otro endpoint ponlo aquí
    ];
    // ------------------------------------------------------------------

    const params = new URLSearchParams(window.location.search);
    const idSolicitud = params.get("id");

    // DOM
    const contenido = document.getElementById("contenidoEmpresa");
    const calendarInput = document.getElementById("calendar");
    const fechaSeleccionadaLabel = document.getElementById("fechaSeleccionada");
    const btnAprobarConFecha = document.getElementById("btnAprobarConFecha");
    const btnAprobarSinFecha = document.getElementById("btnAprobarSinFecha");
    const btnRechazar = document.getElementById("btnRechazar");
    const estadoActualLabel = document.getElementById("estadoActual");
    const idSolicitudLabel = document.getElementById("idSolicitud");

    let fechasOcupadas = []; // YYYY-MM-DD
    let flatpickrInstance = null;
    let fechaSeleccionada = null;
    let solicitudData = null;

    function buildHeaders(json = false) {
      const h = {
        "Accept": "application/json",
        "Authorization": token ? ("Bearer " + token) : ""
      };
      if (json) h["Content-Type"] = "application/json";
      return h;
    }

    function escapeHtml(str) {
      return String(str || "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
    }

    // Normaliza distintas formas de respuestas para extraer fechas YYYY-MM-DD
    function normalizarFechas(raw) {
      if (!raw) return [];
      if (Array.isArray(raw)) {
        // array de strings o de objetos
        if (raw.every(x => typeof x === 'string')) return raw.map(s => s.split('T')[0]);
        // array de objetos
        const candidates = raw.flatMap(item => {
          if (!item) return [];
          if (typeof item === 'string') return item.split('T')[0];
          const keys = ["fecha", "date", "fecha_reserva", "dia", "day", "fecha_evento", "created_at"];
          for (const k of keys) {
            if (item[k]) {
              return (typeof item[k] === 'string') ? item[k].split('T')[0] : null;
            }
          }
          return [];
        }).filter(Boolean);
        if (candidates.length) return candidates;
      }
      if (raw.data && Array.isArray(raw.data)) return normalizarFechas(raw.data);
      if (raw.reservas && Array.isArray(raw.reservas)) return normalizarFechas(raw.reservas);
      return [];
    }

    // Intenta varios endpoints para obtener fechas ocupadas, con fallback
    async function fetchFechasOcupadas() {
      const attempts = [
        ENDPOINT_FECHAS_RESERVADAS,
        "reservasOcupadas",
        "listarReservas",
        "reservas",
        "listarTickets",
        "listarEventos"
      ];
      for (const ep of attempts) {
        try {
          const r = await fetch(API_BASE + ep, { headers: buildHeaders() });
          if (!r.ok) continue;
          const j = await r.json();
          const fs = normalizarFechas(j);
          if (fs.length) return fs.map(s => s.split('T')[0]);
        } catch (e) { /* continue */ }
      }
      return [];
    }

    // Inicializa flatpickr con las fechas a deshabilitar
    function initFlatpickr(disabledDates = []) {
      if (flatpickrInstance) flatpickrInstance.destroy();
      flatpickrInstance = flatpickr(calendarInput, {
        locale: "es",
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: disabledDates,
        onChange(selectedDates, dateStr) {
          fechaSeleccionada = dateStr || null;
          fechaSeleccionadaLabel.textContent = dateStr ? `Fecha seleccionada: ${dateStr}` : "Ninguna fecha seleccionada";
        }
      });
    }

    // Cargar solicitud de empresa
    async function cargarSolicitud() {
      try {
        const res = await fetch(API_BASE + ENDPOINT_VER_SOLICITUD + idSolicitud, { headers: buildHeaders() });
        if (!res.ok) throw new Error("HTTP " + res.status);
        const j = await res.json();
        // la solicitud puede venir en j.data, j.solicitud, j
        const empresa = j.data || j.empresa || j.solicitud || j || {};
        solicitudData = empresa;
        // Rellenar vista
        const nombre = escapeHtml(empresa.nombre_empresa || empresa.nombre || "");
        const nit = escapeHtml(empresa.nit || "");
        const correo = escapeHtml(empresa.correo || "");
        const telefono = escapeHtml(empresa.telefono || "");
        const direccion = escapeHtml(empresa.direccion || "");
        const estado = escapeHtml(empresa.estado || "Pendiente");

        contenido.innerHTML = `
          <div class="grid">
            <div>
              <label>Nombre de la Empresa</label>
              <input class="form-control" disabled value="${nombre}">
            </div>
            <div>
              <label>NIT</label>
              <input class="form-control" disabled value="${nit}">
            </div>
            <div>
              <label>Correo</label>
              <input class="form-control" disabled value="${correo}">
            </div>
            <div>
              <label>Teléfono</label>
              <input class="form-control" disabled value="${telefono}">
            </div>
            <div>
              <label>Dirección</label>
              <input class="form-control" disabled value="${direccion}">
            </div>
            <div>
              <label>Estado</label>
              <input class="form-control" disabled value="${estado}">
            </div>
          </div>
        `;

        estadoActualLabel.textContent = estado;
        idSolicitudLabel.textContent = idSolicitud || "—";

        // mostrar botones
        btnAprobarConFecha.style.display = "inline-block";
        btnAprobarSinFecha.style.display = "inline-block";
        btnRechazar.style.display = "inline-block";

      } catch (err) {
        contenido.innerHTML = `<div class="loading">❌ Error al cargar la solicitud: ${err}</div>`;
        console.error(err);
        // aún así mostrar botones para permitir acciones manuales
        btnAprobarConFecha.style.display = "inline-block";
        btnAprobarSinFecha.style.display = "inline-block";
        btnRechazar.style.display = "inline-block";
      }
    }

    // Intentar liberar recursos (varios endpoints) — llamada POST con información básica
    async function intentarLiberarRecursos(payload = {}) {
      for (const ep of ENDPOINTS_LIBERAR_RECURSO) {
        try {
          // Si ep termina con '/' asumimos que se necesita ID en ruta
          if (ep.endsWith("/")) {
            const url = API_BASE + ep + (payload.reserva_id || idSolicitud || "");
            const r = await fetch(url, { method: "POST", headers: buildHeaders(true), body: JSON.stringify(payload) });
            if (r.ok) return { ok: true, endpoint: ep, response: await safeJson(r) };
          } else {
            const url = API_BASE + ep;
            const r = await fetch(url, { method: "POST", headers: buildHeaders(true), body: JSON.stringify(payload) });
            if (r.ok) return { ok: true, endpoint: ep, response: await safeJson(r) };
          }
        } catch (e) {
          // continuar
        }
      }
      return { ok: false };
    }

    // Helper para parsear json sin fallar
    async function safeJson(response) {
      try { return await response.json(); } catch (e) { return null; }
    }

    // Actualiza estado en backend. Usa varios intentos/fallbacks para ajustarse al API
    async function actualizarEstadoEnBackend(id, payload) {
      const attempts = [
        API_BASE + ENDPOINT_ACTUALIZAR_ESTADO + id,      // primary: aprobarEmpresa/:id
        API_BASE + "actualizarReserva/" + id,          // fallback common pattern
        API_BASE + "reservas/" + id,                   // fallback REST resource
      ];
      let lastError = null;
      for (const url of attempts) {
        try {
          const r = await fetch(url, {
            method: "PUT",
            headers: buildHeaders(true),
            body: JSON.stringify(payload)
          });
          const j = await safeJson(r);
          if (r.ok) return { ok: true, url, json: j };
          lastError = j || { status: r.status, text: await r.text().catch(()=>"") };
        } catch (e) { lastError = e; }
      }
      return { ok: false, error: lastError };
    }

    // --- HANDLERS BOTONES ---

    // 1) Aprobar con fecha -> estado: "Confirmada" y fecha_reserva
    btnAprobarConFecha.addEventListener("click", async () => {
      if (!fechaSeleccionada) {
        return Swal.fire({ icon: "warning", title: "Selecciona una fecha", text: "Elige una fecha disponible antes de confirmar." });
      }
      // confirm dialog
      const confirm = await Swal.fire({
        title: "Confirmar aprobación",
        html: `Vas a aprobar y confirmar la fecha <strong>${fechaSeleccionada}</strong>. ¿Continuar?`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, confirmar",
        cancelButtonText: "Cancelar"
      });
      if (!confirm.isConfirmed) return;

      // payload
      const payload = { estado: "Confirmada", fecha_reserva: fechaSeleccionada };

      Swal.fire({ title: "Procesando...", didOpen: () => Swal.showLoading(), allowOutsideClick: false });
      const res = await actualizarEstadoEnBackend(idSolicitud, payload);
      Swal.close();

      if (res.ok) {
        Swal.fire("✅ Reserva confirmada", "La solicitud fue aprobada y la fecha asignada.", "success")
          .then(()=> location.href = "Solicitudes_Empresas.php");
      } else {
        console.error(res.error);
        Swal.fire("❌ Error", "No se pudo confirmar la reserva. Revisa el servidor.", "error");
      }
    });

    // 2) Aprobar sin fecha -> estado: "Confirmada" (sin fecha_reserva)
    btnAprobarSinFecha.addEventListener("click", async () => {
      const confirm = await Swal.fire({
        title: "Aprobar sin fecha",
        html: `Vas a aprobar esta solicitud sin asignar fecha (estado: <strong>Confirmada</strong>). ¿Continuar?`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, aprobar",
        cancelButtonText: "Cancelar"
      });
      if (!confirm.isConfirmed) return;

      const payload = { estado: "Confirmada" };

      Swal.fire({ title: "Procesando...", didOpen: () => Swal.showLoading(), allowOutsideClick: false });
      const res = await actualizarEstadoEnBackend(idSolicitud, payload);
      Swal.close();

      if (res.ok) {
        Swal.fire("✅ Aprobada", "La solicitud fue aprobada correctamente.", "success")
          .then(()=> location.href = "Solicitudes_Empresas.php");
      } else {
        console.error(res.error);
        Swal.fire("❌ Error", "No se pudo aprobar la solicitud. Revisa el servidor.", "error");
      }
    });

    // 3) Rechazar reserva -> estado: "Rechazada", pedir motivo y liberar recursos
    btnRechazar.addEventListener("click", async () => {
      const { value: motivo } = await Swal.fire({
        title: "Rechazar Reserva",
        input: "textarea",
        inputLabel: "Motivo (opcional)",
        inputPlaceholder: "Escribe por qué se rechaza la reserva (recomendado)",
        inputAttributes: { 'aria-label': 'Motivo rechazo' },
        showCancelButton: true,
        confirmButtonText: "Rechazar",
        cancelButtonText: "Cancelar",
        preConfirm: (v) => v
      });
      if (motivo === undefined) return; // cancel

      // Confirmación final
      const ok = await Swal.fire({
        title: "Confirmar rechazo",
        html: `Vas a rechazar la solicitud${motivo ? "<br><small>Motivo: "+escapeHtml(motivo)+"</small>" : ""}.<br>Esto liberará recursos asociados si aplica.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, rechazar",
        cancelButtonText: "Cancelar"
      });
      if (!ok.isConfirmed) return;

      // Primera, intentar liberar recursos
      Swal.fire({ title: "Rechazando y liberando recursos...", didOpen: () => Swal.showLoading(), allowOutsideClick: false });

      // intentar liberar recursos (payload por si backend lo requiere)
      const liberarPayload = {
        reserva_id: idSolicitud,
        empresa_id: solicitudData?.id || solicitudData?.idEmpresa || solicitudData?.empresa_id || null,
        fecha: fechaSeleccionada || null,
        motivo: motivo || null
      };
      const liberarRes = await intentarLiberarRecursos(liberarPayload);

      // Ahora actualizar estado a Rechazada
      const payload = { estado: "Rechazada", motivo_rechazo: motivo || null };
      const updateRes = await actualizarEstadoEnBackend(idSolicitud, payload);

      Swal.close();

      if (updateRes.ok) {
        const texto = liberarRes.ok ? "Recursos liberados correctamente." : "No se pudieron liberar automáticamente todos los recursos (consulta al admin).";
        Swal.fire("✖️ Reserva Rechazada", texto, "success")
          .then(()=> location.href = "Solicitudes_Empresas.php");
      } else {
        console.error(updateRes.error);
        Swal.fire("❌ Error", "No se pudo completar el rechazo. Revisa el servidor.", "error");
      }
    });

    // --- Inicialización: cargar solicitud y calendario ---
    async function init() {
      // cargar solicitud (mostrar datos)
      await cargarSolicitud();

      // cargar fechas ocupadas
      fechasOcupadas = await fetchFechasOcupadas();
      // inicializar calendario
      initFlatpickr(fechasOcupadas);

      // Si la solicitud ya tiene una fecha reservada, mostrarla
      const fechaActual = solicitudData?.fecha_reserva || solicitudData?.fecha || null;
      if (fechaActual) {
        fechaSeleccionada = (fechaActual.split ? fechaActual.split('T')[0] : fechaActual);
        fechaSeleccionadaLabel.textContent = `Fecha actual (solicitud): ${fechaSeleccionada}`;
      }
    }

    // Run
    document.addEventListener("DOMContentLoaded", init);

  })();
  </script>
</body>
</html>
