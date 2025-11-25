document.addEventListener("DOMContentLoaded", async function () {
    await iniciarCalendarioTeatro();     
});




async function cargarFechasOcupadas() {
  const res = await fetch(ApiConexion + "listarEventos");
  const data = await res.json();
  const eventos = data.eventos || data.data || [];

  // Filtrar días donde el teatro está ocupado
  // Puedes modificar los estados que cuenten como ocupados
  const fechas = eventos
    .filter(e => e.estado !== "cancelado")
    .map(e => e.fecha.split("T")[0]);

  return [...new Set(fechas)]; // elimina duplicados
}

let calendarioOcupado = null;

async function iniciarCalendarioTeatro() {
  const fechasOcupadas = await cargarFechasOcupadas();

  if (calendarioOcupado) calendarioOcupado.destroy();

  calendarioOcupado = flatpickr("#calendarioTeatro", {
    inline: true,
    locale: "es",
    dateFormat: "Y-m-d",
    onDayCreate: (dObj, dStr, fp, dayElem) => {
      const fecha = dayElem.dateObj.toISOString().split("T")[0];

      if (fechasOcupadas.includes(fecha)) {
        dayElem.classList.add("ocupada");
      }
    }
  });
}


