document.addEventListener('DOMContentLoaded', async () => {
  await ctrGenerarReportes();
});

// ============================================================
// 📊 FUNCIÓN PRINCIPAL
// ============================================================

async function ctrGenerarReportes() {
  const tbodyTickets = document.getElementById('tbody-reportes');
  const tbodyEventos = document.getElementById('tbody-eventos');

  tbodyTickets.innerHTML = '<tr><td colspan="6" class="loading">Cargando tickets...</td></tr>';
  tbodyEventos.innerHTML = '<tr><td colspan="9" class="loading">Cargando eventos...</td></tr>';

  try {
    await cargarReportesTickets();
    await cargarReportesEventos();
  } catch (error) {
    console.error('❌ Error al generar reportes:', error);
  }
}

// ============================================================
// 🎫 TICKETS
// ============================================================

async function cargarReportesTickets() {
  const tbody = document.getElementById('tbody-reportes');
  const urlAPI = `${ApiConexion}listarTickets`;

  try {
    const res = await fetch(urlAPI);
    const data = await res.json();

    tbody.innerHTML = '';

    if (Array.isArray(data) && data.length > 0) {
      data.forEach(ticket => {
        const row = `
          <tr>
            <td>${ticket.evento_titulo ?? '—'}</td>
            <td>${ticket.cliente_nombre ?? '—'}</td>
            <td><input type="number" class="input-precio" value="${ticket.precio ?? 0}" readonly></td>
            <td>${ticket.estado ?? '—'}</td>
            <td>${ticket.fecha_compra ?? '—'}</td>
            <td>
              <button class="btn btn-edit" onclick="editarTicket(${ticket.id})">Editar</button>
            </td>
          </tr>`;
        tbody.insertAdjacentHTML('beforeend', row);
      });
    } else {
      tbody.innerHTML = '<tr><td colspan="6" class="loading">No hay tickets registrados</td></tr>';
    }
  } catch (error) {
    console.error('❌ Error cargando tickets:', error);
    tbody.innerHTML = '<tr><td colspan="6" class="loading">Error cargando tickets</td></tr>';
  }
}

// ============================================================
// 🎭 EVENTOS
// ============================================================

async function cargarReportesEventos() {
  const tbody = document.getElementById('tbody-eventos');
  const urlAPI = `${ApiConexion}listarEventos`;

  try {
    const res = await fetch(urlAPI);
    const data = await res.json();

    tbody.innerHTML = '';

    if (Array.isArray(data) && data.length > 0) {
      data.forEach(evento => {
        const row = `
          <tr>
            <td>${evento.titulo ?? '—'}</td>
            <td>${evento.descripcion ?? '—'}</td>
            <td>${evento.fecha ?? '—'}</td>
            <td>${evento.hora_inicio ?? '—'}</td>
            <td>${evento.hora_final ?? '—'}</td>
            <td>${evento.estado ?? '—'}</td>
            <td>${evento.empresa_nombre ?? '—'}</td>
            <td>${evento.categoria_nombre ?? '—'}</td>
            <td>
              <button class="btn btn-edit" onclick="editarEvento(${evento.id})">Editar</button>
            </td>
          </tr>`;
        tbody.insertAdjacentHTML('beforeend', row);
      });
    } else {
      tbody.innerHTML = '<tr><td colspan="9" class="loading">No hay eventos registrados</td></tr>';
    }
  } catch (error) {
    console.error('❌ Error cargando eventos:', error);
    tbody.innerHTML = '<tr><td colspan="9" class="loading">Error cargando eventos</td></tr>';
  }
}

// ============================================================
// ✏️ FUNCIONES AUXILIARES
// ============================================================

function editarTicket(id) {
  Swal.fire('Editar Ticket', `Función de edición para ticket ID ${id}`, 'info');
}

function editarEvento(id) {
  Swal.fire('Editar Evento', `Función de edición para evento ID ${id}`, 'info');
}
