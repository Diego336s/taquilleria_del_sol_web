<!-- 🎭 DISEÑO TEATRAL PREMIUM -->
<link rel="stylesheet" href="vista/css/main.css?v=1.3">

<style>
  /* 🌟 CONTENEDOR PRINCIPAL */
  .seat-selection-container {
    display: grid;
    grid-template-columns: 1fr;
    /* Cambiado a una sola columna */
    gap: 2.5rem;
    /* Aumentamos el espacio entre el mapa y el resumen */
    padding: 2rem;
    max-width: 1400px;
    margin: auto;
    min-height: 100vh;
    background: radial-gradient(circle at top, rgba(255, 215, 130, 0.1), rgba(0, 0, 0, 0.9) 100%);
    backdrop-filter: blur(6px);
    border-radius: 50px;

  }

  /* 🎭 MAPA DEL TEATRO */
  .theatre-layout {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 215, 0, 0.25);
    border-radius: 25px;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: inset 0 0 30px rgba(255, 255, 255, 0.05),
      0 0 60px rgba(255, 215, 0, 0.15);
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px) saturate(180%);

  }
  
  /* 🌟 ESTILOS PARA EL ENCABEZADO DEL EVENTO */
  .event-details-header {
    border-bottom: 1px solid rgba(255, 215, 0, 0.2);
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    width: 100%;
    max-width: 900px;
  }
  .event-details-header .event-title {
    font-size: 2.2rem;
    font-weight: bold;
    color: #ffd966;
    text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
  }
  .event-details-header .event-description {
    font-size: 1rem;
    color: #e0e0e0;
    max-width: 800px;
    margin: 0.5rem auto 1rem;
    line-height: 1.6;
  }


  /* ✨ REFLEJO DE LUZ */
  .theatre-layout::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(ellipse at top, rgba(255, 240, 180, 0.15), transparent 50%);
    pointer-events: none;
  }

  /* 🎬 ESCENARIO */
  .screen {
    background: linear-gradient(135deg, #fff3b0, #ffd966);
    color: #111;
    padding: 0.8rem 0;
    width: 70%;
    margin-bottom: 2rem;
    text-align: center;
    font-weight: bold;
    letter-spacing: 0.1rem;
    border-radius: 10px;
    box-shadow: 0 0 25px rgba(255, 240, 160, 0.4);
  }

  /* 🪑 ZONAS */
  .zone {
    margin-top: 2rem;
    width: 100%;
    text-align: center;
  }

  .zone h2 {
    font-size: 1.2rem;
    color: #ffd966;
    text-transform: uppercase;
    letter-spacing: 0.1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 215, 0, 0.2);
    display: inline-block;
    padding-bottom: 0.3rem;
  }

  /* 🧱 FILAS */
  .seat-row {
    display: flex;
    justify-content: center;
    margin-bottom: 0.6rem;
  }

  /* 🪑 ASIENTOS */
  .seat {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.64), rgba(255, 255, 255, 0.05));
    height: 25px;
    width: 28px;
    margin: 0 4px;
    border-radius: 6px 6px 3px 3px;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: inset 0 -2px 3px rgba(0, 0, 0, 0.4), 0 2px 5px rgba(255, 215, 0, 0.1);
  }

  .seat:hover:not(.occupied):not(.selected) {
    transform: translateY(-2px);
    background: linear-gradient(145deg, #ffe680, #ffcc33);
    box-shadow: 0 0 10px rgba(255, 220, 100, 0.3);
  }

  .seat.selected {
    background: linear-gradient(145deg, #00cc66, #00ff80);
    box-shadow: 0 0 15px rgba(0, 255, 100, 0.6);
    transform: scale(1.05);
  }

  .seat.occupied {
    background: linear-gradient(145deg, #cc0000, #ff3333);
    box-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
    cursor: not-allowed;
    opacity: 0.8;
  }

  /* 🧾 LEYENDA */
  .legend {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    margin-top: 2.5rem;
    list-style: none;
    padding: 0;
    font-size: 0.9rem;
  }

  .legend li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #fff;
    /* Color de texto blanco para la leyenda */
  }

  .legend .seat {
    height: 15px;
    width: 18px;
    cursor: default;
    pointer-events: none;
    /* Evita que las sillas de la leyenda sean clickeables */
  }

  /* 💳 PANEL LATERAL */
  .booking-summary {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 215, 0, 0.25);
    border-radius: 25px;
    padding: 2rem;
    color: #fff;
    box-shadow: 0 0 40px rgba(255, 200, 100, 0.15);
    max-width: 800px;
    width: 100%;
    backdrop-filter: blur(10px);
    margin: 0 auto; 
  }

  .booking-summary h3 {
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    padding-bottom: 1rem;
    font-weight: 600;
    letter-spacing: 1px;
  }

  #selected-seats-list {
    list-style: none;
    padding: 0;
    margin: 1rem 0;
    max-height: 200px;
    overflow-y: auto;
  }

  #selected-seats-list li {
    padding: 0.4rem 0;
    border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
  }

  .total-price {
    font-size: 1.6rem;
    font-weight: bold;
    margin-top: 1.5rem;
    text-align: center;
    color: #ffcc33;
    text-shadow: 0 0 10px rgba(255, 200, 100, 0.5);
  }

  /* 🟡 BOTÓN */
  .btn-success {
    background: linear-gradient(135deg, #00cc66, #00ff80);
    border: none;
    font-weight: bold;
    color: #111;
    box-shadow: 0 0 10px rgba(0, 255, 100, 0.4);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
  }

  .btn-success:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(0, 255, 120, 0.6);
  }

  /* 📱 RESPONSIVIDAD */
  @media (max-width: 992px) {
    .seat-selection-container {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="seat-selection-container">
  <!-- 🎭 MAPA DE TEATRO -->
  <div class="theatre-layout" id="layout-container">
    <div id="evento-info" class="text-center mb-4">
      <h1 class="h3 text-white">Cargando evento...</h1>
      <p class="text-white-50">Por favor espera un momento</p>
    </div>
  </div>


  <!-- 💳 RESUMEN DE VENTA -->
  <div class="booking-summary ">
    <h3>Resumen de tu Venta</h3>
    <p>Asientos Seleccionados:</p>
    <ul id="selected-seats-list"></ul>
    <div class="total-price">
      Total: <span id="total-price">0</span> COP
    </div>
    <button id="btn-confirmar-reserva" type="button" class="btn btn-success w-100 py-2 mt-4">
      Confirmar Reserva
    </button>
    <p class="mt-4 text-center small">
      <a href="index.php?ruta=dashboard-usuario&id_asiento_evento=<?php echo $_GET['eventoid']; ?>">
        <i class="fas fa-arrow-left me-1"></i> Volver
      </a>
    </p>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const eventId = urlParams.get('eventoid');

    if (!eventId) {
      Swal.fire('Error', 'No se encontró el ID del evento.', 'error');
      return;
    }

    const theatreLayout = document.getElementById('layout-container');
    const selectedList = document.getElementById('selected-seats-list');
    const totalPrice = document.getElementById('total-price');
    const seatPriceDefault = 45000;

    try {
      // 🎭 Loader visual (ahora no borra el título inicial)
      const eventoInfoDiv = document.getElementById('evento-info');
      eventoInfoDiv.innerHTML = `<h1 class="h3 text-white">Cargando evento...</h1><p class="text-white-50">Por favor espera un momento</p>`;
      await new Promise(resolve => setTimeout(resolve, 1000));

      // 🔹 Hacemos las dos llamadas a la API (evento y asientos) al mismo tiempo para más eficiencia
      const [respuestaAsientos, respuestaEvento] = await Promise.all([
        fetch(`${ApiConexion}asientos/evento/${eventId}`),
        fetch(`${ApiConexion}evento/${eventId}`)
      ]);

      const resultadoAsientos = await respuestaAsientos.json();
      const resultadoEvento = await respuestaEvento.json();

      const sillas = (resultadoAsientos.asientos || []).map(a => ({
        codigo: `${a.fila}${a.numero}`,
        fila: a.fila,
        numero: parseInt(a.numero), // 👈 convertir a número
        zona: a.ubicacion,
        precio: !isNaN(parseInt(a.precio)) ? parseInt(a.precio) : seatPriceDefault,
        estado: a.disponible === 1 ? 'disponible' : 'ocupado'
      }));

      // Obtenemos el nombre del evento desde su propia respuesta
      const evento = resultadoEvento.evento || resultadoEvento;
      const eventName = evento.titulo || 'Mapa de Asientos';
      const eventDescripcion = evento.descripcion_corta || evento.descripcion || 'Selecciona tus lugares para esta increíble función.';
      const eventDate = evento.fecha ? new Date(evento.fecha).toLocaleDateString('es-CO', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
      }) : '';

      theatreLayout.innerHTML = `
        <div class="event-details-header text-center">
          <h1 class="event-title">${eventName}</h1>
          <p class="event-description">${eventDescripcion}</p>
          ${eventDate ? `<p class="text-white-50">📅 ${eventDate}</p>` : ''}
        </div>
        <div class="screen">ESCENARIO</div>
      `;

      if (!sillas.length) {
        theatreLayout.innerHTML += `<p class="text-white text-center mt-5">No hay asientos disponibles para este evento.</p>`;
        return;
      }

      // 🔹 Agrupar por zona
      const zonas = {};
      sillas.forEach(silla => {
        if (!zonas[silla.zona]) zonas[silla.zona] = [];
        zonas[silla.zona].push(silla);
      });

      // 🔹 Crear cada zona
      // Ordenamos las zonas para que 'General' siempre esté de primera
      const zonasOrdenadas = Object.keys(zonas).sort((a, b) => {
        const priority = {
          'zona general': 0,
          'palco trasero primer piso': 1,
          'palco trasero segundo piso': 1,
          'palco izquierdo primer piso': 2,
          'palco derecho primer piso': 2,
          'palco izquierdo segundo piso': 3,
          'palco derecho segundo piso': 3,
        };

        const aLower = a.toLowerCase();
        const bLower = b.toLowerCase();

        const aPrio = priority[aLower] ?? 2; // Prioridad por defecto
        const bPrio = priority[bLower] ?? 2; // Prioridad por defecto

        if (aPrio !== bPrio) {
          return aPrio - bPrio; // Ordenar por prioridad
        }
        return a.localeCompare(b); // Si tienen la misma prioridad, ordenar alfabéticamente
      });
      

      zonasOrdenadas.forEach(zona => {
        const divZona = document.createElement('div');
        divZona.classList.add('zone');

        const h2 = document.createElement('h2');
        h2.textContent = zona;
        divZona.appendChild(h2);

        // 🔸 Agrupar dentro de cada zona por fila (A, B, C...)
        const filas = {};
        zonas[zona].forEach(silla => {
          if (!filas[silla.fila]) filas[silla.fila] = [];
          filas[silla.fila].push(silla);
        });

        // 🔸 Ordenar las filas (A-Z)
        const filasOrdenadas = Object.keys(filas).sort();

        // 🔸 Dibujar cada fila en orden
        filasOrdenadas.forEach(filaNombre => {
          const fila = document.createElement('div');
          fila.classList.add('seat-row');

          // Ordenar los asientos dentro de la fila por número (1,2,3...)
          const asientosOrdenados = filas[filaNombre].sort((a, b) => a.numero - b.numero);


          asientosOrdenados.forEach(silla => {
            const divSeat = document.createElement('div');
            divSeat.classList.add('seat');
            if (silla.estado === 'ocupado') divSeat.classList.add('occupied');
            divSeat.dataset.fila = silla.fila;
            divSeat.dataset.ubicacion = silla.zona;
            divSeat.dataset.numero = silla.numero;
            divSeat.dataset.precio = silla.precio;
            fila.appendChild(divSeat);
          });

          divZona.appendChild(fila);
        });

        theatreLayout.appendChild(divZona);
      });

      // 🔹 Leyenda
      theatreLayout.insertAdjacentHTML('beforeend', `
        <ul class="legend">
          <li><div class="seat"></div> Disponible</li>
          <li><div class="seat selected"></div> Seleccionado</li>
          <li><div class="seat occupied"></div> Ocupado</li>
        </ul>
      `);

      // 🎟️ Lógica de selección
      const seats = document.querySelectorAll('.seat-row .seat');
      seats.forEach(seat => {
        seat.addEventListener('click', () => {
          if (!seat.classList.contains('occupied')) {
            seat.classList.toggle('selected');
            updateSummary();
          }
        });
      });

      function updateSummary() {
        const selectedSeats = document.querySelectorAll('.seat-row .seat.selected');
        selectedList.innerHTML = '';
        let total = 0;

        if (selectedSeats.length === 0) {
          // 🔸 Si no hay sillas seleccionadas, mostrar mensaje vacío y total 0
          selectedList.innerHTML = '<li class="text-white-50">No hay asientos seleccionados.</li>';
          totalPrice.textContent = '0';
          return;
        }

        selectedSeats.forEach(seat => {
          const ubicacion = seat.dataset.ubicacion;
          const numero = seat.dataset.numero;
          const precio = parseInt(seat.dataset.precio);

          if (ubicacion && numero && !isNaN(precio)) {
            const li = document.createElement('li');
            li.textContent = `Ubicación: ${ubicacion} - Silla: ${numero} - Precio: $ ${precio.toLocaleString('es-CO')} COP`;
            selectedList.appendChild(li);
            total += precio;
          }
        });

        totalPrice.textContent = total.toLocaleString('es-CO');
      }

      // 🌟 Lógica para el modal de confirmación
      const confirmButton = document.getElementById('btn-confirmar-reserva');
      confirmButton.addEventListener('click', () => {
        const selectedSeats = document.querySelectorAll('.seat-row .seat.selected');
        const eventTitle = document.querySelector('.event-title').textContent;

        if (selectedSeats.length === 0) {
          Swal.fire({
            icon: 'warning',
            title: 'No has seleccionado asientos',
            text: 'Por favor, elige al menos un asiento para continuar.',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#00ff7899',
            background: 'rgba(10, 10, 10, 0.9)',
            color: '#fff'
          });
          return;
        }

        let seatsSummaryHtml = '<ul style="list-style: none; padding: 0; text-align: left; max-height: 150px; overflow-y: auto;">';
        selectedSeats.forEach(seat => {
          const ubicacion = seat.dataset.ubicacion;
          const numero = seat.dataset.numero;
          const precio = parseInt(seat.dataset.precio).toLocaleString('es-CO');
          seatsSummaryHtml += `<li style="margin-bottom: 8px; font-size: 1rem; overflow: hidden;">✅ ${ubicacion} - Silla: ${numero}<span style="float: right; color: #ccc; font-weight: bold;">$ ${precio} COP</span></li>`;
        });
        seatsSummaryHtml += '</ul>';

        const totalText = document.getElementById('total-price').textContent;

        Swal.fire({
          title: '<span style="color: #ffd966;">🎭 Confirma tu Reserva</span>',
          html: `
            <div style="color: #fff; text-align: left;">
              <p style="font-size: 1.1rem;">Estás a punto de reservar para:</p>
              <h3 style="color: #fff; font-weight: bold;">${eventTitle}</h3>
              <hr style="border-color: rgba(255,215,0,0.2);">
              <p style="font-size: 1.1rem;">Tus asientos seleccionados:</p>
              ${seatsSummaryHtml}
              <hr style="border-color: rgba(255,215,0,0.2);">
              <p style="font-size: 1.5rem; font-weight: bold; text-align: center; color: #ffcc33;">Total a Pagar: $ ${totalText} COP</p>
            </div>
          `,
          icon: 'info',
          showCancelButton: true,
          confirmButtonText: '¡Confirmar y Pagar!',
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#00cc66',
          cancelButtonColor: '#d33', 
          background: 'rgba(10, 10, 10, 0.95)',
          backdrop: `rgba(0,0,0,0.8)`
        }).then((result) => {
          if (result.isConfirmed) {
            //redirigir a la pagina de pago con asientos seleccionados
          
          }
        });
      });

    } catch (error) {
      console.error('Error al cargar asientos:', error);
      Swal.fire('Error', 'No se pudieron cargar los asientos.', 'error');
    }
  });
</script>



<script src='./vista/js/Usuario/Dashboard_Usuario.js'></script> <!-- Lógica del dashboard de usuario -->