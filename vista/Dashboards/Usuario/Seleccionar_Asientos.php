<!-- 🎭 DISEÑO TEATRAL PREMIUM -->
<link rel="stylesheet" href="vista/css/main.css?v=1.3">

<style>
/* 🌟 CONTENEDOR PRINCIPAL */
.seat-selection-container {
  display: grid;
  grid-template-columns: 3fr 1fr;
  gap: 2rem;
  padding: 2rem;
  max-width: 1400px;
  margin: auto;
  min-height: 100vh;
  background: radial-gradient(circle at top, rgba(255,215,130,0.1), rgba(0,0,0,0.9) 100%);
  backdrop-filter: blur(6px);
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

/* ✨ REFLEJO DE LUZ */
.theatre-layout::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: radial-gradient(ellipse at top, rgba(255, 240, 180, 0.15), transparent 70%);
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
  background: linear-gradient(145deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05));
  height: 25px;
  width: 28px;
  margin: 0 4px;
  border-radius: 6px 6px 3px 3px;
  cursor: pointer;
  transition: all 0.25s ease;
  box-shadow: inset 0 -2px 3px rgba(0,0,0,0.4), 0 2px 5px rgba(255,215,0,0.1);
}
.seat:hover:not(.occupied):not(.selected) {
  transform: translateY(-2px);
  background: linear-gradient(145deg, #ffe680, #ffcc33);
  box-shadow: 0 0 10px rgba(255, 220, 100, 0.3);
}
.seat.selected {
  background: linear-gradient(145deg, #00cc66, #00ff80);
  box-shadow: 0 0 15px rgba(0,255,100,0.6);
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
}
.legend .seat {
  height: 15px;
  width: 18px;
  cursor: default;
}

/* 💳 PANEL LATERAL */
.booking-summary {
  background: rgba(255, 255, 255, 0.06);
  border: 1px solid rgba(255, 215, 0, 0.25);
  border-radius: 25px;
  padding: 2rem;
  color: #fff;
  box-shadow: 0 0 40px rgba(255, 200, 100, 0.15);
  backdrop-filter: blur(10px);
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
  border-bottom: 1px dashed rgba(255,255,255,0.1);
}
.total-price {
  font-size: 1.6rem;
  font-weight: bold;
  margin-top: 1.5rem;
  text-align: center;
  color: #ffcc33;
  text-shadow: 0 0 10px rgba(255,200,100,0.5);
}

/* 🟡 BOTÓN */
.btn-success {
  background: linear-gradient(135deg, #00cc66, #00ff80);
  border: none;
  font-weight: bold;
  color: #111;
  box-shadow: 0 0 15px rgba(0,255,100,0.4);
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.btn-success:hover {
  transform: scale(1.05);
  box-shadow: 0 0 20px rgba(0,255,120,0.6);
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
  <div class="theatre-layout">
    <div class="text-center mb-4">
      <h1 class="h3 text-white">Obra: La Tragedia de Hamlet</h1>
      <p class="text-white-50">20 de Enero, 7:00 PM</p>
    </div>

    <div class="screen">ESCENARIO</div>

    <!-- 🪟 PALCOS SEGUNDO PISO (Lados Superiores) -->
    <div class="d-flex justify-content-between w-100">
      <div class="zone">
        <h2>Palco Izquierdo Segundo Piso</h2>
        <div class="seat-row">
          <div class="seat" data-seat="P1"></div><div class="seat" data-seat="P2"></div>
          <div class="seat" data-seat="P3"></div><div class="seat occupied" data-seat="P4"></div>
          <div class="seat" data-seat="P5"></div><div class="seat" data-seat="P6"></div>
        </div>
        <div class="seat-row">
          <div class="seat" data-seat="P7"></div><div class="seat" data-seat="P8"></div>
          <div class="seat occupied" data-seat="P9"></div><div class="seat" data-seat="P10"></div>
          <div class="seat" data-seat="P11"></div><div class="seat" data-seat="P12"></div>
        </div>
      </div>

      <div class="zone">
        <h2>Palco Derecho Segundo Piso</h2>
        <div class="seat-row">
          <div class="seat" data-seat="P1"></div><div class="seat" data-seat="P2"></div>
          <div class="seat" data-seat="P3"></div><div class="seat occupied" data-seat="P4"></div>
          <div class="seat" data-seat="P5"></div><div class="seat" data-seat="P6"></div>
        </div>
        <div class="seat-row">
          <div class="seat" data-seat="P7"></div><div class="seat" data-seat="P8"></div>
          <div class="seat occupied" data-seat="P9"></div><div class="seat" data-seat="P10"></div>
          <div class="seat" data-seat="P11"></div><div class="seat" data-seat="P12"></div>
        </div>
      </div>
    </div>

    <!-- 🪟 PALCOS PRIMER PISO (Lados Medios) -->
    <div class="d-flex justify-content-between w-100 mt-4">
      <div class="zone">
        <h2>Palco Izquierdo Primer Piso</h2>
        <div class="seat-row">
          <div class="seat" data-seat="PI1"></div><div class="seat" data-seat="PI2"></div>
          <div class="seat" data-seat="PI3"></div><div class="seat occupied" data-seat="PI4"></div>
          <div class="seat" data-seat="PI5"></div><div class="seat" data-seat="PI6"></div>
        </div>
        <div class="seat-row">
          <div class="seat" data-seat="PI7"></div><div class="seat" data-seat="PI8"></div>
          <div class="seat occupied" data-seat="PI9"></div><div class="seat" data-seat="PI10"></div>
          <div class="seat" data-seat="PI11"></div><div class="seat" data-seat="PI12"></div>
        </div>
      </div>

      <div class="zone">
        <h2>Palco Derecho Primer Piso</h2>
        <div class="seat-row">
          <div class="seat" data-seat="PD1"></div><div class="seat" data-seat="PD2"></div>
          <div class="seat" data-seat="PD3"></div><div class="seat occupied" data-seat="PD4"></div>
          <div class="seat" data-seat="PD5"></div><div class="seat" data-seat="PD6"></div>
        </div>
        <div class="seat-row">
          <div class="seat" data-seat="PD7"></div><div class="seat" data-seat="PD8"></div>
          <div class="seat occupied" data-seat="PD9"></div><div class="seat" data-seat="PD10"></div>
          <div class="seat" data-seat="PD11"></div><div class="seat" data-seat="PD12"></div>
        </div>
      </div>
    </div>

    <!-- 🏛️ ZONA GENERAL (Centro) -->
    <div class="zone mt-5">
      <h2>Zona General</h2>
      <div class="seat-row">
        <div class="seat" data-seat="A1"></div><div class="seat" data-seat="A2"></div>
        <div class="seat" data-seat="A3"></div><div class="seat" data-seat="A4"></div>
        <div class="seat" data-seat="A5"></div><div class="seat" data-seat="A6"></div>
        <div class="seat" data-seat="A7"></div><div class="seat" data-seat="A8"></div>
      </div>
      <div class="seat-row">
        <div class="seat" data-seat="B1"></div><div class="seat" data-seat="B2"></div>
        <div class="seat occupied" data-seat="B3"></div><div class="seat" data-seat="B4"></div>
        <div class="seat" data-seat="B5"></div><div class="seat" data-seat="B6"></div>
        <div class="seat" data-seat="B7"></div><div class="seat" data-seat="B8"></div>
      </div>
      <div class="seat-row">
        <div class="seat" data-seat="C1"></div><div class="seat" data-seat="C2"></div>
        <div class="seat" data-seat="C3"></div><div class="seat occupied" data-seat="C4"></div>
        <div class="seat" data-seat="C5"></div><div class="seat" data-seat="C6"></div>
        <div class="seat" data-seat="C7"></div><div class="seat" data-seat="C8"></div>
      </div>
      <div class="seat-row">
        <div class="seat" data-seat="D1"></div><div class="seat" data-seat="D2"></div>
        <div class="seat" data-seat="D3"></div><div class="seat" data-seat="D4"></div>
        <div class="seat" data-seat="D5"></div><div class="seat" data-seat="D6"></div>
        <div class="seat occupied" data-seat="D7"></div><div class="seat" data-seat="D8"></div>
      </div>
      <div class="seat-row">
        <div class="seat" data-seat="E1"></div><div class="seat" data-seat="E2"></div>
        <div class="seat occupied" data-seat="E3"></div><div class="seat" data-seat="E4"></div>
        <div class="seat" data-seat="E5"></div><div class="seat" data-seat="E6"></div>
        <div class="seat" data-seat="E7"></div><div class="seat" data-seat="E8"></div>
      </div>
      <div class="seat-row">
        <div class="seat" data-seat="F1"></div><div class="seat" data-seat="F2"></div>
        <div class="seat" data-seat="F3"></div><div class="seat" data-seat="F4"></div>
        <div class="seat" data-seat="F5"></div><div class="seat occupied" data-seat="F6"></div>
        <div class="seat" data-seat="F7"></div><div class="seat" data-seat="F8"></div>
      </div>
    </div>

    <!-- 🏢 PALCO TRASERO (Fondo) -->
    <div class="zone mt-5">
      <h2>Palco Trasero Primer Piso</h2>
      <div class="seat-row">
        <div class="seat" data-seat="2A1"></div><div class="seat" data-seat="2A2"></div>
        <div class="seat" data-seat="2A3"></div><div class="seat" data-seat="2A4"></div>
        <div class="seat occupied" data-seat="2A5"></div><div class="seat" data-seat="2A6"></div>
        <div class="seat" data-seat="2A7"></div><div class="seat" data-seat="2A8"></div>
      </div>
      <div class="seat-row">
        <div class="seat" data-seat="2B1"></div><div class="seat" data-seat="2B2"></div>
        <div class="seat" data-seat="2B3"></div><div class="seat occupied" data-seat="2B4"></div>
        <div class="seat" data-seat="2B5"></div><div class="seat" data-seat="2B6"></div>
        <div class="seat" data-seat="2B7"></div><div class="seat" data-seat="2B8"></div>
      </div>
    </div>

    <!-- LEYENDA -->
    <ul class="legend">
      <li><div class="seat"></div> Disponible</li>
      <li><div class="seat selected"></div> Seleccionado</li>
      <li><div class="seat occupied"></div> Ocupado</li>
    </ul>
  </div>

  <!-- 💳 RESUMEN DE VENTA (ABAJO) -->
  <div class="booking-summary mt-5">
    <h3>Resumen de tu Venta</h3>
    <p>Asientos Seleccionados:</p>
    <ul id="selected-seats-list"></ul>
    <div class="total-price">
      Total: <span id="total-price">0</span> COP
    </div>
    <button class="btn btn-success w-100 py-2 mt-4">Confirmar Reserva</button>
    <p class="mt-4 text-center small">
      <a href="index.php?ruta=dashboard-usuario">
        <i class="fas fa-arrow-left me-1"></i> Volver
      </a>
    </p>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const seats = document.querySelectorAll('.seat');
  const selectedList = document.getElementById('selected-seats-list');
  const totalPrice = document.getElementById('total-price');
  const seatPrice = 45000;

  seats.forEach(seat => {
    seat.addEventListener('click', () => {
      if (!seat.classList.contains('occupied')) {
        seat.classList.toggle('selected');
        updateSummary();
      }
    });
  });

  function updateSummary() {
    const selectedSeats = document.querySelectorAll('.seat.selected');
    selectedList.innerHTML = '';
    let total = 0;
    selectedSeats.forEach(seat => {
      const li = document.createElement('li');
      li.textContent = `Asiento ${seat.dataset.seat}`;
      selectedList.appendChild(li);
      total += seatPrice;
    });
    totalPrice.textContent = total.toLocaleString('es-CO');
  }
});
</script>
